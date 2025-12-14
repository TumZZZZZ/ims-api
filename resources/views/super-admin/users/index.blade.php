@extends('layouts.app')

@section('title', __('users'))
@section('header-title', __('users'))

@section('content')

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
    </div>

    {{-- Users Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>{{ __('user_name') }}</th>
                    <th>{{ __('email') }}</th>
                    <th>{{ __('phone_number') }}</th>
                    <th>{{ __('merchant') }}</th>
                    <th>{{ __('branches') }}</th>
                    <th>{{ __('role') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $user)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$user->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $user->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($user->getFullName()) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->getFullName() }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->merchant->name }}</td>
                        <td>{{ @$user->getBranches()->count() ? $user->getBranches()->implode('name', ', ') : '-' }}</td>
                        <td>{{ App\Enum\Constants::ROLES[$user->role] }}</td>
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
