@extends('layouts.app')

@section('title', __('activity_logs'))
@section('header-title', __('activity_logs'))

@section('content')

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
    </div>

    {{-- Activities Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th>{{ __('user_name') }}</th>
                    <th>{{ __('merchant') }}</th>
                    <th>{{ __('branch') }}</th>
                    <th>{{ __('role') }}</th>
                    <th>{{ __('action') }}</th>
                    <th>{{ __('date') }}</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $activity)
                    <tr>
                        <td>{{ $activity->user->getFullName() }}</td>
                        <td>{{ $activity->merchant->name ?? "-" }}</td>
                        <td>{{ $activity->branch->name ?? "-" }}</td>
                        <td>{{ App\Enum\Constants::ROLES[$activity->user->role] }}</td>
                        <td>{{ $activity->action }}</td>
                        <td>{{ Carbon\Carbon::parse($activity->created_at)->setTimezone(getTimezone())->format('m/d/Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr id="no-record">
                        <td colspan="6" class="text-center">{{ __('record_not_found') }}</td>
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
