@props(['messages'=>[]])

@isset($messages['success'])
    @foreach($messages['success'] as $message)
        <div role="alert" class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-4 w-4 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div class="font-bold">{{ $message }}</div>
            </div>
        </div>
    @endforeach
@endisset

@isset($messages['warning'])
    @foreach($messages['warning'] as $message)
        <div role="alert" class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4">
            <p class="font-bold">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_MESSAGE_WARNING_TEXT_WARNING') }}</p>
            <p>{{ $message }}</p>
        </div>
    @endforeach
@endisset
