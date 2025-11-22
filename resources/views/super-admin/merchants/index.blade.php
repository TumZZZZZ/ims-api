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
            <button class="btn" style="background: #4CAF50;">+ @lang('create')</button>
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
            <tbody id="merchant-body">
                @foreach ($data as $merchant)
                    <tr class="store-row">
                        <td style="display:flex; align-items:center;">
                            <div style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                @if ($merchant->image_url)
                                    <img src="{{ $merchant->image_url }}" style="width:100%; height:100%; object-fit:contain;">
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
                        <td><span style="color: #{{ $merchant->active ? '4CAF50' : 'F44336' }};">{{ $merchant->active ? __('activate') : __('suspend') }}</span></td>
                        <td>
                            <button class="btn" onclick="openDialog()" style="background:#{{ !$merchant->active ? '4CAF50' : 'F44336' }};">{{ !$merchant->active ? __('activate') : __('suspend') }}</button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- ===== Modal ===== -->
    <div class="modal-bg" id="modal">
        <div class="modal-box">
            <p>Are you sure you want to continue?</p>
            <br>
            <button class="btn btn-cancel" onclick="closeDialog()">Cancel</button>
            <button class="btn btn-ok" onclick="confirmAction()">OK</button>
        </div>
    </div>

    <!-- ===== Success Toast ===== -->
    <div id="toast" class="toast">
        Product saved successfully.
    </div>

    <!-- JS for search -->
    <script>
        function openDialog() {
            document.getElementById("modal").style.display = "flex";
        }

        function closeDialog() {
            document.getElementById("modal").style.display = "none";
        }

        function confirmAction() {
            closeDialog();

            const toast = document.getElementById("toast");
            toast.style.display = "block";

            setTimeout(() => {
                toast.style.display = "none";
            }, 5000);
        }
    </script>

@endsection
