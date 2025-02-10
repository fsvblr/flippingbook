<nav class="mt-10">
    @if(config('flippingbook.link_to_admin_dashboard')))
        <a href="{{ config('flippingbook.link_to_admin_dashboard') }}"
           class="text-gray-100 flex items-center mt-1 py-1 px-6">
            <span class="mx-3">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MENU_ADMIN_DASHBOARD') }}</span>
        </a>
    @endif
    <a href="{{ route('flippingbook.admin.categories.index') }}"
       class="text-gray-100 flex items-center mt-1 py-1 px-6{{ request()->routeIs('flippingbook.admin.categories.index') ? ' active font-bold' : '' }}">
        <span class="mx-3">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MENU_CATEGORIES') }}</span>
    </a>
    <a href="{{ route('flippingbook.admin.publications.index') }}"
       class="text-gray-100 flex items-center mt-1 py-1 px-6{{ request()->routeIs('flippingbook.admin.publications.index') ? ' active font-bold' : '' }}">
        <span class="mx-3">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MENU_PUBLICATIONS') }}</span>
    </a>
    <a href="{{ route('flippingbook.admin.pages.index') }}"
       class="text-gray-100 flex items-center mt-1 py-1 px-6{{ request()->routeIs('flippingbook.admin.pages.index') ? ' active font-bold' : '' }}">
        <span class="mx-3">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MENU_PAGES') }}</span>
    </a>
</nav>
