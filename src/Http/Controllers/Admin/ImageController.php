<?php

namespace Flippingbook\Http\Controllers\Admin;

use Flippingbook\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ZipArchive;

class ImageController
{
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Uploading a zip archive with images of publication pages
     *
     * @param $publicationId
     * @param $archive
     * @return bool
     * @throws \ImagickException
     */
    public static function multiupload($publicationId = null, $archive = null)
    {
        if (!$publicationId || !$archive || $archive->extension() != 'zip') {
            return false;
        }

        $zipFilePath = $archive->path();
        $zip = new ZipArchive();
        $zipStatus = $zip->open($zipFilePath);
        if ($zipStatus !== true) {
            throw new \Exception('Could not open ZIP file. Error code: ' . $zipStatus);
        }

        $publicationPath = 'flippingbook/publications/' . $publicationId;
        $originalPath = $publicationPath . '/original';

        if (Storage::disk('flippingbook')->exists($originalPath)) {
            Storage::disk('flippingbook')->deleteDirectory($originalPath);
        }
        Storage::disk('flippingbook')->makeDirectory($originalPath);

        $zip->extractTo(Storage::disk('flippingbook')->path('') . $originalPath);
        $zip->close();
        Storage::delete($zipFilePath);

        $files = Storage::disk('flippingbook')->allFiles($originalPath);
        if (empty($files)) {
            return false;
        }

        uasort($files, 'strnatcmp');
        $files = array_values($files);

        $maxOrdering = Page::where('publication_id', $publicationId)->max('ordering');

        $i = 1;
        foreach ($files as $file) {
            if (!in_array(Storage::disk('flippingbook')->mimeType($file), self::ALLOWED_MIME_TYPES)) {
                continue;
            }

            $validated = [
                'publication_id' => $publicationId,
                'title' => 'Page ' . $i,
                'ordering' => (int)$maxOrdering + $i,
                'image' => '/page_' . $publicationId . '_x.webp',
            ];

            $page = Page::create($validated);

            if (!empty($page->id)) {
                if (self::saveSetImages($page, $file, true)) {
                    Page::where('id', $page->id)->update([
                        'title' => 'Page ' . $publicationId . '-' . $page->id,
                        'image' => 'page_' . $publicationId . '_' . $page->id . '.webp',
                    ]);
                }
            }

            $i++;
        }

        Storage::disk('flippingbook')->deleteDirectory($originalPath);

        return true;
    }

    /**
     * Uploading a single image of a publication page
     *
     * @param $page
     * @param $image
     * @param $oldImageName
     * @return bool
     * @throws \ImagickException
     */
    public static function pageImageUpload($page, $image, $oldImageName = null)
    {
        if (!in_array($image->getClientMimeType(), self::ALLOWED_MIME_TYPES)) {
            return false;
        }

        $publicationPath = 'flippingbook/publications/' . $page->publication_id;
        $originalPath = $publicationPath . '/original';

        if (Storage::disk('flippingbook')->exists($originalPath)) {
            Storage::disk('flippingbook')->deleteDirectory($originalPath);
        }
        Storage::disk('flippingbook')->makeDirectory($originalPath);

        if ($oldImageName) {
            if (Storage::disk('flippingbook')->exists($publicationPath . '/' . $oldImageName)) {
                Storage::disk('flippingbook')->delete($publicationPath . '/' . $oldImageName);
            }
            if (Storage::disk('flippingbook')->exists($publicationPath . '/thumbs/' . $oldImageName)) {
                Storage::disk('flippingbook')->delete($publicationPath . '/thumbs/' . $oldImageName);
            }
        }

        $path = Storage::disk('flippingbook')->putFile($originalPath, $image);

        $needUpdate = false;
        $name_duplicate = DB::table('flippingbook_pages')
            ->select('id')
            ->where('id', '!=', $page->id)
            ->where('publication_id', $page->publication_id)
            ->where('image', $page->image)
            ->first();
        if (!empty($name_duplicate)) {
            $needUpdate = true;
            $image_name = pathinfo($page->image)['filename'];
            $page->image = $image_name . '__' . time() . '.' . $image->extension();
        }

        if (self::saveSetImages($page, $path)) {
            Storage::disk('flippingbook')->deleteDirectory($originalPath);

            if ($needUpdate) {
                Page::where('id', $page->id)->update([
                    'image' => $page->image,
                ]);
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Saves a set of images (full and thumbnails) of a given size.
     *
     * @param $page
     * @param $originalImageStorePath
     * @param $multiupload
     * @return bool
     * @throws \ImagickException
     */
    private static function saveSetImages($page, $originalImageStorePath = null, $multiupload = false)
    {
        if (!$originalImageStorePath) {
            return false;
        }

        if (!extension_loaded('imagick')
            || empty(\Imagick::queryformats())  //maybe if environment variables are not set in phpstorm terminal
        ){
            return false;
        }

        $publicationPath = 'flippingbook/publications/' . $page->publication_id;
        $publicationPathFull = Storage::disk('flippingbook')->path('') . $publicationPath;

        $im = new Imagick(Storage::disk('flippingbook')->path('') . $originalImageStorePath);

        $ratio = $im->getImageWidth() / $im->getImageHeight();
        $sizes = self::getImageSizesByRatio($ratio);

        $im->resizeImage(
            $sizes['thumb']['width'],
            $sizes['thumb']['height'],
            imagick::FILTER_UNDEFINED ,
            1
        );
        if ($multiupload) {
            $im->writeImage($publicationPathFull . '/thumbs/page_' . $page->publication_id . '_' . $page->id . '.webp');
        } else {
            $im->writeImage($publicationPathFull . '/thumbs/' . $page->image);
        }
        $im->destroy();

        $im = new Imagick(Storage::disk('flippingbook')->path('') . $originalImageStorePath);
        $im->resizeImage(
            $sizes['full']['width'],
            $sizes['full']['height'],
            imagick::FILTER_UNDEFINED ,
            1
        );
        if ($multiupload) {
            $im->writeImage($publicationPathFull . '/page_' . $page->publication_id . '_' . $page->id . '.webp');
        } else {
            $im->writeImage($publicationPathFull . '/' . $page->image);
        }
        $im->destroy();

        return true;
    }

    /**
     * Getting preset image sizes based on its ratio.
     *
     * @param float $ratio
     * @return array[]
     */
    private static function getImageSizesByRatio($ratio = 1)
    {
        if ($ratio < 1) {
            $thumb_w = config('flippingbook.image_thumb_small_side');
            $full_w = config('flippingbook.image_full_small_side');
        } else {
            $thumb_w = config('flippingbook.image_thumb_big_side');
            $full_w = config('flippingbook.image_full_small_side');
        }
        $thumb_h = (int)($thumb_w / $ratio);
        $full_h = (int)($full_w / $ratio);

        return [
            'thumb' => ['width' => $thumb_w, 'height' => $thumb_h],
            'full' => ['width' => $full_w, 'height' => $full_h],
        ];
    }
}
