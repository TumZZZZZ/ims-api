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
    </div>

    {{-- Branches Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>{{ __('branch') }}</th>
                    <th>{{ __('merchant') }}</th>
                    <th>{{ __('currency') }}</th>
                    <th>{{ __('address') }}</th>
                    <th>{{ __('available') }}</th>
                </tr>
            </thead>

            <tbody>
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
                        <td>{{ $branch->merchant->name }}</td>
                        <td>{{ getCurrencyNameByCode($branch->currency_code) }}</td>
                        <td>{{ $branch->location }}</td>
                        <td><span style="color: #{{ $branch->active ? '4CAF50' : 'F44336' }};">{{ $branch->active ? __('open') : __('closed') }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">{{ __('record_not_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @include('layouts.pagination')

    @push('scripts')
        <script src="{{ asset('js/search.js') }}"></script>
    @endpush

@endsection
