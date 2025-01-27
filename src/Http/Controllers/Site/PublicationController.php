<?php

namespace Flippingbook\Http\Controllers\Site;

use Flippingbook\Models\Category;
use Flippingbook\Models\Page;
use Flippingbook\Models\Publication;
use Illuminate\Http\Request;

class PublicationController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $filters_request = $request->get('filters');
        $filter_category = isset($filters_request['category']) ? (int)$filters_request['category'] : null;

        $filters = session('flippingbook.site.publications.filters', []);
        if(!is_null($filter_category)) {
            $filters['category'] = $filter_category;
            session(['flippingbook.site.publications.filters' => $filters]);
        }

        $query = Publication::query();
        if(!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }
        $publications = $query
            ->where('state', true)
            ->with(['pages', 'category'])
            ->orderBy('id', 'ASC')
            ->paginate(config('flippingbook.site_pagination_limit'));

        if(!empty($publications)) {
            foreach ($publications as $publication) {
                $pages = $publication->pages->toArray();
                if(empty($publication->preview)) {
                    if(!empty($pages[0]['image'])) {
                        $publication->preview = $pages[0]['image'];
                    }
                }
            }
        }

        $filter_categories_options = Category::getListCategories();

        $categories = collect($filter_categories_options)
            ->pluck('title', 'id')
            ->toArray();

        $curr_category_id = !empty($filters['category']) ? $filters['category'] : null;

        return view('flippingbook::site.publications.index',
            compact('publications', 'categories', 'curr_category_id', 'filter_categories_options'));
    }

    /**
     * Show the specified resource.
     *
     * @param Publication $publication
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Publication $publication)
    {
        if(!$publication->state) {
            abort(404);
        }

        $query = Page::query();
        $pages = $query
            ->where('publication_id', $publication->id)
            ->orderBy('ordering', 'ASC')
            ->get()
            ->toArray();

        $images = array_map(function($page) {
            return $page['image'];
        }, $pages);

        $data = [
            'images' => $images,
            'publication' => $publication->id,
            'direction' => $publication->direction,
            'showslider' => $publication->show_slider,
        ];

        $data = json_encode($data);

        return view('flippingbook::site.publications.show',
            compact('publication', 'data'));
    }
}
