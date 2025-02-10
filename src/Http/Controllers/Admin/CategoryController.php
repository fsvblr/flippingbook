<?php

namespace Flippingbook\Http\Controllers\Admin;

use Flippingbook\Http\Requests\StoreCategoryRequest;
use Flippingbook\Models\Category;
use Flippingbook\Services\AdminSystemMessageService;
use Illuminate\Http\Request;

class CategoryController
{
    private $adminSystemMessageService;

    public function __construct(AdminSystemMessageService $adminSystemMessageService) {
        $this->adminSystemMessageService = $adminSystemMessageService;
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

        $filters = session('flippingbook.admin.categories.filters', []);
        if (!is_null($filterStatus)) {
            $filters['status'] = $filterStatus;
            session(['flippingbook.admin.categories.filters' => $filters]);
        }

        $query = Category::query();
        if (isset($filters['status']) && in_array($filters['status'], [0, 1])) {
            $query->where('state', $filters['status']);
        }
        $categories = $query
            ->withCount('publications')
            ->orderBy('id', 'ASC')
            ->paginate(config('flippingbook.admin_pagination_limit'));

        return view('flippingbook::admin.categories.index', compact('categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('flippingbook::admin.categories.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);

        $category = Category::create($validated);

        if (!empty($category->id)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_MESSAGE_STORE', ['id' => $category->id]),
                'success'
            );

            if ($task == 'apply') {
                return redirect(route('flippingbook.admin.categories.edit', $category->id));
            }
        }

        return redirect(route('flippingbook.admin.categories.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        return view('flippingbook::admin.categories.create', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreCategoryRequest  $request
     * @param  Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $task = $validated['task'];
        unset($validated['task']);

        $category->update($validated);

        if (!empty($category->id)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_MESSAGE_UPDATE', ['id' => $category->id]),
                'success'
            );

            if ($task == 'apply') {
                return redirect(route('flippingbook.admin.categories.edit', $category->id));
            }
        }

        return redirect(route('flippingbook.admin.categories.index'));
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
            'ids.*' => ['required', 'integer', 'exists:flippingbook_categories,id'],
        ]);

        if (method_exists($this, $validated['task'])) {
            return $this->{$validated['task']}($validated);
        }

        return redirect(route('flippingbook.admin.categories.index'));
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
            $category = Category::find($id);
            $category->state = $publish;
            if ($category->save()) {
                $changed[] = $id;
            }
        }

        if (!empty($changed)) {
            $text = $publish ? 'PUBLISHED' : 'UNPUBLISHED';
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_MESSAGE_' . $text, ['ids' => implode(',', $changed)]),
                'success'
            );
        }

        return redirect(route('flippingbook.admin.categories.index'));
    }

    /**
     * Execute a task 'Publish'
     *
     * @param $validated
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
        $not_empty = [];

        foreach ($validated['ids'] as $id) {
            $query = Category::query();
            $category = $query->where('id', $id)
                ->withCount('publications')
                ->first();

            if ($category->publications_count > 0) {
                $not_empty[] = $id;
            } else {
                if ($category->delete()) {
                    $deleted[] = $id;
                }
            }
        }

        if (!empty($deleted)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_MESSAGE_DELETED', ['ids' => implode(',', $deleted)]),
                'success'
            );
        }
        if (!empty($not_empty)) {
            $this->adminSystemMessageService->setAdminSystemMessage(
                __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_MESSAGE_WARNING_NOT_EMPTY', ['ids' => implode(',', $not_empty)]),
                'warning'
            );
        }

        return redirect(route('flippingbook.admin.categories.index'));
    }
}
