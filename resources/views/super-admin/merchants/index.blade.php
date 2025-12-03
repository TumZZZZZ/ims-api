@extends('layouts.app')

@section('title', __('merchants'))
@section('header-title', __('merchants'))

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="{{ __('search') }}">
        <!-- Buttons -->
        <div>
            <button class="btn" style="background: #4CAF50;"
            onclick="window.location.href='/super-admin/merchants/create'">
                + @lang('create')
            </button>
        </div>
    </div>

    <!-- Merchants Table -->
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th></th>
                    <th>@lang('merchant')</th>
                    <th>@lang('branch')</th>
                    <th>@lang('address')</th>
                    <th>@lang('status')</th>
                    <th>@lang('action')</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($data as $merchant)
                    <tr class="table-body-tr">
                        <td style="display:flex; align-items:center;">
                            <div style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                @if ($merchant->image_url)
                                    <div style="width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                        <img src="{{ $merchant->image_url }}" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                @else
                                    <div style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                        {{ substr($merchant->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $merchant->name }}</td>
                        <td>{{ $merchant->branches }}</td>
                        <td>{{ $merchant->address }}</td>
                        <td><span id="status-{{ $merchant->id }}" style="color: #{{ $merchant->active ? '4CAF50' : 'F44336' }};">{{ $merchant->active ? __('activate') : __('suspend') }}</span></td>
                        <td>
                            <button id="action-btn-{{ $merchant->id }}" class="btn"
                                onclick="openDialog('{{ $merchant->id }}', '{{ $merchant->name }}', '{{ !$merchant->active ? __('activate') : __('suspend') }}')"
                                style="background:#{{ !$merchant->active ? '4CAF50' : 'F44336' }};">
                                {{ !$merchant->active ? __('activate') : __('suspend') }}
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- ===== Modal ===== -->
    @include('modal')

    <script>
        // Pass translation to JS
        window.translations = {
            recordNotFound: "{{ __('record_not_found') }}"
        };

        // Modal
        let currentMerchantId = '';
        let currentMerchantName = '';
        let currentAction = '';

        function openDialog(merchantId, merchantName, action) {
            currentMerchantId = merchantId;
            currentMerchantName = merchantName;
            currentAction = action;
            document.getElementById("modal-message").textContent = `Are you sure you want to ${action.toLowerCase()} ${merchantName}?`;
            document.getElementById("modal").style.display = "flex";
        }

        function closeDialog() {
            document.getElementById("modal").style.display = "none";
        }

        function confirmAction() {

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
                        statusSpan.style.color = "#F44336";

                        actionBtn.textContent = "Activate";
                        actionBtn.style.background = "#4CAF50";
                        actionBtn.setAttribute("onclick", `openDialog('${currentMerchantId}', '${currentMerchantName}', 'activate')`);
                    } else {
                        statusSpan.textContent = "Activate";
                        statusSpan.style.color = "#4CAF50";

                        actionBtn.textContent = "Suspend";
                        actionBtn.style.background = "#F44336";
                        actionBtn.setAttribute("onclick", `openDialog('${currentMerchantId}', '${currentMerchantName}', 'suspend')`);
                    }

                    // Display success message
                    const toast = document.getElementById("toast");
                    toast.textContent = `${currentMerchantName} has been ${currentAction.toLowerCase()}ed successfully.`;
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
    </script>
    <script src="{{ asset('js/search.js') }}"></script>

@endsection
