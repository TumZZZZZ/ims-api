@extends('layouts.app')

@section('title', __('merchants'))
@section('header-title', __('merchants'))

@section('content')

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
        {{-- Buttons --}}
        <div>
            <button class="btn" style="background: #4CAF50;"
            onclick="window.location.href='/super-admin/merchants/create'">
                + @lang('create')
            </button>
        </div>
    </div>

    {{-- Merchants Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>@lang('merchant')</th>
                    <th>@lang('branch')</th>
                    <th>@lang('address')</th>
                    <th>@lang('status')</th>
                    <th class="text-center">@lang('action')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $merchant)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$merchant->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $merchant->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($merchant->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $merchant->name }}</td>
                        <td>{{ $merchant->branches->count() ? implode(', ', $merchant->branches->pluck('name')->toArray()) : '-' }}</td>
                        <td>{{ $merchant->location }}</td>
                        <td><span id="status-{{ $merchant->id }}" style="color: #{{ $merchant->active ? '4CAF50' : 'FFD700' }};">{{ $merchant->active ? __('activate') : __('suspend') }}</span></td>
                        <td class="text-center">
                            <button id="action-btn-{{ $merchant->id }}" class="btn"
                                onclick="openDialog('{{ $merchant->id }}', '{{ $merchant->name }}', '{{ !$merchant->active ? __('activate') : __('suspend') }}')"
                                style="background:#{{ !$merchant->active ? '4CAF50' : 'FFD700' }};">
                                {{ !$merchant->active ? __('activate') : __('suspend') }}
                            </button>
                            <button class="btn"
                                onclick="openDialog('{{ $merchant->id }}', '{{ $merchant->name }}', '{{ __('delete') }}')"
                                style="background: #F44336;">{{ __('delete') }}
                            </button>
                            <button class="btn"
                                onclick="window.location.href='/super-admin/merchants/update/{{ $merchant->id }}'"
                                style="background: #666666;">{{ __('edit') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('record_not_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        @if ($data->isNotEmpty())
            <div>
                Showing {{ $data->firstItem() }}–{{ $data->lastItem() }} of {{ $data->total() }}
            </div>
        @endif
        {{ $data->links() }}
    </div>

    <!-- ===== Modal ===== -->
    @include('modal')


    @push('scripts')
        <script src="{{ asset('js/search.js') }}"></script>
        <script>

            // Check if Laravel has a success message
            @if(session('success_message'))
                const toast = document.getElementById("toast");
                toast.innerHTML = @json(session('success_message')); // safely pass the message
                toast.style.display = "block";

                // Optional: hide toast after 5 seconds
                setTimeout(() => {
                    toast.style.display = "none";
                }, 5000);
            @endif

            // Show toast from sessionStorage (after page reload)
            document.addEventListener('DOMContentLoaded', function () {
                const deleteMerchantMessage = sessionStorage.getItem('delete_merchant_message');
                if (deleteMerchantMessage) {
                    const toast = document.getElementById("toast");
                    toast.innerHTML = deleteMerchantMessage;
                    toast.style.display = "block";

                    sessionStorage.removeItem('delete_merchant_message');

                    setTimeout(() => {
                        toast.style.display = "none";
                    }, 5000);
                }
            });

            // Modal
            let currentMerchantId = '';
            let currentMerchantName = '';
            let currentAction = '';

            function openDialog(merchantId, merchantName, action) {
                currentMerchantId = merchantId;
                currentMerchantName = merchantName;
                currentAction = action;

                // Get the localized string with placeholders from Blade
                let template = @json(__('confirm_merchant_action', ['action' => ':action', 'merchantName' => ':merchantName']));

                // Replace placeholders with actual values in JS
                let confirmationMessage = template
                    .replace(':action', action.toLowerCase())
                    .replace(':merchantName', merchantName);

                document.getElementById("modal-message").innerHTML = confirmationMessage;
                document.getElementById("modal").style.display = "flex";
            }

            function closeDialog() {
                document.getElementById("modal").style.display = "none";
            }

            function confirmAction() {

                let action = currentAction.toUpperCase();

                // Handle delete action separately
                if (['DELETE'].includes(action)) {
                    const url = `/super-admin/merchants/delete/${currentMerchantId}?merchant_name=${encodeURIComponent(currentMerchantName)}`;

                    fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json()) // <-- parse JSON
                    .then(data => {
                        if (data.success) {
                            closeDialog();

                            // Store message in sessionStorage for reload
                            sessionStorage.setItem('delete_merchant_message', data.message);

                            // Reload page
                            window.location.reload();
                        } else {
                            alert('Failed to delete merchant.');
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
                    return;
                }

                // AJAX request to suspend/activate merchant
                if (['SUSPEND', 'ACTIVATE'].includes(action)) {
                    const url = `/super-admin/merchants/${currentMerchantId}/suspend-or-activate`;

                    fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            merchant_name: currentMerchantName,
                            action: currentAction,
                            active: currentAction === "Suspend" ? 0 : 1
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            closeDialog();

                            // Update columns status & action
                            const statusSpan = document.getElementById(`status-${currentMerchantId}`);
                            const actionBtn = document.getElementById(`action-btn-${currentMerchantId}`);

                            if (currentAction.toLowerCase() === "suspend") {
                                statusSpan.textContent = "Suspend";
                                statusSpan.style.color = "#FFD700";

                                actionBtn.textContent = "Activate";
                                actionBtn.style.background = "#4CAF50";
                                actionBtn.setAttribute("onclick", `openDialog('${currentMerchantId}', '${currentMerchantName}', 'activate')`);
                            } else {
                                statusSpan.textContent = "Activate";
                                statusSpan.style.color = "#4CAF50";

                                actionBtn.textContent = "Suspend";
                                actionBtn.style.background = "#FFD700";
                                actionBtn.setAttribute("onclick", `openDialog('${currentMerchantId}', '${currentMerchantName}', 'suspend')`);
                            }

                            // Display success message
                            const toast = document.getElementById("toast");
                            toast.innerHTML = `<strong>${currentMerchantName}</strong> has been ${currentAction.toLowerCase()}ed successfully.`;
                            toast.style.display = "block";

                            // Disapear after 5 seconds
                            setTimeout(() => {
                                toast.style.display = "none";
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
                }
            }
        </script>
    @endpush

@endsection
