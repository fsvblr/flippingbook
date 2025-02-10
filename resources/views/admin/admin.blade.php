<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
        {{ Vite::useBuildDirectory('vendor/flippingbook/build')
                ->withEntryPoints(['resources/css/flippingbook.css']) }}
        @stack('css')
    </head>
    <body class="flippingbook-admin">
        <div>
            <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
                <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

                <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-900 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
                    <div class="flex items-center justify-center mt-8">
                        <div class="flex items-center">
                            <img class="h-12 w-12" src="/vendor/flippingbook/icons/icon-book.svg" alt="Open book"/>
                        </div>
                    </div>

                    <x-flippingbook::admin.menu />
                </div>

                <div class="flex-1 flex flex-col overflow-hidden">
                    <header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-indigo-600">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-end text-gray-700">
                            <div class="sm:text-3xl font-medium uppercase">
                                {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_COMPONENT_TITLE') }}
                            </div>
                            <div class="ml-2 max-[380px]:hidden">
                                ({{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_COMPONENT_VERSION') . config('flippingbook.version') }})
                            </div>
                        </div>

                        <div class="flex items-center">
                            <a href="{{ route('flippingbook.site.categories.index') }}" class="h-8 w-8 mr-6"
                                target="_blank" rel="noopener noreferrer">
                                <img class="h-full w-full object-cover" alt="View site"
                                     src="/vendor/flippingbook/icons/icon-external-link.svg">
                            </a>
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = ! dropdownOpen"
                                        class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                                    <img class="h-full w-full object-cover"
                                         src="/vendor/flippingbook/icons/icon-user.svg"
                                         alt="Admin avatar">
                                </button>

                                <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"
                                     style="display: none;"></div>

                                <div x-show="dropdownOpen"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
                                     style="display: none;">
                                    <a href="{{ route('flippingbook.admin.logout') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                                        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MENU_LOGOUT') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                        @yield('content')
                    </main>

                    <div class="flex flex-col min-[400px]:flex-row justify-center items-center py-4 px-6">
                        <span>© {{ date('Y') }} Copyright by&nbsp;</span>
                        <a href="{{ config('flippingbook.author_website') }}" class="text-blue-700 hover:text-blue-900"
                           target="_blank" rel="noopener noreferrer">
                            {{ config('flippingbook.author_name') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{ Vite::useBuildDirectory('vendor/flippingbook/build')
                ->withEntryPoints(['resources/js/flippingbook-admin.js']) }}
        @stack('scripts')
    </body>
</html>
