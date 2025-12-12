@extends('layouts.app')

@section('title', __('categories'))
@section('header-title', __('categories'))

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
            onclick="window.location.href='/admin/categories/create'">
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
                    <th>@lang('name')</th>
                    <th>@lang('products')</th>
                    <th>@lang('actions')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $category)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$category->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $category->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($category->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->getProducts()->isNotEmpty() ? $category->getProducts()->count() : '-' }}</td>
                        <td class="text-center">
                            @if ($category->getProducts()->isEmpty())
                                <button class="btn"
                                    onclick="openDialog('admin/categories/delete', '{{ $category->id }}', '{{ $category->name }}', '{{ __('delete') }}')"
                                    style="background: #F44336;">{{ __('delete') }}
                                </button>
                            @endif
                            <button class="btn"
                                onclick="window.location.href='/admin/categories/edit/{{ $category->id }}'"
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
