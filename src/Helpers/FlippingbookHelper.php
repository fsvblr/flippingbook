<?php
namespace Flippingbook\Helpers;

use Illuminate\Support\Facades\Storage;

class FlippingbookHelper
{
    /**
     * Check if the current user is an administrator Flippingbook
     *
     * @return bool
     */
    public static function isFlippingbookAdmin()
    {
        return auth()->id() === config('flippingbook.admin_user_id');
    }

    /**
     * Sending a system message in the admin panel.
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    public static function setAdminSystemMessage($text='', $type='success')
    {
        $messages = session('flippingbook.admin.messages', []);

        if(empty($messages[$type])) {
            $messages[$type] = [];
        }

        $messages[$type][] = $text;

        session(['flippingbook.admin.messages' => $messages]);
    }

    /**
     * Preparing folders to store pages files
     *
     * @param integer $publication_id
     * @param bool $isNew
     * @return bool
     */
    public static function preparePublicationFolder($publication_id=0, $isNew=true)
    {
        if(!Storage::exists('public/flippingbook/publications')){
            Storage::makeDirectory('public/flippingbook/publications', '0755', true);
        }

        if($isNew && Storage::exists('public/flippingbook/publications/'.$publication_id)){
            Storage::deleteDirectory('public/flippingbook/publications/'.$publication_id);
        }
        if(!Storage::exists('public/flippingbook/publications/'.$publication_id)){
            Storage::makeDirectory('public/flippingbook/publications/'.$publication_id);
        }

        if(!Storage::exists('public/flippingbook/publications/'.$publication_id.'/thumbs')){
            Storage::makeDirectory('public/flippingbook/publications/'.$publication_id.'/thumbs');
        }

        return true;
    }
}
