@extends('layouts.app')

@section('title', __('ledgers'))
@section('header-title', __('ledgers'))

@section('content')

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
    </div>

    {{-- Ledgers Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>@lang('branch')</th>
                    <th>@lang('product')</th>
                    <th>@lang('quantity')</th>
                    <th>@lang('type')</th>
                    <th>@lang('reason')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $ledger)
                    <tr>
                        <td>{{ $ledger->branch->name }}</td>
                        <td>{{ $ledger->product->name }}</td>
                        <td>{{ $ledger->quantity }}</td>
                        <td>{{ App\Enum\Constants::LEDGER_TYPES[$ledger->type] }}</td>
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
    @endpush

@endsection
