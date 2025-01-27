<?php

namespace Flippingbook\Http\Controllers\Admin;

use Flippingbook\Helpers\FlippingbookHelper;
use Flippingbook\Http\Requests\StorePageRequest;
use Flippingbook\Models\Page;
use Flippingbook\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController
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
        $filter_publication = isset($filters_request['publication']) ? (int)$filters_request['publication'] : null;

        $filters = session('flippingbook.admin.pages.filters', []);
        if(!is_null($filter_publication)) {
            $filters['publication'] = $filter_publication;
            session(['flippingbook.admin.pages.filters' => $filters]);
        }

        $query = Page::query();
        if(!empty($filters['publication'])) {
            $query->where('publication_id', $filters['publication']);
        }
        $pages = $query
            ->with('publication')
            ->orderBy('publication_id', 'DESC')
            ->orderBy('ordering', 'ASC')
            ->paginate(config('flippingbook.admin_pagination_limit'));

        $publications = Publication::getListPublications();
        $data_for_building_filters = [];
        $data_for_building_filters['publications'] = $publications;

        return view('flippingbook::admin.pages.index',
            compact('pages', 'filters', 'data_for_building_filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $publications = Publication::getListPublications();
        $allowedMimeTypes = json_encode(ImageController::AllowedMimeTypes);
        $maxOrderings = Page::getMaxOrderings();

        return view('flippingbook::admin.pages.create',
            compact('publications', 'allowedMimeTypes', 'maxOrderings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePageRequest $request)
    {
        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);
        if(isset($validated['image_upload'])) {
            $image = $validated['image_upload'];
            unset($validated['image_upload']);
        }

        $page = Page::create($validated);

        if(!empty($page->id)) {
            FlippingbookHelper::setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_MESSAGE_STORE', ['id' => $page->id]),
                'success'
            );

            if(isset($image) && !empty($image->getClientOriginalName())) {
                if($image->getError() || !ImageController::pageImageUpload($page, $image, null)) {
                    FlippingbookHelper::setAdminSystemMessage(
                        __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_MESSAGE_ERROR_IMAGE_UPLOAD'),
                        'warning'
                    );
                }
            }
        }

        if($task == 'apply') {
            return redirect(route('flippingbook.admin.pages.edit', $page->id));
        }

        return redirect(route('flippingbook.admin.pages.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Page  $page
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Page $page)
    {
        $publications = Publication::getListPublications();
        $allowedMimeTypes = json_encode(ImageController::AllowedMimeTypes);
        $maxOrderings = Page::getMaxOrderings();

        return view('flippingbook::admin.pages.create',
            compact('page','publications', 'allowedMimeTypes', 'maxOrderings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePageRequest  $request
     * @param  Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StorePageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);
        if(isset($validated['image_upload'])) {
            $image = $validated['image_upload'];
            unset($validated['image_upload']);
        }

        $old_image_name = $page->image;

        if($page->update($validated)) {
            FlippingbookHelper::setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_MESSAGE_UPDATE', ['id' => $page->id]),
                'success'
            );

            if(isset($image) && !empty($image->getClientOriginalName())) {
                if($image->getError() || !ImageController::pageImageUpload($page, $image, $old_image_name)) {
                    FlippingbookHelper::setAdminSystemMessage(
                        __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_MESSAGE_ERROR_IMAGE_UPLOAD'),
                        'warning'
                    );
                }
            }
        }

        if($task == 'apply') {
            return redirect(route('flippingbook.admin.pages.edit', $page->id));
        }

        return redirect(route('flippingbook.admin.pages.index'));
    }

    /**
     * Execute a task
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function task(Request $request)
    {
        $validated = $request->validate([
            'task' => ['required', 'string'],
            'ids.*' => ['required', 'integer', 'exists:flippingbook_pages,id'],
        ]);

        if(method_exists($this, $validated['task'])) {
            return $this->{$validated['task']}($validated);
        }

        return redirect(route('flippingbook.admin.pages.index'));
    }

    /**
     * Execute a task 'Delete'
     *
     * @param  $validated
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($validated)
    {
        $deleted = [];

        foreach($validated['ids'] as $id) {
            $page = Page::find($id);
            $publication_id = $page->publication_id;
            $image = $page->image;
            if($page->delete()) {
                $deleted[] = $id;
                Storage::delete('public/flippingbook/publications/'.$publication_id.'/thumbs/'.$image);
                Storage::delete('public/flippingbook/publications/'.$publication_id.'/'.$image);
            }
        }

        if(!empty($deleted)) {
            FlippingbookHelper::setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_MESSAGE_DELETED', ['ids' => implode(',', $deleted)]),
                'success'
            );
        }

        return redirect(route('flippingbook.admin.pages.index'));
    }
}
