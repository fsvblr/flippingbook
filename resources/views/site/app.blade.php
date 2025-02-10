<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        {{ Vite::useBuildDirectory('vendor/flippingbook/build')
                ->withEntryPoints(['resources/css/flippingbook.css']) }}
    </head>
    <body class="flippingbook-site">
        @yield('content')
        @stack('scripts')
    </body>
</html>
