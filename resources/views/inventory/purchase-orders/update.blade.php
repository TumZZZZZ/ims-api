@extends('layouts.app')

@section('title', __('update_purchase_order'))
@section('header-title', __('update_purchase_order'))

@php
    $orderDetails = $data->purchaseOrderDetails();

    // First, map all products to include ordered_qty
    $products = $products->map(function($item) use ($orderDetails) {
        $existing = $orderDetails->where('product_id', $item->id)->first();
        $item->ordered_qty = $existing ? $existing['quantity'] : 0;
        return $item;
    });

    // Then, sort: existing products first
    $products = $products->sortByDesc(function($item) {
        return $item->ordered_qty > 0 ? 1 : 0;
    })->values();

@endphp

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
        <button class="btn btn-gold" onclick="window.location.href='/inventory/purchase-orders/draft'">
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
                                <input type="hidden" name="branch_id" value="{{ $data->branch->id }}">
                                <input type="text" value="{{ $data->branch->name }}" readonly>
                            </div>
                            <div style="width:50%;">
                                <label>@lang('po_number')</label>
                                <input type="text" name="po_number" value="{{ $data->order_number }}" readonly>
                            </div>
                        </div>

                        <div style="display:flex;">
                            <div style="width:50%; padding-right:20px;">
                                <label>@lang('address')</label>
                                <textarea rows="3" readonly>{{ $data->branch->location }}</textarea>
                            </div>
                            <div style="width:50%;">
                                <label>@lang('order_date')</label>
                                <input type="hidden" name="order_date" value="{{ $data->branch->requested_date }}">
                                <input type="text" value="{{ \Carbon\Carbon::parse($data->branch->requested_date)->setTimezone(getTimezone())->format('m/d/Y h:i A') }}" readonly>
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
                                <select id="supplierSelect" name="supplier_id" required>
                                    <button>
                                        <selectedcontent></selectedcontent>
                                    </button>
                                    <option value="">
                                        <div class="custom-option">
                                            <span class="option-text">{{ __('select_supplier') }}</span>
                                        </div>
                                    </option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-address="{{ $supplier->address }}" {{ $supplier->id === $data->supplier->id ? 'selected' : '' }}>
                                            <div class="custom-option">
                                                <span class="option-text">{{ $supplier->name }}</span>
                                            </div>
                                        </option>
                                    @endforeach
                                </select>
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
                            <th>@lang('action')</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
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
                                        value="{{ $product->ordered_qty }}"
                                        class="qty"
                                        min="0"
                                        step="1">
                                </td>
                                <td>
                                    <input type="number"
                                        name="items[{{ $index }}][unit_cost]"
                                        value="{{ convertCentsToAmounts($product->assign->price) }}"
                                        class="unit-cost"
                                        min="0"
                                        step="0.01" readonly>
                                </td>
                                <td>
                                    <input type="number"
                                        name="items[{{ $index }}][total_cost]"
                                        value="{{ convertCentsToAmounts($product->assign->price * $product->ordered_qty) }}"
                                        value="0"
                                        class="total-cost"
                                        readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn removeRow" style="font-weight:bold; font-size:28px; line-height:1; background: #dc3545; padding: 10px 15px;">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                            <td><h3>@lang('total_all_product')</h3></td>
                            <input id="totalAllProduct" type="hidden" name="total_all_product" step="0.01" min="0" value="{{ convertCentsToAmounts($data->total_cost) }}">
                            <td id="allProductTotal"><h3>{{ convertCentsToAmounts($data->total_cost) }}</h3></td>
                            <td></td>
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
                        <select name="payment_term" required>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_payment_terms') }}</span>
                                </div>
                            </option>
                            @foreach ($payment_terms as $value)
                                <option value="{{ $value }}" {{ $value === $data->payment_term ? 'selected' : '' }}>
                                    <div class="custom-option">
                                        <span class="option-text">{{ $value }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 35%; padding-right:20px;">
                        <label>@lang('shipping_carrier')<span>*</span></label>
                        <select name="shipping_carrier" required>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_shipping_carrier') }}</span>
                                </div>
                            </option>
                            @foreach ($shipping_carrier as $value)
                                <option value="{{ $value }}" {{ $value === $data->shipping_carrier ? 'selected' : '' }}>
                                    <div class="custom-option">
                                        <span class="option-text">{{ $value }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 30%;">
                        <label>@lang('shipping_fee') ({{ getCurrencySymbolByCurrencyCode(getCurrencyCode()) }})</label>
                        <input id="shippingFeeInput" type="number" name="shipping_fee" value="{{ convertCentsToAmounts($data->shipping_fee) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h2>@lang('total_summary')</h2>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('subtotal')</h2>
            <input id="summarySubtotalInput" type="hidden" value="0">
            <h2 id="subtotal">{{ amountFormat(convertCentsToAmounts($data->total_cost), getCurrencyCode()) }}</h2>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('shipping_fee')</h2>
            <input id="summaryShippingFeeInput" type="hidden" value="0">
            <h2 id="shippingFee">{{ amountFormat(convertCentsToAmounts($data->shipping_fee), getCurrencyCode()) }}</h2>
        </div>
        <hr>
        <div style="display: flex; justify-content: space-between;">
            <h2>@lang('grand_total')</h2>
            <input id="summaryGrandTotalInput" type="hidden" value="0">
            <h2 id="grandTotal">{{ amountFormat(convertCentsToAmounts($data->total_cost + $data->shipping_fee), getCurrencyCode()) }}</h2>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button
                type="submit"
                name="action"
                value="save"
                class="submit-btn"
                style="background: #666666;"
            >
                @lang('save')
            </button>

            <button
                type="submit"
                name="action"
                value="send"
                class="submit-btn"
            >
                @lang('send')
            </button>
        </div>

    </form>

    @include('modal')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                function showToast(message, success = true) {
                    toast.textContent = message;
                    toast.style.color = success ? '#28a745' : '#dc3545';
                    toast.style.border = success ? '1px solid #28a745' : '1px solid #dc3545';
                    toast.style.display = 'block';
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 5000);
                }

                const tbody = document.getElementById('productTableBody');

                tbody.addEventListener('click', function(e) {
                    if(e.target && e.target.classList.contains('removeRow')) {
                        const productRows = Array.from(tbody.querySelectorAll('tr')).slice(0, -1); // exclude total row
                        if(productRows.length > 1) {
                            e.target.closest('tr').remove();
                        } else {
                        showToast("{{ __('at_least_one_row_must_remain') }}", false);
                        }
                    }
                });

                const supplierSelect = document.getElementById('supplierSelect');
                const supplierAddress = document.getElementById('supplierAddress');

                supplierSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];

                    const address = selectedOption.dataset.address || '';
                    supplierAddress.value = address;
                });

                const rows = document.querySelectorAll('tbody tr');

                function formatMoney(amount) {
                    if ("{{ getCurrencyCode() }}" === 'KHR') {
                        return Math.round(amount).toString();
                    }
                    return amount.toFixed(2);
                }

                // Total Summary Auto Fill
                const currencyCode = "{{ getCurrencyCode() }}";
                const currencySymbol = "{{ getCurrencySymbolByCurrencyCode(getCurrencyCode()) }}";

                function parseMoney(value) {
                    return parseFloat(value.replace(/,/g, '')) || 0;
                }

                function formatMoneyWithSymbol(value) {
                    let number = parseFloat(value) || 0;

                    // KHR: no decimals, symbol at end
                    if (currencyCode === 'KHR') {
                        return number.toLocaleString('en-US', {
                            maximumFractionDigits: 0
                        }) + currencySymbol;
                    }

                    // USD: 2 decimals, symbol at start
                    if (currencyCode === 'USD') {
                        return currencySymbol + number.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }

                    // fallback
                    return number.toLocaleString();
                }

                function calculateGrandTotal() {
                    const subtotalInput = document.getElementById('summarySubtotalInput');
                    const subtotal = subtotalInput ? parseFloat(subtotalInput.value) || 0 : 0;

                    const shippingFeeInput = document.getElementById('summaryShippingFeeInput');
                    const shippingFee = shippingFeeInput ? parseFloat(shippingFeeInput.value) || 0 : 0;

                    const grandTotal = subtotal + shippingFee;

                    document.getElementById('grandTotal').innerText =
                        formatMoneyWithSymbol(grandTotal);
                }

                function calculateTotals() {
                    let subtotal = 0;

                    rows.forEach(row => {
                        const qtyInput = row.querySelector('.qty');
                        const unitCostInput = row.querySelector('.unit-cost');
                        const totalCostInput = row.querySelector('.total-cost');
                        const totalAllProductInput = document.getElementById('totalAllProduct');

                        // Skip summary row
                        if (!qtyInput || !unitCostInput || !totalCostInput) return;

                        const qty = parseFloat(qtyInput.value) || 0;
                        const unitCost = parseFloat(unitCostInput.value) || 0;

                        const rowTotal = qty * unitCost;
                        totalCostInput.value = formatMoney(rowTotal);

                        subtotal += rowTotal;
                    });

                    totalAllProduct.value = subtotal;

                    document.getElementById('allProductTotal').innerHTML =
                        `<h3>${formatMoney(subtotal)}</h3>`;

                    document.getElementById('summarySubtotalInput').value = subtotal;
                    document.getElementById('subtotal').innerText = formatMoneyWithSymbol(subtotal);
                    calculateGrandTotal();
                }

                // Listen to quantity changes
                document.querySelectorAll('.qty').forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });

                const shippingFeeInput = document.getElementById('shippingFeeInput');
                shippingFeeInput.addEventListener('input', function (e) {
                    const value = e.target.value;
                    document.getElementById('summaryShippingFeeInput').value = value;
                    document.getElementById('shippingFee').innerText = formatMoneyWithSymbol(value);
                    calculateGrandTotal();
                });
            });
        </script>
    @endpush

@endsection
