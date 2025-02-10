<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Services\AdminSystemMessageService;
use Flippingbook\Services\PublicationFolderService;
use Illuminate\Support\Facades\Storage;

class ServicesTest extends FeatureTestCase
{
    public function test_set_admin_system_message(): void
    {
        $adminSystemMessageService = new AdminSystemMessageService();

        $msgType = 'success';
        $adminSystemMessageService->setAdminSystemMessage('Unit test [' . $msgType . ']', $msgType);

        $msgType = 'warning';
        $adminSystemMessageService->setAdminSystemMessage('Unit test [' . $msgType . ']', $msgType);

        $messages = session()->pull('flippingbook.admin.messages');

        $this->assertSame($messages['success'][0], 'Unit test [success]');
        $this->assertSame($messages['warning'][0], 'Unit test [warning]');
    }

    public function test_prepare_publication_folder(): void
    {
        $publicationFolderService = new PublicationFolderService();

        $publicationFolderService->preparePublicationFolder(1, true);
        $this->assertDirectoryExists(Storage::disk('flippingbook')->path('') . 'flippingbook/publications/1/thumbs');

        $publicationFolderService->preparePublicationFolder(2, false);
        $this->assertDirectoryExists(Storage::disk('flippingbook')->path('') . 'flippingbook/publications/2/thumbs');
    }
}
