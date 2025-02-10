@extends('flippingbook::admin.admin')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_TAG_TITLE'))

@section('content')
    <div class="flippingbook-admin-publications container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()" />
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages" />
        @endif

        <h1 class="text-gray-700 text-3xl font-medium">
            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_PAGE_TITLE') }}
        </h1>

        @if($publications->isEmpty())
            <div class="flex justify-between flex-col xl:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add']" routelist="publications" />
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['status', 'category']" :filters="$filters"
                        :data="$dataForBuildingFilters" routelist="publications" />
                </div>
            </div>

            <div class="flex flex-col mt-8">
                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_EMPTY') }}
            </div>
        @else
            <div class="flex justify-between flex-col xl:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add','delete','publish','unpublish']" routelist="publications" />
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['status', 'category']" :filters="$filters"
                        :data="$dataForBuildingFilters" routelist="publications" />
                </div>
            </div>

            <div class="flex flex-col mt-8">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <form action="{{ route('flippingbook.admin.publications.task') }}" method="POST"
                              name="flippingbook_publications" id="flippingbook_list_form">
                            @csrf

                            <table class="min-w-full">
                                <thead>
                                <tr class="border-b border-gray-200 bg-gray-300 text-gray-900 uppercase">
                                    <th class="px-1 py-3 text-center">
                                        <input type="checkbox" name="ids_all" id="status-boxes-all" value="all" />
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_HEAD_STATUS') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_HEAD_TITLE') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {!! __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_HEAD_PREVIEW') !!}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_HEAD_PAGES') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_HEAD_ID') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($publications as $publication)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-6 py-4 text-center w-12">
                                            <input type="checkbox" name="ids[]" class="status-box" value="{{ $publication->id }}" />
                                        </td>
                                        <td class="px-6 py-4 text-center w-12">
                                            <div class="flex justify-center">
                                                @if($publication->state)
                                                    <img src="/vendor/flippingbook/icons/icon-published.svg"
                                                         alt="Icon published" class="w-6" />
                                                @else
                                                    <img src="/vendor/flippingbook/icons/icon-unpublished.svg"
                                                         alt="Icon unpublished" class="w-6" />
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                <a href="{{ route('flippingbook.admin.publications.edit', $publication->id) }}"
                                                   class="text-blue-700 hover:text-blue-900">{{ $publication->title }}</a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PUBLICATIONS_TABLE_TEXT_CATEGORY') }}:&nbsp;
                                                <a href="{{ route('flippingbook.admin.categories.edit', $publication->category_id) }}"
                                                   class="text-blue-700 hover:text-blue-900">
                                                    {{ $publication->category->title }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-24">
                                            <div class="">
                                                @if($publication->preview)
                                                    <a href="{{ route('flippingbook.site.publications.show', $publication->id) }}"
                                                        target="_blank" rel="noopener noreferrer" >
                                                        <img alt="Preview"
                                                             src="{{ '/storage/flippingbook/publications/' . $publication->id . '/thumbs/' . e($publication->preview, true) }}" />
                                                    </a>
                                                @else
                                                    <img src="/vendor/flippingbook/icons/icon-noimage.svg" alt="Preview" />
                                                @endif
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-12">
                                            <div class="text-sm leading-5 text-gray-900">
                                                @if($publication->pagesCount > 0)
                                                    <a href="{{ route('flippingbook.admin.pages.index', ['filters[publication]'=>$publication->id]) }}"
                                                       class="w-10 px-2 py-2 border-2 border-gray-300 inline-block">
                                                        {{ $publication->pagesCount }}
                                                    </a>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-12">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ $publication->id }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <input type="hidden" name="task" id="flippingbook_task" value="" />
                        </form>

                        <div class="sm:pl-3">
                            {{ $publications->links() }}
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
