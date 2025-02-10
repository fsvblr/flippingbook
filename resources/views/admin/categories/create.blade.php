@extends('flippingbook::admin.admin')

@section('title',
    isset($category)
    ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_TAG_TITLE_EDIT', ['title' => $category->title])
    : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_TAG_TITLE_NEW'))

@section('content')
    <div class="flippingbook-admin-category container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()" />
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages" />
        @endif

        <h1 class="text-gray-700 text-2xl sm:text-3xl font-medium">
            {{ isset($category)
                ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_PAGE_TITLE_EDIT', ['title' => $category->title])
                : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_PAGE_TITLE_NEW') }}
        </h1>

        <div class="flex justify-between flex-col lg:flex-row">
            <div class="page-bar mt-8">
                <x-flippingbook::admin.actions_item :buttons="['apply','save','cancel']"
                    routelist="categories" :item="isset($category) ? $category : null" />
            </div>
        </div>

        <div class="mt-8">
            <form name="flippingbook_category" method="POST" class="mt-3" id="flippingbook_item_form"
                  action="{{ isset($category) ? route("flippingbook.admin.categories.update", $category->id) : route("flippingbook.admin.categories.store") }}">
                @csrf

                @if(isset($category))
                    <input type="hidden" name="_method" value="PUT" id="flippingbook_form_method" />
                @endif

                @if(isset($category) && !empty($category->id))
                    <div class="mt-6 mb-1.5">
                        <label for="field-id" class="mb-4 font-semibold text-gray-900 dark:text-white">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_ID_LABEL') }}
                        </label>:&nbsp;&nbsp;{{ $category->id }}
                    </div>
                    <input name="id" type="hidden" id="field-id" value="{{ $category->id }}" />
                @endif

                <div class="mt-6 mb-1.5">
                    <label for="field-title" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_TITLE_LABEL') }}
                        &nbsp;<span class="@error('title') text-red-500 @enderror">*</span>
                    </label>
                </div>
                <div>
                    <input name="title" type="text" id="field-title"
                        class="w-full md:w-3/4 h-12 border border-gray-400 rounded px-3 outline-0 @error('title') border-red-500 @enderror"
                        placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_TITLE_PLACEHOLDER') }}"
                        value="{{ request()->old('title') ?? (isset($category) ? $category->title : '') }}"
                    />
                </div>

                <div class="mt-6 mb-1.5">
                    <label for="" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_STATE_LABEL') }}
                    </label>
                </div>
                <ul class="w-6 items-center rounded-lg flex">
                    <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3 bg-teal-500 rounded-l-lg">
                            <input id="radio-state-yes" type="radio" value="1" name="state"
                                   {{ (request()->old('state') == 1 || (isset($category) && $category->state == 1)) ? ' checked ' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-state-yes" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_STATE_OPTION_YES') }}
                            </label>
                        </div>
                    </li>
                    <li class="border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3 bg-red-500 rounded-r-lg">
                            <input id="radio-state-no" type="radio" value="0" name="state"
                                   {{ ((!is_null(request()->old('state')) && request()->old('state') == 0)
                                        || (!isset($category) && is_null(request()->old('state')))
                                            || (isset($category) && $category->state == 0)) ? ' checked ' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                            <label for="radio-state-no" class="py-2.5 pl-3 pr-6 text-white font-bold cursor-pointer rounded-r-lg">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_STATE_OPTION_NO') }}
                            </label>
                        </div>
                    </li>
                </ul>

                <div class="mt-6 mb-1.5">
                    <label for="field-description" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_DESCRIPTION_LABEL') }}
                    </label>
                </div>
                <div class="w-full md:w-3/4 h-64 border border-gray-400 rounded py-1 px-1">
                    <input type="hidden" name="description" id="field-description"
                        value="{{ request()->old('description') ?? (isset($category) ? $category->description : '') }}"
                    />
                    <trix-editor input="field-description" class="trix-content h-5/6 bg-gray-100"
                        placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_CATEGORY_FIELD_DESCRIPTION_PLACEHOLDER') }}"></trix-editor>
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
        })();
    </script>
@endpush
