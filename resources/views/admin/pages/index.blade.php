@extends('flippingbook::admin.admin')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_TAG_TITLE'))

@section('content')
    <div class="flippingbook-admin-pages container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()"/>
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages"/>
        @endif

        <h1 class="text-gray-700 text-3xl font-medium">
            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_PAGE_TITLE') }}
        </h1>

        @if($pages->isEmpty())
            <div class="flex justify-between flex-col xl:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add']" routelist="pages"/>
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['publication']" :filters="$filters"
                        :data="$dataForBuildingFilters" routelist="pages"/>
                </div>
            </div>

            <div class="flex flex-col mt-8">
                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_EMPTY') }}
            </div>
        @else
            <div class="flex justify-between flex-col xl:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add','delete']" routelist="pages"/>
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['publication']" :filters="$filters"
                       :data="$dataForBuildingFilters" routelist="pages"/>
                </div>
            </div>

            <div class="flex flex-col mt-8">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <form action="{{ route('flippingbook.admin.pages.task') }}" method="POST"
                              name="flippingbook_pages" id="flippingbook_list_form">
                            @csrf

                            <table class="min-w-full">
                                <thead>
                                <tr class="border-b border-gray-200 bg-gray-300 text-gray-900 uppercase">
                                    <th class="px-1 py-3 text-center">
                                        <input type="checkbox" name="ids_all" id="status-boxes-all" value="all"/>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_HEAD_TITLE') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_HEAD_IMAGE') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_HEAD_ID') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($pages as $page)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-6 py-4 text-center w-12">
                                            <input type="checkbox" name="ids[]" class="status-box"
                                                   value="{{ $page->id }}"/>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                <a href="{{ route('flippingbook.admin.pages.edit', $page->id) }}"
                                                   class="text-blue-700 hover:text-blue-900">{{ $page->title }}</a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGES_TABLE_TEXT_PUBLICATION') }}
                                                :&nbsp;
                                                <a href="{{ route('flippingbook.admin.publications.edit', $page->publication_id) }}"
                                                   class="text-blue-700 hover:text-blue-900">
                                                    {{ $page->publication->title }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 w-96">
                                            <div class="flex justify-center">
                                                <img src="{{ '/storage/flippingbook/publications/' . $page->publication_id . '/thumbs/' . $page->image }}"
                                                     alt="Page image" class="h-20"/>
                                            </div>
                                            <div class="mt-1 text-sm leading-5 text-gray-900 text-center">
                                                {{ $page->image }}
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-12">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ $page->id }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <input type="hidden" name="task" id="flippingbook_task" value=""/>
                        </form>

                        <div class="sm:pl-3">
                            {{ $pages->links() }}
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
