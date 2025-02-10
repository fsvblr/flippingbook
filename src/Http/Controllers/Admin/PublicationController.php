<?php

namespace Flippingbook\Http\Controllers\Admin;

use Flippingbook\Http\Requests\StorePublicationRequest;
use Flippingbook\Models\Category;
use Flippingbook\Models\Publication;
use Flippingbook\Services\AdminSystemMessageService;
use Flippingbook\Services\PublicationFolderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController
{
    private $adminSystemMessageService;
    private $publicationFolderService;

    public function __construct(
        AdminSystemMessageService $adminSystemMessageService,
        PublicationFolderService $publicationFolderService
    ) {
        $this->adminSystemMessageService = $adminSystemMessageService;
        $this->publicationFolderService = $publicationFolderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $filtersRequest = $request->get('filters');
        $filterStatus = isset($filtersRequest['status']) ? (int)$filtersRequest['status'] : null;
        $filterCategory = isset($filtersRequest['category']) ? (int)$filtersRequest['category'] : null;

        $filters = session('flippingbook.admin.publications.filters', []);
        if (!is_null($filterStatus)) {
            $filters['status'] = $filterStatus;
            session(['flippingbook.admin.publications.filters' => $filters]);
        }
        if (!is_null($filterCategory)) {
            $filters['category'] = $filterCategory;
            session(['flippingbook.admin.publications.filters' => $filters]);
        }

        $query = Publication::query();
        if (isset($filters['status']) && in_array($filters['status'], [0, 1])) {
            $query->where('state', $filters['status']);
        }
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }
        $publications = $query
            ->with(['pages', 'category'])
            ->orderBy('id', 'ASC')
            ->paginate(config('flippingbook.admin_pagination_limit'));

        if (!empty($publications)) {
            foreach ($publications as $publication) {
                $publication->pagesCount = $publication->pages->count();
                $pages = $publication->pages->toArray();
                if (empty($publication->preview)) {
                    if (!empty($pages[0]['image'])) {
                        $publication->preview = $pages[0]['image'];
                    }
                }
            }
        }

        $categories = Category::getListCategories();
        $dataForBuildingFilters = [
            'categories' => $categories,
        ];

        return view('flippingbook::admin.publications.index',
            compact('publications','filters', 'dataForBuildingFilters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::getListCategories();

        return view('flippingbook::admin.publications.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePublicationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePublicationRequest $request) {
        $zip = $request->file('multiupload');
        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);

        $publication = Publication::create($validated);

        if (!empty($publication->id)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_STORE', ['id' => $publication->id]),
                'success'
            );

            if ($this->publicationFolderService->preparePublicationFolder($publication->id, true)) {
                if (!empty($zip) && !empty($zip->getClientOriginalName())) {
                    if ($zip->getError() || !ImageController::multiupload($publication->id, $zip)) {
                        $this->adminSystemMessageService->setAdminSystemMessage(
                            __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_ERROR_MULTIUPLOAD'),
                            'warning'
                        );
                    }
                }
            }

            if ($task == 'apply') {
                return redirect(route('flippingbook.admin.publications.edit', $publication->id));
            }
        }

        return redirect(route('flippingbook.admin.publications.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Publication  $publication
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Publication $publication)
    {
        $categories = Category::getListCategories();

        $pages = $publication->pages->toArray();

        $images = array_map(function ($page, $index) use ($publication) {
            if ($index === 0 && empty($publication->preview)) {
                $publication->preview = $page['image'];
            }
            return $page['image'];
        }, $pages, array_keys($pages));

        return view('flippingbook::admin.publications.create',
            compact('publication', 'categories', 'images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePublicationRequest  $request
     * @param  Publication  $publication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StorePublicationRequest $request, Publication $publication) {
        $zip = $request->file('multiupload');

        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);

        if ($publication->update($validated)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_UPDATE', ['id' => $publication->id]),
                'success'
            );
        }

        if ($this->publicationFolderService->preparePublicationFolder($publication->id, false)) {
            if (!empty($zip) && !empty($zip->getClientOriginalName())) {
                if ($zip->getError() || !ImageController::multiupload($publication->id, $zip)) {
                    $this->adminSystemMessageService->setAdminSystemMessage(
                        __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_ERROR_MULTIUPLOAD'),
                        'warning'
                    );
                }
            }
        }

        if ($task == 'apply') {
            return redirect(route('flippingbook.admin.publications.edit', $publication->id));
        }

        return redirect(route('flippingbook.admin.publications.index'));
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
            'ids.*' => ['required', 'integer', 'exists:flippingbook_publications,id'],
        ]);

        if (method_exists($this, $validated['task'])) {
            return $this->{$validated['task']}($validated);
        }

        return redirect(route('flippingbook.admin.publications.index'));
    }

    /**
     * Change entity status
     *
     * @param $validated
     * @param bool $publish
     * @return mixed
     */
    public function changeStatus($validated, $publish)
    {
        $changed = [];

        foreach ($validated['ids'] as $id) {
            $publication = Publication::find($id);
            $publication->state = $publish;
            if ($publication->save()) {
                $changed[] = $id;
            }
        }

        if (!empty($changed)) {
            $text = $publish ? 'PUBLISHED' : 'UNPUBLISHED';
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_' . $text, ['ids' => implode(',', $changed)]),
                'success'
            );
        }

        return redirect(route('flippingbook.admin.publications.index'));
    }

    /**
     * Execute a task 'Publish'
     *
     * @param  $validated
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish($validated)
    {
        return $this->changeStatus($validated, true);
    }

    /**
     * Execute a task 'Unpublish'
     *
     * @param  $validated
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unpublish($validated)
    {
        return $this->changeStatus($validated, false);
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

        foreach ($validated['ids'] as $id) {
            $publication = Publication::find($id);
            if ($publication->delete()) {
                $deleted[] = $id;
                Storage::disk('flippingbook')->deleteDirectory('flippingbook/publications/' . $id);
            }
        }

        if (!empty($deleted)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_MESSAGE_DELETED', ['ids' => implode(',', $deleted)]),
                'success'
            );
        }

        return redirect(route('flippingbook.admin.publications.index'));
    }
}
