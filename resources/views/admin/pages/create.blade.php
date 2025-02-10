@extends('flippingbook::admin.admin')

@section('title',
    isset($page)
    ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_TAG_TITLE_EDIT', ['title' => $page->title])
    : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_TAG_TITLE_NEW'))

@section('content')
    <div class="flippingbook-admin-page container mx-auto px-6 py-8">

        @if($errors->any())
            <div class="page-errors mb-4">
                <x-flippingbook::admin.errors :errors="$errors->all()" />
            </div>
        @endif

        @if($messages = session()->pull('flippingbook.admin.messages'))
            <x-flippingbook::admin.messages :messages="$messages" />
        @endif

        <h1 class="text-gray-700 text-2xl sm:text-3xl font-medium">
            {{ isset($page)
                ? __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_PAGE_TITLE_EDIT', ['title' => $page->title])
                : __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_PAGE_TITLE_NEW') }}
        </h1>

        <div class="flex justify-between flex-col lg:flex-row">
            <div class="page-bar mt-8">
                <x-flippingbook::admin.actions_item :buttons="['apply','save','cancel']"
                    routelist="pages" :item="isset($page) ? $page : null" />
            </div>
        </div>

        <div class="mt-8">
            <form name="flippingbook_page" enctype="multipart/form-data" method="POST" class="mt-3" id="flippingbook_item_form"
                  action="{{ isset($page) ? route("flippingbook.admin.pages.update", $page->id) : route("flippingbook.admin.pages.store") }}">
                @csrf

                @if(isset($page))
                    <input type="hidden" name="_method" value="PUT" id="flippingbook_form_method" />
                @endif

                @if(isset($page) && !empty($page->id))
                    <div class="mt-6 mb-1.5">
                        <label for="field-id" class="mb-4 font-semibold text-gray-900 dark:text-white">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_ID_LABEL') }}
                        </label>:&nbsp;&nbsp;{{ $page->id }}
                    </div>
                    <input name="id" type="hidden" id="field-id" value="{{ $page->id }}" />
                @endif

                <div class="mt-6 mb-1.5">
                    <label for="field-title" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_TITLE_LABEL') }}
                        &nbsp;<span class="@error('title') text-red-500 @enderror">*</span>
                    </label>
                </div>
                <div>
                    <input name="title" type="text" id="field-title"
                        class="w-full md:w-3/4 h-12 border border-gray-400 rounded px-3 outline-0 @error('title') border-red-500 @enderror"
                        placeholder="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_TITLE_PLACEHOLDER') }}"
                        value="{{ request()->old('title') ?? (isset($page) ? $page->title : '') }}"
                    />
                </div>

                <div class="mt-6 mb-1.5">
                    <label for="field-publication" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_PUBLICATION_LABEL') }}
                        &nbsp;<span class="@error('publication_id') text-red-500 @enderror">*</span>
                    </label>
                </div>
                <div>
                    <select name="publication_id" id="field-publication"
                            class="w-full md:w-3/4  h-12 border border-gray-400 rounded px-3 outline-0 @error('publication_id') border-red-500 @enderror">
                        <option value="">
                            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_PUBLICATION_OPTION_SELECT') }}
                        </option>
                        @if(!empty($publications))
                            @foreach($publications as $publication)
                                <option value="{{ $publication->id }}"
                                    {{ (request()->old('publication_id') == $publication->id || (isset($page) && $page->publication_id == $publication->id)) ? ' selected ' : '' }}
                                >{{ $publication->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mt-6 mb-1.5">
                    <label for="field-image_upload" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_IMAGE_UPLOAD_LABEL') }}
                        &nbsp;<span class="@error('image') text-red-500 @enderror">*</span>
                    </label>
                    <div class="w-[100px] my-2">
                        @isset($page->image)
                            <img src="/storage/flippingbook/publications/{{ $page->publication_id }}/thumbs/{{ $page->image }}"
                                id="page-image" alt="Page image" />
                        @else
                            <img src="/vendor/flippingbook/icons/icon-noimage.svg" id="page-image"
                                 alt="Page image" class="w-[100px]" />
                        @endisset
                    </div>
                    @isset($page->image)
                        <div id="page-image-name" class="py-1 text-gray-500">{{ $page->image }}</div>
                    @endisset
                </div>
                <div>
                    <input name="image_upload" type="file" accept="image/*" id="field-image_upload"
                        class="@error('image') border-red-500 @enderror"
                        value="{{ request()->old('image_upload') ?? null }}" />
                    <input name="image" type="hidden" id="field-image"
                        value="{{ request()->old('image') ?? (isset($page) ? $page->image : '') }}" />
                    <div class="text-gray-500">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_IMAGE_UPLOAD_NOTE') }}
                    </div>
                </div>

                <div class="mt-6 mb-1.5">
                    <label for="field-ordering" class="mb-4 font-semibold text-gray-900 dark:text-white">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_ORDERING_LABEL') }}
                        &nbsp;<span class="@error('ordering') text-red-500 @enderror">*</span>
                    </label>
                </div>
                <div>
                    <input name="ordering" type="number" id="field-ordering"
                        class="w-24 h-10 border border-gray-400 rounded px-3 outline-0 @error('ordering')
                        border-red-500 @enderror"
                        value="{{ request()->old('ordering') ?? (isset($page) ? $page->ordering : 999) }}"
                    />
                    <div class="text-gray-500">
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_FIELD_ORDERING_NOTE') }} &nbsp;
                        <span id="ordering-note"></span>
                    </div>
                </div>

                <input type="hidden" name="task" id="flippingbook_task" value="" />
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function() {
            let maxOrderings = JSON.parse('{!! $maxOrderings !!}'),
                orderingNote = document.getElementById('ordering-note'),
                fieldPublication = document.getElementById('field-publication'),
                fieldImage  = document.getElementById('field-image'),
                pageImage  = document.getElementById('page-image'),
                pageImageName  = document.getElementById('page-image-name'),
                fieldOrdering  = document.getElementById('field-ordering');

            document.addEventListener('DOMContentLoaded', function() {
                setOrdering(fieldPublication.value);
            });

            fieldPublication.addEventListener('change', function() {
                setOrdering(this.value);
            });

            document.getElementById('field-image_upload').addEventListener('change', function(event) {
                let file = event.target.files[0],
                    allowedMimeTypes = JSON.parse('{!! $allowedMimeTypes !!}');
                if(!file) {
                    return false;
                }
                if(!allowedMimeTypes.includes(file.type)) {
                    alert('{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_PAGE_MESSAGE_ERROR_INVALID_FILE_FORMAT') }}');
                    this.value = '';
                    fieldImage.value = '';
                    pageImage.setAttribute('src', '/vendor/flippingbook/icons/icon-noimage.svg');
                    return false;
                }
                fieldImage.value = file.name;

                const fileReader = new FileReader();
                fileReader.readAsDataURL(file);
                fileReader.addEventListener('load', function() {
                    pageImage.setAttribute('src', this.result);
                });

                if(pageImageName) {
                    pageImageName.innerHTML = '';
                }
            });

            function setOrdering(order) {
                let maxOrder = maxOrderings[order] ? maxOrderings[order] : 0,
                    pageOrdering = {{ (isset($page) && !empty($page->ordering )) ? $page->ordering : -1 }};

                orderingNote.innerText = maxOrder;

                if(pageOrdering === -1) { //New page
                    fieldOrdering.value = parseInt(maxOrder) + 1;
                }
            }
        })();
    </script>
@endpush
