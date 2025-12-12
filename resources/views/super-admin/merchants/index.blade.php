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
                                onclick="openDialog('super-admin/merchants', '{{ $merchant->id }}', '{{ $merchant->name }}', '{{ !$merchant->active ? __('activate') : __('suspend') }}')"
                                style="background:#{{ !$merchant->active ? '4CAF50' : 'FFD700' }};">
                                {{ !$merchant->active ? __('activate') : __('suspend') }}
                            </button>
                            <button class="btn"
                                onclick="openDialog('super-admin/merchants/delete', '{{ $merchant->id }}', '{{ $merchant->name }}', '{{ __('delete') }}')"
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
    @include('layouts.pagination')

    <!-- ===== Modal ===== -->
    @include('modal')

    @push('scripts')
        <script src="{{ asset('js/search.js') }}"></script>
        <script src="{{ asset('js/actions.js') }}"></script>
        <script>
            // Pass the translated template to JS
            window.confirmationTemplate = @json(__('confirmation_action', [
                'action' => ':action',
                'objectName' => ':object_name'
            ]));

            window.objectActionTemplate = @json(__('object_action_successfully', [
                    'objectName' => ':object_name',
                    'action' => ':action'
                ])
            );

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
        </script>
    @endpush

@endsection
