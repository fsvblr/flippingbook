<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Models\Category;

class FrontCategoriesTest extends FeatureTestCase
{
    public function test_check_db(): void
    {
        $dataСategories = [
            ['title' => 'Category 1', 'state' => 1, 'description' => '<p>Test 1</p>'],
            ['title' => 'Category 2', 'state' => 0, 'description' => '<p>Test 2</p>'],
            ['title' => 'Category 3', 'state' => 0, 'description' => '<p>Test 3</p>'],
        ];

        foreach ($dataСategories as $dataСategory) {
            Category::forceCreate($dataСategory);
        }

        $categories = Category::query()
            ->where('state', true)
            ->get();

        $this->assertCount(
            2,  // plus "General category" from migration
            $categories, "$categories doesn't contains 2 elements"
        );
    }

    public function test_front_categories_returns_a_successful_response(): void
    {
        $response = $this->get('/flippingbook/categories');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::site.categories.index');
    }
}
