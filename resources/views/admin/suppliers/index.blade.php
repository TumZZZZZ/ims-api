@extends('layouts.app')

@section('title', __('suppliers'))
@section('header-title', __('suppliers'))

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
            onclick="window.location.href='/inventory/suppliers/create'">
                + @lang('create')
            </button>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th>@lang('name')</th>
                    <th>@lang('email')</th>
                    <th>@lang('phone_number')</th>
                    <th>@lang('address')</th>
                    <th>@lang('actions')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone_number }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td class="text-center">
                            <button class="btn"
                                onclick="openDialog('inventory/suppliers/delete', '{{ $supplier->id }}', '{{ $supplier->name }}', '{{ __('delete') }}')"
                                style="background: #F44336;">{{ __('delete') }}
                            </button>
                            <button class="btn"
                                onclick="window.location.href='/inventory/suppliers/edit/{{ $supplier->id }}'"
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
