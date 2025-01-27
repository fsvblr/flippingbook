@extends('flippingbook::admin.admin')

@section('title', __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_TAG_TITLE'))

@section('content')
    <div class="flippingbook-admin-categories container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()" />
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages" />
        @endif

        <h1 class="text-gray-700 text-3xl font-medium">
            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_PAGE_TITLE') }}
        </h1>

        @if($categories->isEmpty())
            <div class="flex justify-between flex-col lg:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add']" routelist="categories" />
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['status']" :filters="$filters" routelist="categories" />
                </div>
            </div>

            <div class="flex flex-col mt-8">
                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_EMPTY') }}
            </div>
        @else
            <div class="flex justify-between flex-col lg:flex-row">
                <div class="page-bar mt-8">
                    <x-flippingbook::admin.actions_list :buttons="['add','delete','publish','unpublish']" routelist="categories" />
                </div>

                <div class="page-filters mt-8">
                    <x-flippingbook::admin.filters :filters_list="['status']" :filters="$filters" routelist="categories" />
                </div>
            </div>

            <div class="flex flex-col mt-8">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <form action="{{ route('flippingbook.admin.categories.task') }}" method="POST"
                              name="flippingbook_categories" id="flippingbook_list_form">
                            @csrf

                            <table class="min-w-full">
                                <thead>
                                <tr class="border-b border-gray-200 bg-gray-300 text-gray-900 uppercase">
                                    <th class="px-1 py-3 text-center">
                                        <input type="checkbox" name="ids_all" id="status-boxes-all" value="all" />
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_HEAD_STATUS') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_HEAD_TITLE') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_HEAD_PUBLICATIONS') }}
                                    </th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-center text-xs leading-4 font-medium tracking-wider">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORIES_HEAD_ID') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($categories as $category)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-6 py-4 text-center w-12">
                                            <input type="checkbox" name="ids[]" class="status-box" value="{{ $category->id }}" />
                                        </td>
                                        <td class="px-6 py-4 text-center w-12">
                                            <div class="flex justify-center">
                                                @if($category->state)
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
                                                <a href="{{ route('flippingbook.admin.categories.edit', $category->id) }}"
                                                   class="text-blue-700 hover:text-blue-900">{{ $category->title }}</a>
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-12">
                                            <div class="text-sm leading-5 text-gray-900">
                                                @if($category->publications_count > 0)
                                                    <a href="{{ route('flippingbook.admin.publications.index', ['filters[category]'=>$category->id]) }}"
                                                    class="w-10 px-2 py-2 border-2 border-gray-300 inline-block">
                                                        {{ $category->publications_count }}
                                                    </a>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 text-center w-12">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ $category->id }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <input type="hidden" name="task" id="flippingbook_task" value="" />
                        </form>

                        <div class="sm:pl-3">
                            {{ $categories->links() }}
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
