@extends('layouts.app')

@section('title', __('create_purchase_order'))
@section('header-title', __('create_purchase_order'))

@push('styles')
    <style>
        .activity-table tbody tr:hover {
            background-color: transparent !important;
        }

        .remove-row {
            cursor: pointer;
            color: #c0392b;
            font-weight: bold;
        }

        .remove-row:hover {
            opacity: 0.7;
        }

        .add-row-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            font-size: 14px;
            white-space: nowrap;
            min-width: 0;
            background: #666666;
        }
    </style>
@endpush

@section('content')

    <div class="action-bar">
        <button class="btn btn-gold" onclick="window.location.href='/inventory/purchase-orders/closed'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf

        <div class="text-center">
            <h2>@lang('purchase_order')</h2>
        </div>
        <div class="row">
            <div class="col-right">
                <div style="display:flex;">
                    <div style="width:50%; padding-right:20px;">
                        <label>@lang('buyer')</label>
                        <input type="text" name="buyer" required>
                    </div>
                    <div style="width:50%;">
                        <label>@lang('po_number')</label>
                        <input type="text" name="po_number" required>
                    </div>
                </div>

                <div style="display:flex;">
                    <div style="width:50%; padding-right:20px;">
                        <label>@lang('address')</label>
                        <textarea name="address" rows="3"></textarea>
                    </div>
                    <div style="width:50%;">
                        <label>@lang('order_date')</label>
                        <input type="datetime-local" name="order_date">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h2>@lang('supplier_details')</h2>
        </div>

        <div class="row">
            <div class="col-right">
                <label>@lang('supplier')</label>
                <input type="text" name="supplier">

                <label>@lang('address')</label>
                <textarea name="supplier_address" rows="2"></textarea>
            </div>
        </div>

        <div class="text-center">
            <h2>@lang('order_details')</h2>
        </div>

        <div class="row">
            <div class="col-right">

                <table class="activity-table">
                    <thead class="table-header">
                        <tr>
                            <th>@lang('product_name')</th>
                            <th>@lang('sku')</th>
                            <th>@lang('quantity')</th>
                            <th>@lang('unit_cost')</th>
                            <th>@lang('total')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>
                                    {{ $product->name }}
                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>
                                    <input type="number"
                                        name="items[{{ $index }}][quantity]"
                                        class="qty"
                                        min="0"
                                        step="1">
                                </td>
                                <td>
                                    <input type="number"
                                        name="items[{{ $index }}][unit_cost]"
                                        class="unit-cost"
                                        min="0"
                                        step="0.01">
                                </td>
                                <td>
                                    <!-- readonly instead of disabled -->
                                    <input type="number"
                                        name="items[{{ $index }}][total]"
                                        class="total"
                                        readonly>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="text-center">
            <h2>@lang('payment_&_shippping_terms')</h2>
        </div>

        <div class="row">
            <div class="col-right">
                <label>@lang('payment_terms')</label>
                <input type="text" name="payment_terms">

                <div style="display: flex;">
                    <div style="width: 50%; padding-right:20px;">
                        <label>@lang('shipping_method')</label>
                        <input type="text" name="shipping_method">
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('shipping_fee')</label>
                        <input type="number" name="shipping_fee">
                    </div>
                </div>

                <div>
                    <div style="display: flex;">
                    </div>
                </div>

                <div style="display: flex; text-align: right;">
                    <button type="submit" class="submit-btn">@lang('save')</button>
                    <button type="submit" class="submit-btn">@lang('create')</button>
                </div>
            </div>
        </div>
    </form>

@endsection
