@extends('flippingbook::site.app')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_CATEGORIES_PAGE_TITLE'))

@section('content')
    <div class="h-screen bg-white flex flex-col space-y-10 items-center">
        <div class="bg-white w-full max-w-screen-lg shadow-xl rounded p-5">

            <div class="flex flex-col min-[400px]:flex-row min-[400px]:justify-between my-3">
                <x-flippingbook::site.menu_item_home/>
                <x-flippingbook::site.menu_item_publications dir="right"/>
            </div>

            <h1 class="text-3xl font-medium">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_CATEGORIES_PAGE_H1') }}</h1>

            @if($categories->isEmpty())
                <div class="flex flex-col mt-8">
                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_CATEGORIES_EMPTY') }}
                </div>
            @else
                <div class="flex flex-col mt-8">
                    @foreach($categories as $category)
                        <div class="my-4 text-gray-900">
                            <div>
                                <a href="{{ route('flippingbook.site.publications.index') }}?filters[category]={{ $category->id }}"
                                   class="text-blue-700 hover:text-blue-900">
                                    {{ $category->title }}
                                </a>
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_CATEGORIES_PUBLICATION_TEXT') }}:&nbsp;
                                    {{ $category->publications_count }}
                                </div>
                            </div>
                            @if($category->description)
                                <div class="my-3 text-gray-900">
                                    {!! $category->description !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="my-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
