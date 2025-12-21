@extends('layouts.app')

@section('title', __('branches'))
@section('header-title', __('branches'))

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
            onclick="window.location.href='/admin/branches/create'">
                + @lang('create')
            </button>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>{{ __('name') }}</th>
                    <th>{{ __('currency') }}</th>
                    <th>{{ __('address') }}</th>
                    <th>{{ __('available') }}</th>
                    <th>{{ __('actions') }}</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $branch)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$branch->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $branch->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($branch->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $branch->name }}</td>
                        <td>{{ getCurrencyNameByCode($branch->currency_code) }}</td>
                        <td>{{ $branch->location }}</td>
                        <td><span id="status-{{ $branch->id }}" style="color: #{{ $branch->active ? '4CAF50' : 'F44336' }};">{{ $branch->active ? __('open') : __('closed') }}</span></td>
                        <td class="text-center">
                            <button id="action-btn-{{ $branch->id }}" class="btn"
                                onclick="openDialog('admin/branches', '{{ $branch->id }}', '{{ $branch->name }}', '{{ !$branch->active ? __('open') : __('close') }}')"
                                style="background:#{{ !$branch->active ? '4CAF50' : 'FFD700' }};">
                                {{ !$branch->active ? __('open') : __('close') }}
                            </button>
                            @if ($branch->id != auth()->user()->active_on)
                                <button
                                    class="btn delete-btn"
                                    data-url={{ 'admin/branches/delete' }}
                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}"
                                    data-title="{{ __('delete') }}"
                                    style="background: #F44336;">
                                    {{ __('delete') }}
                                </button>
                            @endif
                            <button class="btn"
                                onclick="window.location.href='/admin/branches/edit/{{ $branch->id }}'"
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
