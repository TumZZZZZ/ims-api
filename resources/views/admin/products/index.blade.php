@extends('layouts.app')

@section('title', __('products'))
@section('header-title', __('products'))

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
                onclick="window.location.href='/admin/products/create'">
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
                    <th>@lang('barcode')</th>
                    <th>@lang('name')</th>
                    <th>@lang('price')</th>
                    <th>@lang('cost')</th>
                    <th>@lang('category')</th>
                    <th>@lang('in_stock')</th>
                    <th>@lang('threshold')</th>
                    <th>@lang('actions')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $product)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$product->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $product->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($product->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $product->barcode }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ amountFormat(convertCentsToAmounts($product->assign->price), getCurrencyCode()) }}</td>
                        <td>{{ amountFormat(convertCentsToAmounts($product->assign->cost), getCurrencyCode()) }}</td>
                        <td>{{ $product->categories->pluck('name')->implode(',') }}</td>
                        <td>{{ $product->assign->quantity }}</td>
                        <td>{{ $product->assign->threshold }}</td>
                        <td class="text-center">
                            <button class="btn"
                                onclick="openDialog('admin/products/delete', '{{ $product->id }}', '{{ $product->name }}', '{{ __('delete') }}')"
                                style="background: #F44336;">{{ __('delete') }}
                            </button>
                            <button class="btn"
                                onclick="window.location.href='/admin/products/edit/{{ $product->id }}'"
                                style="background: #666666;">{{ __('edit') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">{{ __('record_not_found') }}</td>
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
