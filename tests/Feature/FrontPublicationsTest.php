<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Models\Publication;

class FrontPublicationsTest extends FeatureTestCase
{
    protected function populateDatabase(): void
    {
        $dataPublications = [
            [
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
            ],
            [
                'category_id' => 1,
                'title' => 'Publication 2',
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
            ],
            [
                'category_id' => 2,
                'title' => 'Publication 3',
                'state' => 0,
                'preview' => '',
                'direction' => 'left',
                'show_slider' => 0,
                'author' => 'John Smith',
                'show_author_category' => 0,
                'show_author_publication' => 0,
                'description' => '<p>Test</p>',
                'show_description_category' => 0,
                'show_description_publication' => 0
            ],
        ];

        foreach ($dataPublications as $dataPublication) {
            Publication::forceCreate($dataPublication);
        }
    }

    public function test_get_items_from_db(): void
    {
        $this->populateDatabase();

        $publications = Publication::query()
            ->where('state', true)
            ->get();

        $this->assertCount(
            2,
            $publications, "$publications doesn't contains 2 elements"
        );
    }

    public function test_front_publications_returns_a_successful_response(): void
    {
        $response = $this->get('/flippingbook/publications');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::site.publications.index');
    }

    public function test_front_publication_returns_a_successful_response(): void
    {
        $this->populateDatabase();

        $response = $this->get('/flippingbook/publications/1');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::site.publications.show');
    }

    public function test_front_publication_returns_404(): void
    {
        $response = $this->get('/flippingbook/publications/1');
        $response->assertStatus(404);
    }
}
