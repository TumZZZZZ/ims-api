@extends('layouts.app')

@section('title', 'Users')
@section('header-title', 'Users')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="{{ __('search') }}"
            style="padding:10px 15px; border-radius:8px; border:1px solid #ccc; width: 250px; margin-bottom: 10px;">
    </div>

    <!-- Stores Table -->
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
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
            <tbody id="table-body">
                @foreach ($data as $user)
                    <tr class="table-body-tr">
                        <td style="display:flex; align-items:center;">
                            <div
                                style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                @if ($user->image_url)
                                    <div style="width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                        <img src="{{ $user->image_url }}" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                @else
                                    <div
                                        style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                        {{ substr($user->full_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->user_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->merchant }}</td>
                        <td>{{ $user->branches }}</td>
                        <td>{{ $user->role }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <script>
        // Pass translation to JS
        window.translations = {
            recordNotFound: "{{ __('record_not_found') }}"
        };
    </script>
    <script src="{{ asset('js/search.js') }}"></script>

@endsection
