@props(['filters_list'=>[], 'filters'=>[], 'data'=>[], 'routelist'=>'publications'])

<div class="admin-list-filters" id="admin-list-filters">
    <form action="{{ route("flippingbook.admin.$routelist.index") }}" method="POST"
          name="flippingbook_filters_form" id="flippingbook_filters_form" class="flex">
        @csrf

        <div class="flex flex-col items-start min-[400px]:flex-row min-[400px]:items-center min-[400px]:justify-start">
            @if(in_array('status', $filters_list))
                <select name="filters[status]" onchange="this.closest('form').submit();"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block xl:ml-3 px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-0">
                    <option value="-1" {{ (!isset($filters['status']) || $filters['status'] == -1) ? ' selected' : '' }} >
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_FILTERS_STATUS_OPTION_SELECT') }}
                    </option>
                    <option value="1" {{ (isset($filters['status']) && $filters['status'] == 1) ? ' selected' : '' }} >
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_FILTERS_STATUS_OPTION_PUBLISED') }}
                    </option>
                    <option value="0" {{ (isset($filters['status']) && $filters['status'] == 0) ? ' selected' : '' }} >
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_FILTERS_STATUS_OPTION_UNPUBLISED') }}
                    </option>
                </select>
            @endif

            @if(in_array('category', $filters_list))
                <select name="filters[category]" onchange="this.closest('form').submit();"
                        class="mt-3 min-[400px]:mt-0 ml-0 min-[400px]:ml-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-0">
                    <option value="0" {{ (!isset($filters['category']) || $filters['category'] == 0) ? ' selected' : '' }} >
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_FILTERS_CATEGORY_OPTION_SELECT') }}
                    </option>
                    @if(!empty($data['categories']))
                        @foreach($data['categories'] as $category)
                            <option value="{{ $category->id }}" {{ (isset($filters['category']) && $filters['category'] == $category->id) ? ' selected' : '' }} >
                                {{ $category->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            @endif

            @if(in_array('publication', $filters_list))
                <select name="filters[publication]" onchange="this.closest('form').submit();"
                        class="mt-3 min-[400px]:mt-0 ml-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-0">
                    <option value="0" {{ (!isset($filters['publication']) || $filters['publication'] == 0) ? ' selected' : '' }} >
                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_FILTERS_PUBLICATION_OPTION_SELECT') }}
                    </option>
                    @if(!empty($data['publications']))
                        @foreach($data['publications'] as $publication)
                            <option value="{{ $publication->id }}" {{ (isset($filters['publication']) && $filters['publication'] == $publication->id) ? ' selected' : '' }} >
                                {{ $publication->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            @endif
        </div>
    </form>
</div>
