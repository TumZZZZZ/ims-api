@extends('layouts.app')

@section('title', __('activity_logs'))
@section('header-title', __('activity_logs'))

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="{{ __('search') }}">
    </div>

    {{-- Activities Table --}}
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th class="text-center">{{ __('user_name') }}</th>
                    <th class="text-center">{{ __('merchant') }}</th>
                    <th class="text-center">{{ __('branch') }}</th>
                    <th class="text-center">{{ __('role') }}</th>
                    <th class="text-center">{{ __('action') }}</th>
                    <th class="text-center">{{ __('date') }}</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach ($data as $activity)
                    <tr class="table-body-tr">
                        <td class="text-center">{{ $activity->username }}</td>
                        <td class="text-center">{{ $activity->merchant }}</td>
                        <td class="text-center">{{ $activity->branch }}</td>
                        <td class="text-center">{{ $activity->role }}</td>
                        <td class="text-center">{{ $activity->action }}</td>
                        <td class="text-center">{{ $activity->date }}</td>
                    </tr>
                @endforeach
                @if ($data->isEmpty())
                    <tr id="no-record">
                        <td colspan="6" class="text-center">{{ __('record_not_found') }}</td>
                    </tr>
                @endif
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
