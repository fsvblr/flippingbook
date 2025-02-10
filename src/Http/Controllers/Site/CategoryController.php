<?php

namespace Flippingbook\Http\Controllers\Site;

use Flippingbook\Models\Category;

class CategoryController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $query = Category::query();
        $categories = $query
            ->where('state', true)
            ->orderBy('id')
            ->withCount([
                'publications' => function ($query) {
                    $query->where('state', true);
                }
            ])
            ->paginate(config('flippingbook.site_pagination_limit'));

        return view('flippingbook::site.categories.index', compact('categories'));
    }
}
