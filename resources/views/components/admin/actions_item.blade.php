@props(['buttons'=>[], 'routelist'=>'publications', 'item'=>null])

<div class="admin-item-actions" id="admin-item-actions">

    @if(in_array('apply', $buttons))
        <a href='{{ !empty($item->id) ? route("flippingbook.admin.$routelist.update", $item->id) : route("flippingbook.admin.$routelist.store") }}'
           id="action_apply" data-task="apply" data-method="{{ !empty($item->id) ? 'PUT' : 'POST' }}"
           class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_APPLY') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_APPLY') }}</span>
        </a>
    @endif

    @if(in_array('save', $buttons))
        <a href='{{ !empty($item->id) ? route("flippingbook.admin.$routelist.update", $item->id) : route("flippingbook.admin.$routelist.store") }}'
           id="action_save" data-task="save" data-method="{{ !empty($item->id) ? 'PUT' : 'POST' }}"
           class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_SAVE') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_SAVE') }}</span>
        </a>
    @endif

    @if(in_array('cancel', $buttons))
        <a href='{{ route("flippingbook.admin.$routelist.index") }}' id="action_cancel"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                 title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_CANCEL') }}"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_CANCEL') }}</span>
        </a>
    @endif

    @if(in_array('preview', $buttons))
        @if(!empty($item->id) && !empty($item->preview))
            <a href="{{ route('flippingbook.site.publications.show', $item->id) }}" id="action_preview"
               target="_blank" rel="noopener noreferrer"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                     title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_PREVIEW') }}"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_PREVIEW') }}</span>
            </a>
        @endif
    @endif

</div>

@push('scripts')
    <script>
        (function() {
            let actions = document.querySelectorAll('#admin-item-actions a');
            actions.forEach(function (action) {
                action.addEventListener('click', function(event) {
                    event.preventDefault();
                    let task = this.getAttribute('data-task'),
                        href = this.getAttribute('href'),
                        method = this.getAttribute('data-method');
                    if (!task) {
                        if (this.hasAttribute('target')) {
                            window.open(href, this.getAttribute('target'));
                        } else {
                            window.location.href = href;
                        }
                    } else {
                        let form = document.getElementById('flippingbook_item_form'),
                            methodInput = document.getElementById('flippingbook_form_method'),
                            taskInput = document.getElementById('flippingbook_task');
                        if (form) {
                            form.setAttribute('action', href);
                            if (methodInput) {
                                methodInput.value = method;
                            }
                            if (taskInput) {
                                taskInput.value = task;
                            }
                            form.submit();
                        }
                    }
                });
            });
        })();
    </script>
@endpush
