<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('admin/flippingbook')
    ->name('flippingbook.admin.')
    ->group(function() {
        Route::get('/', function() {
            return redirect(route('flippingbook.admin.publications.index'));
        });

        Route::get('logout', [\Flippingbook\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

        // Flippingbook categories...
        Route::match(['get', 'post'], 'categories', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories/task', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'task'])->name('categories.task');
        Route::get('categories/create', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::get('categories/{category}/edit', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('categories/store', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [\Flippingbook\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');

        // Flippingbook publications...
        Route::match(['get', 'post'], 'publications', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'index'])->name('publications.index');
        Route::post('publications/task', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'task'])->name('publications.task');
        Route::get('publications/create', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'create'])->name('publications.create');
        Route::get('publications/{publication}/edit', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'edit'])->name('publications.edit');
        Route::post('publications/store', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'store'])->name('publications.store');
        Route::put('publications/{publication}', [\Flippingbook\Http\Controllers\Admin\PublicationController::class, 'update'])->name('publications.update');

        // Flippingbook pages...
        Route::match(['get', 'post'], 'pages', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        Route::post('pages/task', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'task'])->name('pages.task');
        Route::get('pages/create', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'create'])->name('pages.create');
        Route::get('pages/{page}/edit', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
        Route::post('pages/store', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'store'])->name('pages.store');
        Route::put('pages/{page}', [\Flippingbook\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');
    });
