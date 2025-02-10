@extends('flippingbook::admin.admin')

@section('title',
    isset($publication)
    ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAG_TITLE_EDIT', ['title' => $publication->title])
    : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAG_TITLE_NEW'))

@section('content')
    <div class="flippingbook-admin-publication container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()" />
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages" />
        @endif

        <h1 class="text-gray-700 text-2xl sm:text-3xl font-medium">
            {{ isset($publication)
                ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_PAGE_TITLE_EDIT', ['title' => $publication->title])
                : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_PAGE_TITLE_NEW') }}
        </h1>

        <div class="flex justify-between flex-col lg:flex-row">
            <div class="page-bar mt-8">
                <x-flippingbook::admin.actions_item :buttons="['apply','save','cancel','preview']"
                    routelist="publications" :item="isset($publication) ? $publication : null" />
            </div>
        </div>

        <div class="mt-8">
            <form name="flippingbook_publication" enctype="multipart/form-data" method="POST" class="mt-3" id="flippingbook_item_form"
                  action="{{ isset($publication) ? route("flippingbook.admin.publications.update", $publication->id) : route("flippingbook.admin.publications.store") }}">
                @csrf

                @if(isset($publication))
                    <input type="hidden" name="_method" value="PUT" id="flippingbook_form_method" />
                @endif

                <div x-data="{ openTab: 'General' }" class="my-3">
                    <div class="mb-4 flex flex-wrap min-[480px]:flex-nowrap space-x-4 p-2 bg-gray-100 rounded-lg shadow-md">
                        <button x-on:click="openTab='General';return false;" :class="{ 'bg-white text-blue-500 font-bold': openTab === 'General' }"
                                class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAB_GENERAL') }}
                        </button>
                        <button x-on:click="openTab='Book';return false;" :class="{ 'bg-white text-blue-500 font-bold': openTab === 'Book' }"
                                class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAB_BOOK') }}
                        </button>

                        <button x-on:click="openTab='Author';return false;" :class="{ 'bg-white text-blue-500 font-bold': openTab === 'Author' }"
                                class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAB_AUTHOR') }}
                        </button>
                        <button x-on:click="openTab='Description';return false;" :class="{ 'bg-white text-blue-500 font-bold': openTab === 'Description' }"
                                class="flex-1 py-2 px-4 rounded-md focus:outline-none focus:shadow-outline-blue transition-all duration-300">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_TAB_DESCRIPTION') }}
                        </button>
                    </div>

                    <div x-show="openTab === 'General'" class="transition-all duration-300 bg-gray-100 p-4 rounded-lg shadow-md">
                        @if(isset($publication) && !empty($publication->id))
                            <div class="mb-1.5">
                                <label for="field-id" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_ID_LABEL') }}
                                </label>:&nbsp;&nbsp;{{ $publication->id }}
                            </div>
                            <input name="id" type="hidden" id="field-id" value="{{ $publication->id }}" />
                        @endif

                        <div class="mt-6 mb-1.5">
                            <label for="field-title" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_TITLE_LABEL') }}
                                &nbsp;<span class="@error('title') text-red-500 @enderror">*</span>
                            </label>
                        </div>
                        <div>
                            <input name="title" type="text" id="field-title"
                               class="w-full md:w-3/4 h-12 border border-gray-400 rounded px-3 outline-0 @error('title') border-red-500 @enderror"
                               placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_TITLE_PLACEHOLDER') }}"
                               value="{{ request()->old('title') ?? (isset($publication) ? $publication->title : '') }}"
                            />
                        </div>

                        <div class="mt-6 mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_STATE_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-state-yes" type="radio" value="1" name="state"
                                           {{ (request()->old('state') == 1 || (isset($publication) && $publication->state == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-state-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_STATE_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-state-no" type="radio" value="0" name="state"
                                           {{ ((!is_null(request()->old('state')) && request()->old('state') == 0)
                                                || (!isset($publication) && is_null(request()->old('state')))
                                                    || (isset($publication) && $publication->state == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-state-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_STATE_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="field-category" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_CATEGORY_LABEL') }}
                                &nbsp;<span class="@error('category_id') text-red-500 @enderror">*</span>
                            </label>
                        </div>
                        <div>
                            <select name="category_id" id="field-category"
                                    class="w-full md:w-3/4  h-12 border border-gray-400 rounded px-3 outline-0">
                                <option value="">
                                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_CATEGORY_OPTION_SELECT') }}
                                </option>
                                @if(!empty($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (request()->old('category_id') == $category->id || (isset($publication) && $publication->category_id == $category->id)) ? ' selected ' : '' }}
                                        >{{ $category->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mt-6 mb-1.5">
                            <label for="field-preview" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_PREVIEW_LABEL') }}
                            </label>
                        </div>
                        <div>
                            @if(isset($publication))
                                <select name="preview" id="field-preview"
                                        class="w-full md:w-3/4  h-12 border border-gray-400 rounded px-3 outline-0">
                                    <option value="">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_PREVIEW_OPTION_SELECT') }}
                                    </option>
                                    @if(!empty($images))
                                        @foreach($images as $image)
                                            <option value="{{ $image }}"
                                                {{ (request()->old('preview') == $image || $publication->preview == $image) ? ' selected ' : '' }}
                                            >{{ $image }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="preview-show" class="mt-3 mb-6">
                                    @if(!empty($publication->preview))
                                        <img alt="Publication preview" id="preview-image" class="w-[100px]"
                                             src="{{ '/storage/flippingbook/publications/' . $publication->id . '/thumbs/' . e($publication->preview, true) }}" />
                                    @else
                                        <img src="/vendor/flippingbook/icons/icon-noimage.svg"
                                             id="preview-image" alt="Publication preview" class="w-[100px]" />
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">
                                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_PREVIEW_NEW') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div x-show="openTab === 'Book'" class="transition-all duration-300 bg-gray-100 p-4 rounded-lg shadow-md">
                        <div class="mb-1.5">
                            <label for="field-multiupload" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_MULTIUPLOAD_LABEL') }}
                            </label>
                            <div class="text-gray-500">
                                {!! __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_MULTIUPLOAD_NOTE') !!}
                            </div>
                        </div>
                        <div>
                            <input name="multiupload" type="file" accept="image/*" id="field-multiupload"
                                value="{{ request()->old('multiupload') ?? null }}" />
                        </div>

                        <div class="mt-6 mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_DIRECTION_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-lime-500 rounded-l-lg">
                                    <input id="radio-direction-right" type="radio" value="right" name="direction"
                                           {{ (request()->old('direction') == 'right'
                                                || (isset($publication) && $publication->direction == 'right')
                                                    || !isset($publication)) ? ' checked ' : ''  }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-direction-right" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_DIRECTION_OPTION_RIGHT') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-yellow-500 rounded-r-lg">
                                    <input id="radio-direction-left" type="radio" value="left" name="direction"
                                           {{ (request()->old('direction') == 'left' || (isset($publication) && $publication->direction == 'left')) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-direction-left" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_DIRECTION_OPTION_LEFT') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_SLIDER_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-show_slider-yes" type="radio" value="1" name="show_slider"
                                           {{ (request()->old('show_slider') == 1 || (isset($publication) && $publication->show_slider == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_slider-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_SLIDER_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-show_slider-no" type="radio" value="0" name="show_slider"
                                           {{ ((!is_null(request()->old('show_slider')) && request()->old('show_slider') == 0)
                                                || (!isset($publication) && is_null(request()->old('show_slider')))
                                                    || (isset($publication) && $publication->show_slider == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_slider-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_SLIDER_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div x-show="openTab === 'Author'" class="transition-all duration-300 bg-gray-100 p-4 rounded-lg shadow-md">
                        <div class="mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_CATEGORY_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-show_author_category-yes" type="radio" value="1" name="show_author_category"
                                           {{ (request()->old('show_author_category') == 1 || (isset($publication) && $publication->show_author_category == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_author_category-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_CATEGORY_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-show_author_category-no" type="radio" value="0" name="show_author_category"
                                           {{ ((!is_null(request()->old('show_author_category')) && request()->old('show_author_category') == 0)
                                                || (!isset($publication) && is_null(request()->old('show_author_category')))
                                                    || (isset($publication) && $publication->show_author_category == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_author_category-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_CATEGORY_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_PUBLICATION_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-show_author_publication-yes" type="radio" value="1" name="show_author_publication"
                                           {{ (request()->old('show_author_publication') == 1 || (isset($publication) && $publication->show_author_publication == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_author_publication-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_PUBLICATION_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-show_author_publication-no" type="radio" value="0" name="show_author_publication"
                                           {{ ((!is_null(request()->old('show_author_publication')) && request()->old('show_author_publication') == 0)
                                                || (!isset($publication) && is_null(request()->old('show_author_publication')))
                                                    || (isset($publication) && $publication->show_author_publication == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_author_publication-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_AUTHOR_PUBLICATION_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="field-author" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_AUTHOR_LABEL') }}
                            </label>
                        </div>
                        <div>
                            <input name="author" type="text" id="field-author"
                               class="w-full md:w-3/4 h-12 border border-gray-400 rounded px-3 outline-0"
                               placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_AUTHOR_PLACEHOLDER') }}"
                               value="{{ request()->old('author') ?? (isset($publication) ? $publication->author : '') }}"
                            />
                        </div>
                    </div>

                    <div x-show="openTab === 'Description'" class="transition-all duration-300 bg-gray-100 p-4 rounded-lg shadow-md">
                        <div class="mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_CATEGORY_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-show_description_category-yes" type="radio" value="1" name="show_description_category"
                                           {{ (request()->old('show_description_category') == 1 || (isset($publication) && $publication->show_description_category == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_description_category-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_CATEGORY_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-show_description_category-no" type="radio" value="0" name="show_description_category"
                                           {{ ((!is_null(request()->old('show_description_category')) && request()->old('show_description_category') == 0)
                                                || (!isset($publication) && is_null(request()->old('show_description_category')))
                                                    || (isset($publication) && $publication->show_description_category == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_description_category-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_CATEGORY_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_PUBLICATION_LABEL') }}
                            </label>
                        </div>
                        <ul class="w-6 items-center rounded-lg flex">
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                                    <input id="radio-show_description_publication-yes" type="radio" value="1" name="show_description_publication"
                                           {{ (request()->old('show_description_publication') == 1 || (isset($publication) && $publication->show_description_publication == 1)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_description_publication-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_PUBLICATION_OPTION_YES') }}
                                    </label>
                                </div>
                            </li>
                            <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                                    <input id="radio-show_description_publication-no" type="radio" value="0" name="show_description_publication"
                                           {{ ((!is_null(request()->old('show_description_publication')) && request()->old('show_description_publication') == 0)
                                                || (!isset($publication) && is_null(request()->old('show_description_publication')))
                                                    || (isset($publication) && $publication->show_description_publication == 0)) ? ' checked ' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="radio-show_description_publication-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_SHOW_DESCRIPTION_PUBLICATION_OPTION_NO') }}
                                    </label>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 mb-1.5">
                            <label for="field-description" class="mb-4 font-semibold text-gray-900 dark:text-white">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_DESCRIPTION_LABEL') }}
                            </label>
                        </div>
                        <div class="w-full md:w-3/4 h-64 border border-gray-400 rounded py-1 px-1">
                            <input type="hidden" name="description" id="field-description"
                                value="{{ request()->old('description') ?? (isset($publication) ? $publication->description : '') }}"
                            />
                            <trix-editor input="field-description" class="trix-content h-5/6 bg-gray-100"
                                placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATION_FIELD_DESCRIPTION_PLACEHOLDER') }}"></trix-editor>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="task" id="flippingbook_task" value="" />
            </form>
        </div>
    </div>
@endsection

@once
    {{ Vite::useBuildDirectory('vendor/flippingbook/build')
            ->withEntryPoints(['resources/css/trix.css', 'resources/js/trix.umd.min.js']) }}
@endonce

@push('css')
    <style>
        .trix-content {
            overflow-y: auto;
        }
        .trix-button--icon-attach {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            {{-- "Don't accept dropped or pasted files in the Trix editor" --}}
            document.addEventListener('trix-file-accept', function(event) {
                event.preventDefault();
            });

            let fieldPreview = document.getElementById('field-preview');
            if(fieldPreview) {
                fieldPreview.addEventListener('change', function() {
                    let img = this.value,
                        fieldId = document.getElementById('field-id'),
                        previewImage = document.getElementById('preview-image');
                    if (!previewImage) {
                        return false;
                    }
                    if (!fieldId || !img) {
                        previewImage.setAttribute('src', '/vendor/flippingbook/icons/icon-noimage.svg');
                    } else {
                        previewImage.setAttribute('src', '/storage/flippingbook/publications/' + fieldId.value + '/thumbs/' + img);
                    }
                });
            }
        })();
    </script>
@endpush
