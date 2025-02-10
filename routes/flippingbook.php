<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('flippingbook')
    ->name('flippingbook.site.')
    ->group(function() {
        Route::get('/', function() {
            return redirect(route('flippingbook.site.categories.index'));
        });

        // Flippingbook categories...
        Route::get('categories', [\Flippingbook\Http\Controllers\Site\CategoryController::class, 'index'])->name('categories.index');

        // Flippingbook publications...
        Route::match(['get', 'post'],'publications', [\Flippingbook\Http\Controllers\Site\PublicationController::class, 'index'])->name('publications.index');
        Route::get('publications/{publication}', [\Flippingbook\Http\Controllers\Site\PublicationController::class, 'show'])->name('publications.show');
    });
