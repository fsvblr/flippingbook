<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Helpers\FlippingbookHelper;
use Illuminate\Support\Facades\Storage;

class HelperTest extends FeatureTestCase
{
    /*public function test_check_is_admin(): void
    {
    }*/

    public function test_set_admin_system_message(): void
    {
        $msg_type = 'success';
        FlippingbookHelper::setAdminSystemMessage('Unit test ['.$msg_type.']', $msg_type);

        $msg_type = 'warning';
        FlippingbookHelper::setAdminSystemMessage('Unit test ['.$msg_type.']', $msg_type);

        $messages = session()->pull('flippingbook.admin.messages');

        $this->assertSame($messages['success'][0], 'Unit test [success]');
        $this->assertSame($messages['warning'][0], 'Unit test [warning]');
    }

    public function test_prepare_publication_folder(): void
    {
        FlippingbookHelper::preparePublicationFolder(1, true);
        $this->assertDirectoryExists(Storage::getConfig()['root'] . '/public/flippingbook/publications/1/thumbs');

        FlippingbookHelper::preparePublicationFolder(2, false);
        $this->assertDirectoryExists(Storage::getConfig()['root'] . '/public/flippingbook/publications/2/thumbs');
    }
}
