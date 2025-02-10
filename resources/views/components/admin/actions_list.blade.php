@props(['buttons'=>[], 'routelist'=>'publications'])

<div class="admin-list-actions" id="admin-list-actions">

    @if(in_array('add', $buttons))
        <a href='{{ route("flippingbook.admin.$routelist.create") }}' id="action_add"
           class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_ADD') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_ADD') }}</span>
        </a>
    @endif

    @if(in_array('delete', $buttons))
        <a href='{{ route("flippingbook.admin.$routelist.task") }}' id="action_delete" data-task="delete"
           class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_DELETE') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_DELETE') }}</span>
        </a>
    @endif

    @if(in_array('publish', $buttons))
        <a href='{{ route("flippingbook.admin.$routelist.task") }}' id="action_publish" data-task="publish"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_PUBLISH') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_PUBLISH') }}</span>
        </a>
    @endif

    @if(in_array('unpublish', $buttons))
        <a href='{{ route("flippingbook.admin.$routelist.task") }}' id="action_unpublish" data-task="unpublish"
           class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1.5 pl-4 sm:pl-2 pr-4 mr-2 min-[340px]:mr-3 rounded inline-flex items-center"
           title="{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_UNPUBLISH') }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
            </svg>
            <span class="ml-1 hidden sm:inline">{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_UNPUBLISH') }}</span>
        </a>
    @endif

</div>

@push('scripts')
    <script>
        (function() {
            let actions = document.querySelectorAll('#admin-list-actions a');
            actions.forEach(function (action) {
                action.addEventListener('click', function(event) {
                    event.preventDefault();
                    let task = this.getAttribute('data-task'),
                        href = this.getAttribute('href');
                    if (!task) {
                        window.location.href = href;
                    } else {
                        let form = document.getElementById('flippingbook_list_form'),
                            oneSelected = false;
                        if (form) {
                            let statusBoxes = form.querySelectorAll('.status-box');
                            statusBoxes.forEach(function(box) {
                                if (box.checked) {
                                    oneSelected = true;
                                }
                            });
                        }
                        if (!oneSelected) {
                            alert('{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_ALERT_NO_SELECTED') }}');
                            return false;
                        }

                        let taskInput = document.getElementById('flippingbook_task');
                        if (taskInput) {
                            let taskForm = taskInput.closest('form');
                            taskForm.setAttribute('action', href);
                            taskInput.value = task;
                            if (task === 'delete') {
                                if (confirm('{{ __('flippingbook::flippingbook.FLIPPINGBOOK_ADMIN_ACTIONS_ALERT_DELETION_CONFIRMATION') }}')) {
                                    taskForm.submit();
                                } else {
                                    return false;
                                }
                            } else {
                                taskForm.submit();
                            }
                        }
                    }
                });
            });
        })();
    </script>
@endpush
