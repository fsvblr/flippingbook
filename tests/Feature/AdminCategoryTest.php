<?php

namespace Flippingbook\Tests\Feature;

use Flippingbook\Models\Category;

class AdminCategoryTest extends FeatureTestCase
{
    public function test_admin_categories_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/categories');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.categories.index');
    }

    public function test_admin_create_category_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/flippingbook/categories/create');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.categories.create');
    }

    public function test_admin_edit_category_returns_a_successful_response(): void
    {
        Category::forceCreate([
            'title' => 'Category 1', 'state' => 1, 'description' => '<p>Test 1</p>'
        ]);

        $response = $this->get('/admin/flippingbook/categories/1/edit');
        $response->assertStatus(200);
        $response->assertViewIs('flippingbook::admin.categories.create');
    }
}
