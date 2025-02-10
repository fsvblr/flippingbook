@props(['errors'=>[]])

<div role="alert">
    <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
        {{ __('flippingbook::flippingbook.FLIPPINGBOOK_ERRORS_TEXT_ERROR') }}
    </div>
    <ul class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
        @foreach($errors as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
