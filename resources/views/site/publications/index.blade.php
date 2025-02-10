@extends('flippingbook::site.app')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_PAGE_TITLE'))

@section('content')
    <div class="h-screen bg-white flex flex-col space-y-10 items-center">
        <div class="bg-white w-full max-w-screen-lg shadow-xl rounded p-5 pb-5">

            <div class="flex flex-col min-[400px]:flex-row my-3">
                <x-flippingbook::site.menu_item_home />
                <x-flippingbook::site.menu_item_categories route="publications.index" />
            </div>

            <div class="text-center sm:text-left">
                <h1 class="text-3xl font-medium">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_PAGE_H1') }}</h1>
                @if($currCategoryId)
                    <div class="text-gray-900 mt-2">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_TEXT_CATEGORY') }}:&nbsp;
                        {{ $categories[$currCategoryId] }}
                    </div>
                @endif
            </div>

            @if($publications->isEmpty())
                <div class="flex justify-between">
                    <div class="page-bar mt-8">&nbsp;</div>
                    <x-flippingbook::site.filter_categories :categories="$filterCategoriesOptions"
                        :selected="$currCategoryId" />
                </div>

                <div class="flex flex-col mt-6">
                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_EMPTY') }}
                </div>
            @else
                <div class="flex justify-between">
                    <div class="page-bar mt-8">&nbsp;</div>
                    <x-flippingbook::site.filter_categories :categories="$filterCategoriesOptions"
                        :selected="$currCategoryId" />
                </div>

                <div class="flex flex-col mt-6">
                    @foreach($publications as $publication)
                        <div class="flex flex-col sm:flex-row my-4 text-gray-900">
                            <div class="sm:basis-1/5 mr-4 mb-3 sm:mb-0">
                                @if(!empty($publication->preview))
                                    <a href="{{ route('flippingbook.site.publications.show', $publication->id) }}">
                                        <img alt="Preview" class="max-w-none mx-auto"
                                             src="{{ '/storage/flippingbook/publications/' . $publication->id . '/thumbs/' . e($publication->preview, true) }}" />
                                    </a>
                                @else
                                    <img src="/vendor/flippingbook/icons/icon-noimage.svg" alt="Preview"
                                         class="w-[100px] max-w-none mx-auto" />
                                @endif
                            </div>
                            <div class="sm:basis-4/5">
                                <div>
                                    <a href="{{ route('flippingbook.site.publications.show', $publication->id) }}"
                                       class="text-blue-700 hover:text-blue-900">
                                        {{ $publication->title }}
                                    </a>
                                    @if(!$currCategoryId)
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_TEXT_CATEGORY') }}:&nbsp;
                                            {{ $publication->category->title }}
                                        </div>
                                    @endif
                                </div>
                                @if(!empty($publication->author) && $publication->show_author_category)
                                    <div class="my-3 text-gray-900">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATIONS_TEXT_AUTHOR') }}:&nbsp;
                                        {{ $publication->author }}
                                    </div>
                                @endif
                                @if(!empty($publication->description) && $publication->show_description_category)
                                    <div class="my-3 text-gray-900">
                                        {!! $publication->description !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="my-3">
                    {{ $publications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
