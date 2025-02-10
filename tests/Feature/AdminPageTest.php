<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Models\Page;
use Flippingbook\Models\Publication;
use Flippingbook\Services\PublicationFolderService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminPageTest extends FeatureTestCase
{
    public function test_admin_pages_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/pages');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.pages.index');
    }

    public function test_admin_create_page_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/pages/create');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.pages.create');
    }

    public function test_admin_edit_page_returns_a_successful_response(): void
    {
        Page::forceCreate([
            'id' => 1,
            'publication_id' => 1,
            'title' => 'Page 1',
            'ordering' => 1,
            'image' => 'page1.jpg',
        ]);

        $response = $this->get('/admin/flippingbook/pages/1/edit');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.pages.create');
    }

    public function test_store_page_with_upload_image(): void
    {
        $publicationFolderService = new PublicationFolderService();

        //Need to create Publication because there is a foreign key check
        $publication = Publication::forceCreate([
            'category_id' => 1,
            'title' => 'Publication 1',
            'state' => 1,
            'preview' => '',
            'direction' => 'right',
            'show_slider' => 1,
            'author' => 'John Smith',
            'show_author_category' => 1,
            'show_author_publication' => 1,
            'description' => '<p>Test</p>',
            'show_description_category' => 1,
            'show_description_publication' => 1,
        ]);
        $publicationFolderService->preparePublicationFolder($publication->id, true);

        Storage::fake('publications');
        $file = UploadedFile::fake()->image('page1.jpg', 1000, 1500)->size(1000);

        $response = $this->post('/admin/flippingbook/pages/store', [
            'id' => 1,
            'publication_id' => $publication->id,
            'title' => 'Page 1',
            'ordering' => 1,
            'image' => 'page1.jpg',
            'task' => 'test',
            'image_upload' => $file,
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertFileExists(
            Storage::disk('flippingbook')->path('') . 'flippingbook/publications/' . $publication->id . '/' . $file->name,
            "Given filename ['".$file->name."'] doesn't exists"
        );
    }
}
