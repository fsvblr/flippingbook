@extends('flippingbook::site.app')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATION_PAGE_TITLE', ['title' => $publication->title]))

@section('content')
    <div class="h-screen bg-white flex flex-col space-y-10 items-center pb-5">
        <div class="bg-white w-full max-w-screen-lg shadow-xl rounded p-5">

            <div class="flex flex-col min-[550px]:flex-row my-3">
                <x-flippingbook::site.menu_item_home />
                <x-flippingbook::site.menu_item_categories route="publications.show" />
                <x-flippingbook::site.menu_item_publications dir="left" />
            </div>

            <h1 class="text-3xl font-medium">{{ $publication->title }}</h1>

            @if(!empty($publication->author) && $publication->show_author_publication)
                <div class="my-3 text-gray-900">
                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATION_PAGE_TEXT_AUTHOR') }}:&nbsp;
                    {{ $publication->author }}
                </div>
            @endif

            @if(!empty($publication->description) && $publication->show_description_publication)
                <div class="my-3 text-gray-900">
                    {!! $publication->description !!}
                </div>
            @endif

            <div class="flex flex-col items-center mt-8 mb-4">
                <div id="flippingbook" class="flippingbook-wrap"
                    data-book="{{ $data }}"
                    data-lang="{{ json_encode([
                            'Page' => __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATION_PAGE_TEXT_PAGE'),
                            'of' => __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_PUBLICATION_PAGE_TEXT_OF')
                        ]) }}"
                ></div>
            </div>
        </div>
    </div>
    {{ Vite::useBuildDirectory('vendor/flippingbook/build')
            ->withEntryPoints(['resources/js/flippingbook.js']) }}
@endsection
