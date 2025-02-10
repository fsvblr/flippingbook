@props(['categories'=>[], 'selected'=>null])

@if(count($categories) > 1)
    <div class="site-list-filters site-filter-categories flex mt-8 mb-4">
        <form action="{{ route("flippingbook.site.publications.index") }}" method="POST"
              name="flippingbook_filter_categories_form" id="flippingbook_filter_categories_form" class="flex">
            @csrf

            <select name="filters[category]" id="flippingbook_filter_categories_select"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block ml-3 px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-0">
                <option value="0" {{ !$selected ? ' selected' : '' }} >
                    {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_FILTER_CATEGORIES_OPTION_ALL') }}
                </option>
                @if(!empty($categories))
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selected == $category->id ? ' selected' : '' }} >
                            {{ $category->title }}
                        </option>
                    @endforeach
                @endif
            </select>
        </form>
    </div>

    @push('scripts')
        <script>
            (function() {
                let form = document.getElementById('flippingbook_filter_categories_form'),
                    selectEl = document.getElementById('flippingbook_filter_categories_select');
                selectEl.addEventListener('change', function() {
                    form.submit();
                });
            })();
        </script>
    @endpush
@endif
