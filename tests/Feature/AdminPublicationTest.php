<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Models\Publication;

class AdminPublicationTest extends FeatureTestCase
{
    public function test_admin_publications_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/publications');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.publications.index');
    }

    public function test_admin_create_publication_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/publications/create');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.publications.create');
    }

    public function test_admin_edit_publication_returns_a_successful_response(): void
    {
        Publication::forceCreate([
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
            'show_description_publication' => 1
        ]);

        $response = $this->get('/admin/flippingbook/publications/1/edit');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.publications.create');
    }
}
