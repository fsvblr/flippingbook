<a href="{{ config('flippingbook.link_to_site_home_page', '/') }}" class="mb-5">
    <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </span>
        <span class="relative inline-flex items-center justify-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white
            border border-gray-300 leading-5 w-[110px]">
            {{ __('flippingbook::flippingbook.FLIPPINGBOOK_SITE_MENU_ITEM_HOME_PAGE') }}
        </span>
    </span>
</a>
