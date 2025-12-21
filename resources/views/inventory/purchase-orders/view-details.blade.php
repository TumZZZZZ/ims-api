@extends('layouts.app')

@section('title', __('view_details').' '.__('purchase_order'))
@section('header-title', __('view_details').' '.__('purchase_order'))

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

    <form action="{{ route('inventory.purchase-order.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: flex;">

            <div style="width: 50%; padding-right: 25px;">
                <div class="text-center">
                    <h2>@lang('purchase_order')</h2>
                </div>
                <div class="row">
                    <div class="col-right">
                        <div style="display:flex;">
                            <div style="width:50%; padding-right:20px;">
                                <label>@lang('buyer')</label>
                                <input type="text" value="{{ $data->branch->name }}" readonly>
                            </div>
                            <div style="width:50%;">
                                <label>@lang('po_number')</label>
                                <input type="text" value="{{ $data->order_number }}" readonly>
                            </div>
                        </div>

                        <div style="display:flex;">
                            <div style="width:50%; padding-right:20px;">
                                <label>@lang('address')</label>
                                <textarea rows="3" readonly>{{ $data->branch->location }}</textarea>
                            </div>
                            <div style="width:50%;">
                                <label>@lang('order_date')</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($data->requested_date)->setTimezone(getTimezone())->format('m/d/Y h:i A') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="width: 50%;">
                <div class="text-center">
                    <h2>@lang('supplier_details')</h2>
                </div>

                <div class="row">
                    <div class="col-right">
                        <div style="display: flex">
                            <div style="width: 50%; padding-right: 25px;">
                                <label>@lang('supplier')<span>*</span></label>
                                <input type="text" value="{{ $data->supplier->name }}" readonly>
                            </div>
                            <div style="width: 50%;">
                                <label>@lang('address')</label>
                                <textarea id="supplierAddress" rows="2" readonly>{{ $data->supplier->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <th>@lang('unit_cost') ({{ getCurrencySymbolByCurrencyCode(getCurrencyCode()) }})</th>
                            <th>@lang('total') ({{ getCurrencySymbolByCurrencyCode(getCurrencyCode()) }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->purchaseOrderDetails() as $index => $item)
                            <tr>
                                <td>
                                    {{ $item['product']->name }}
                                </td>
                                <td>{{ $item['product']->sku }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ convertCentsToAmounts($item['unit_cost']) }}</td>
                                <td>{{ convertCentsToAmounts($item['total_cost']) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                            <td><h3>@lang('total_all_product')</h3></td>
                            <td><h3>{{ convertCentsToAmounts($data->total_cost) }}</h3></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="text-center">
            <h2>@lang('payment_&_shippping_terms')</h2>
        </div>
        <div class="row">
            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 35%; padding-right: 20px;">
                        <label>@lang('payment_terms')<span>*</span></label>
                        <input type="text" value="{{ $data->payment_term }}" readonly>
                    </div>
                    <div style="width: 35%; padding-right:20px;">
                        <label>@lang('shipping_carrier')<span>*</span></label>
                        <input type="text" value="{{ $data->shipping_carrier }}" readonly>
                    </div>
                    <div style="width: 30%;">
                        <label>@lang('shipping_fee') ({{ getCurrencySymbolByCurrencyCode(getCurrencyCode()) }})</label>
                        <input type="number" value="{{ convertCentsToAmounts($data->shipping_fee) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h2>@lang('total_summary')</h2>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('subtotal')</h2>
            <h2 id="subtotal">{{ amountFormat(convertCentsToAmounts($data->total_cost), getCurrencyCode()) }}</h2>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('shipping_fee')</h2>
            <h2 id="shippingFee">{{ amountFormat(convertCentsToAmounts($data->shipping_fee), getCurrencyCode()) }}</h2>
        </div>
        <hr>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('grand_total')</h2>
            <h2 id="grandTotal">{{ amountFormat(convertCentsToAmounts($data->total_cost + $data->shipping_fee), getCurrencyCode()) }}</h2>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button
                type="submit"
                name="action"
                value="close"
                class="submit-btn"
                style="background: #aca66d;"
            >
                @lang('close')
            </button>

            <button
                type="submit"
                name="action"
                value="reject"
                class="submit-btn"
                style="background: #dc3545"
            >
                @lang('reject')
            </button>
        </div>

    </form>

    @include('modal')

@endsection
