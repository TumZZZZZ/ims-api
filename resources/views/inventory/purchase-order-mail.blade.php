<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Purchase Order</title>
</head>

<body style="margin:0;padding:0;background:#fffaf3;font-family:Arial,sans-serif;color:#3c2a21;">

    <table width="100%">
        <tr>
            <td align="center">

                <!-- CONTAINER -->
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="background:#bba27e;padding:30px;border-radius:10px;">

                    <!-- HEADER -->
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td valign="top">
                                        <h2 style="margin:0;color:#a47e3c;">{{ $purchase_order->user->merchant->name }}</h2>
                                        <p style="margin:5px 0;">{{ $purchase_order->user->merchant->location }}</p>
                                        <p style="margin:5px 0;">Email: {{ $purchase_order->user->email }}</p>
                                        <p style="margin:5px 0;">Phone: {{ formatPhoneKH($purchase_order->user->phone_number) }}</p>
                                    </td>
                                    <td valign="top" align="right">
                                        <h3 style="margin:0;">Purchase Order</h3>
                                        <p>PO Number: <strong>#{{ $purchase_order->order_number }}</strong></p>
                                        <p>Date: <strong>{{ \Carbon\Carbon::parse($purchase_order->requested_date)->setTimezone(getTimezone())->format('m/d/Y h:i A') }}</strong></p>
                                        <p>Payment Term: <strong>{{ $purchase_order->payment_term }}</strong></p>
                                        <p>Shipping Carrier: <strong>{{ $purchase_order->shipping_carrier }}</strong></p>
                                        <p>Shipping Fee: <strong>{{ amountFormat(convertCentsToAmounts($purchase_order->shipping_fee), getCurrencyCode()) }}</strong></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- SUPPLIER -->
                    <tr>
                        <td style="padding-top:25px;">
                            <table width="100%" style="background:#fffaf3;padding:0 20px;border-radius:8px;">
                                <tr>
                                    <td valign="top">
                                        <h4 style="margin-bottom:10px;color:#a47e3c;">Supplier</h4>
                                        <p>{{ $purchase_order->supplier->name }}</p>
                                        <p>{{ $purchase_order->supplier->address }}</p>
                                        <p>Email: {{ $purchase_order->supplier->email }}</p>
                                        <p>Phone: {{ formatPhoneKH($purchase_order->supplier->phone_number) }}</p>
                                    </td>
                                    <td valign="top">
                                        <h4 style="margin-bottom:10px;color:#a47e3c;">Ship To</h4>
                                        <p>{{ $purchase_order->branch->name }}</p>
                                        <p>{{ $purchase_order->branch->location }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ITEMS TABLE -->
                    <tr>
                        <td style="padding-top:25px;">
                            <table width="100%" cellpadding="8" cellspacing="0"
                                style="border-collapse:collapse;background:#fff;border-radius:8px;">
                                <tr style="background:#a47e3c;color:#fff;">
                                    <th align="left">#</th>
                                    <th align="center">Item</th>
                                    <th align="center">Qty</th>
                                    <th align="center">Unit Price</th>
                                    <th align="center">Total</th>
                                </tr>
                                @foreach ($purchase_order->purchaseOrderDetails() as $index => $item)
                                    <tr @if($loop->even) style="background:#f7f2eb;" @endif>
                                        <td align="left">{{ $index+1 }}</td>
                                        <td align="center">{{ $item['product']->name }}</td>
                                        <td align="center">{{ $item['quantity'] }}</td>
                                        <td align="center">{{ amountFormat(ConvertCentsToAmounts($item['unit_cost']), getCurrencyCode()) }}</td>
                                        <td align="center">{{ amountFormat(ConvertCentsToAmounts($item['total_cost']), getCurrencyCode()) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <!-- TOTALS -->
                    <tr>
                        <td align="right" style="padding-top:20px;">
                            <table width="100%" cellpadding="8" cellspacing="0" style="background:#fff; border-radius: 5px;">
                                <tr>
                                    <td align="left">Subtotal</td>
                                    <td align="right">{{ amountFormat(ConvertCentsToAmounts($purchase_order->total_cost), getCurrencyCode()) }}</td>
                                </tr>
                                <tr>
                                    <td align="left">Shipping Fee</td>
                                    <td align="right">{{ amountFormat(ConvertCentsToAmounts($purchase_order->shipping_fee), getCurrencyCode()) }}</td>
                                </tr>
                                <tr>
                                    <td align="left"><strong>Grand Total</strong></td>
                                    <td align="right"><strong>{{ amountFormat(ConvertCentsToAmounts($purchase_order->total_cost + $purchase_order->shipping_fee), getCurrencyCode()) }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="padding-top:30px;font-size:12px;">
                            <p>Thank you for your business</p>
                            <p>© 2025 Khmer Angkor</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
