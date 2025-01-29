<?php

namespace Flippingbook\Http\Controllers\Admin;

use Flippingbook\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ZipArchive;

class ImageController
{
    const AllowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Uploading a zip archive with images of publication pages
     *
     * @param $publication_id
     * @param $archive
     * @return bool
     * @throws \ImagickException
     */
    public static function multiupload($publication_id=null, $archive=null)
    {
        if(!$publication_id || !$archive || $archive->extension() != 'zip') {
            return false;
        }

        $zipFilePath = $archive->path();
        $zip = new ZipArchive();
        $zipStatus = $zip->open($zipFilePath);
        if ($zipStatus !== true) {
            throw new \Exception('Could not open ZIP file. Error code: ' . $zipStatus);
        }

        $publication_path = 'flippingbook/publications/'.$publication_id;
        $original_path = $publication_path.'/original';

        if(Storage::disk('flippingbook')->exists($original_path)){
            Storage::disk('flippingbook')->deleteDirectory($original_path);
        }
        Storage::disk('flippingbook')->makeDirectory($original_path);

        $zip->extractTo(Storage::disk('flippingbook')->path('') . $original_path);
        $zip->close();
        Storage::delete($zipFilePath);

        $files = Storage::disk('flippingbook')->allFiles($original_path);
        if(empty($files)) {
            return false;
        }

        uasort($files, 'strnatcmp');
        $files = array_values($files);

        $maxOrdering = Page::where('publication_id', $publication_id)->max('ordering');

        $i = 1;
        foreach($files as $file) {
            if(!in_array(Storage::disk('flippingbook')->mimeType($file), self::AllowedMimeTypes)) {
                continue;
            }

            $validated = [
                'publication_id' => $publication_id,
                'title' => 'Page ' . $i,
                'ordering' => (int)$maxOrdering + $i,
                'image' => '/page_'.$publication_id.'_x.webp',
            ];

            $page = Page::create($validated);

            if(!empty($page->id)) {
                if(self::saveSetImages($page, $file, true)) {
                    Page::where('id', $page->id)->update([
                        'title' => 'Page '.$publication_id.'-'.$page->id,
                        'image' => 'page_'.$publication_id.'_'.$page->id.'.webp',
                    ]);
                }
            }

            $i++;
        }

        Storage::disk('flippingbook')->deleteDirectory($original_path);

        return true;
    }

    /**
     * Uploading a single image of a publication page
     *
     * @param $page
     * @param $image
     * @param $old_image_name
     * @return bool
     * @throws \ImagickException
     */
    public static function pageImageUpload($page, $image, $old_image_name=null)
    {
        if(!in_array($image->getClientMimeType(), self::AllowedMimeTypes)) {
            return false;
        }

        $publication_path = 'flippingbook/publications/'.$page->publication_id;
        $original_path = $publication_path.'/original';

        if(Storage::disk('flippingbook')->exists($original_path)){
            Storage::disk('flippingbook')->deleteDirectory($original_path);
        }
        Storage::disk('flippingbook')->makeDirectory($original_path);

        if($old_image_name) {
            if(Storage::disk('flippingbook')->exists($publication_path.'/'.$old_image_name)) {
                Storage::disk('flippingbook')->delete($publication_path.'/'.$old_image_name);
            }
            if(Storage::disk('flippingbook')->exists($publication_path.'/thumbs/'.$old_image_name)) {
                Storage::disk('flippingbook')->delete($publication_path.'/thumbs/'.$old_image_name);
            }
        }

        $path = $image->store($original_path);

        $need_update = false;
        $name_duplicate = DB::table('flippingbook_pages')
            ->select('id')
            ->where('id', '!=', $page->id)
            ->where('publication_id', $page->publication_id)
            ->where('image', $page->image)
            ->first();
        if(!empty($name_duplicate)) {
            $need_update = true;
            $image_name = pathinfo($page->image)['filename'];
            $page->image = $image_name . '__' . time() . '.' . $image->extension();
        }

        if(self::saveSetImages($page, $path)) {
            Storage::disk('flippingbook')->deleteDirectory($original_path);

            if($need_update) {
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
     * @param $original_image_store_path
     * @param $multiupload
     * @return bool
     * @throws \ImagickException
     */
    private static function saveSetImages($page, $original_image_store_path=null, $multiupload=false)
    {
        if(!$original_image_store_path) {
            return false;
        }

        if (!extension_loaded('imagick')
            || empty(\Imagick::queryformats())  //maybe if environment variables are not set in phpstorm terminal
        ){
            return false;
        }

        $publication_path = 'flippingbook/publications/'. $page->publication_id;
        $publication_path_full = Storage::disk('flippingbook')->path('') . $publication_path;

        $im = new Imagick(Storage::disk('flippingbook')->path('') . $original_image_store_path);

        $ratio = $im->getImageWidth() / $im->getImageHeight();
        $sizes = self::getImageSizesByRatio($ratio);

        $im->resizeImage(
            $sizes['thumb']['width'],
            $sizes['thumb']['height'],
            imagick::FILTER_UNDEFINED ,
            1
        );
        if($multiupload) {
            $im->writeImage($publication_path_full.'/thumbs/page_'.$page->publication_id.'_'.$page->id.'.webp');
        } else {
            $im->writeImage($publication_path_full.'/thumbs/'.$page->image);
        }
        $im->destroy();

        $im = new Imagick(Storage::disk('flippingbook')->path('') . $original_image_store_path);
        $im->resizeImage(
            $sizes['full']['width'],
            $sizes['full']['height'],
            imagick::FILTER_UNDEFINED ,
            1
        );
        if($multiupload) {
            $im->writeImage($publication_path_full.'/page_'.$page->publication_id.'_'.$page->id.'.webp');
        } else {
            $im->writeImage($publication_path_full.'/'.$page->image);
        }
        $im->destroy();

        return true;
    }

    private static function getImageSizesByRatio($ratio=1)
    {
        if($ratio < 1) {
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
