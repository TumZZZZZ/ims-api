<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: 'Khmer OS Siemreap Regular', sans-serif;
            background: #FFF9F2;
            color: #1F2937;
            font-size: 13px;
        }

        .invoice-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, #D4AF37, #F59E0B);
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
        }

        /* Info */
        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 6px 0;
        }

        .label {
            color: #D4AF37;
            font-weight: bold;
            width: 140px;
        }

        /* Table */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.items th {
            background: #F59E0B;
            color: white;
            padding: 10px;
            text-align: left;
        }

        table.items td {
            padding: 10px;
            border-bottom: 1px solid #D4AF37;
        }

        table.items td:last-child,
        table.items th:last-child {
            text-align: right;
        }

        /* Summary */
        .summary {
            margin-top: 20px;
            width: 100%;
        }

        .summary td {
            padding: 8px 0;
            text-align: right;
        }

        .summary .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #D4AF37;
        }

        .summary .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #D4AF37;
        }

        /* Payment */
        .payment {
            margin-top: 25px;
            background: #D4AF37;
            color: white;
            padding: 10px;
            border-radius: 6px;
            width: fit-content;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="invoice-box">

        {{-- HEADER --}}
        <div class="header">
            <h1>{{ $data['branch_name'] }}</h1>
            <div>{{ strtoupper(__('invoice')) }}</div>
        </div>

        {{-- INFO --}}
        <div class="info">
            <table>
                <tr>
                    <td class="label">@lang('date')</td>
                    <td>: {{ $data['date'] }}</td>
                </tr>
                <tr>
                    <td class="label">@lang('brach')</td>
                    <td>: {{ $data['branch_name'] }}</td>
                </tr>
                <tr>
                    <td class="label">@lang('order_number')</td>
                    <td>: #{{ $data['order_number'] }}</td>
                </tr>
            </table>
        </div>

        {{-- ITEMS --}}
        <table class="items">
            <thead>
                <tr>
                    <th width="15%">Qty</th>
                    <th>Item Name</th>
                    <th width="20%">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2x</td>
                    <td><strong>Hannuman Black Bottle</strong></td>
                    <td>$4.00</td>
                </tr>
            </tbody>
        </table>

        {{-- SUMMARY --}}
        <table class="summary">
            <tr>
                <td>Discount:</td>
                <td>$0.00</td>
            </tr>
            <tr>
                <td class="total-label">TOTAL:</td>
                <td class="total-amount">$4.00</td>
            </tr>
        </table>

        {{-- PAYMENT --}}
        <div class="payment">
            Payment Method: <strong>ABA</strong>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            Thank you for your purchase! <br>
            Powered by Holiday Mart POS
        </div>

    </div>

</body>

</html>
