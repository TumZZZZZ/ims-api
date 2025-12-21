@extends('layouts.app')

@section('title', __('promotions'))
@section('header-title', __('promotions'))

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
            onclick="window.location.href='/admin/promotions/create'">
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
                    <th>@lang('start_date')</th>
                    <th>@lang('end_date')</th>
                    <th>@lang('status')</th>
                    <th>@lang('actions')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $promotion)
                    @php
                        $status = Carbon\Carbon::parse($promotion->end_date)->isPast()
                            ? __('expired')
                            : __('active');
                        $colorCode = Carbon\Carbon::parse($promotion->end_date)->isPast() ? 'F44336' : '4CAF50';
                        $startDate = Carbon\Carbon::parse($promotion->start_date)->setTimezone(getTimezone())->format('m/d/Y h:i A');
                        $endDate = Carbon\Carbon::parse($promotion->end_date)->setTimezone(getTimezone())->format('m/d/Y h:i A');
                    @endphp
                    <tr>
                        <td>{{ $promotion->name }}</td>
                        <td>{{ $startDate }}</td>
                        <td>{{ $endDate }}</td>
                        <td><span style="color: #{{ $colorCode }}">{{ $status }}</span></td>
                        <td class="text-center">
                            <button
                                class="btn delete-btn"
                                data-url={{ 'admin/promotions/delete' }}
                                data-id="{{ $promotion->id }}"
                                data-name="{{ $promotion->name }}"
                                data-title="{{ __('delete') }}"
                                style="background: #F44336;">
                                {{ __('delete') }}
                            </button>
                            <button class="btn"
                                onclick="window.location.href='/admin/promotions/edit/{{ $promotion->id }}'"
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
