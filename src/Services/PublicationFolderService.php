<?php

namespace Flippingbook\Services;

use Illuminate\Support\Facades\Storage;

class PublicationFolderService
{
    /**
     * Preparing folders to store pages files
     *
     * @param integer $publicationId
     * @param bool $isNew
     * @return bool
     */
    public function preparePublicationFolder($publicationId = 0, $isNew = true)
    {
        if (!Storage::disk('flippingbook')->exists('flippingbook/publications')) {
            Storage::disk('flippingbook')->makeDirectory('flippingbook/publications', '0755', true);
        }

        if ($isNew && Storage::disk('flippingbook')->exists('flippingbook/publications/' . $publicationId)) {
            Storage::disk('flippingbook')->deleteDirectory('flippingbook/publications/' . $publicationId);
        }
        if (!Storage::disk('flippingbook')->exists('flippingbook/publications/' . $publicationId)) {
            Storage::disk('flippingbook')->makeDirectory('flippingbook/publications/'.$publicationId);
        }

        if (!Storage::disk('flippingbook')->exists('flippingbook/publications/' . $publicationId . '/thumbs')) {
            Storage::disk('flippingbook')->makeDirectory('flippingbook/publications/' . $publicationId . '/thumbs');
        }

        return true;
    }
}