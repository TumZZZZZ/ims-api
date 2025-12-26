<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family:'Khmer OS Siemreap Regular', sans-serif; background:#FFF9F2; color:#1F2937; font-size:13px; margin:0; padding:0;">

    <div style="width:559px; background:#ffffff; padding:30px; border-radius:10px; margin:0 auto;">

        <!-- HEADER -->
        <div style="background:#F59E0B; color:#ffffff; padding:20px; border-radius:8px; text-align:center; margin-bottom:25px;">
            <h1 style="margin:0; font-size:26px; letter-spacing:1px;">{{ $data->branch->name }}</h1>
            <div>{{ strtoupper(__('invoice')) }}</div>
        </div>

        <!-- INFO -->
        <div style="margin-bottom:20px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="color:#D4AF37; font-weight:bold; width:130px; padding:6px 0;">@lang('date')</td>
                    <td style="padding:6px 0;">: {{ \Carbon\Carbon::parse($data->date)->setTimezone(getTimezone())->format('m/d/Y h:i A') }}</td>
                </tr>
                <tr>
                    <td style="color:#D4AF37; font-weight:bold; padding:6px 0;">@lang('branch')</td>
                    <td style="padding:6px 0;">: {{ $data->branch->name }}</td>
                </tr>
                <tr>
                    <td style="color:#D4AF37; font-weight:bold; padding:6px 0;">@lang('order_number')</td>
                    <td style="padding:6px 0;">: #{{ str_pad($data->order_number, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
            </table>
        </div>

        <!-- ITEMS -->
        @php
            $totalDiscount = 0;
            $totalPrice = 0;
        @endphp
        <table style="width:100%; border-collapse:collapse; margin-top:15px; text-align:center;">
            <thead>
                <tr>
                    <th style="background:#F59E0B; color:white; padding:10px; text-align:center;">@lang('qty')</th>
                    <th style="background:#F59E0B; color:white; padding:10px; text-align:center;">@lang('item_name')</th>
                    <th style="background:#F59E0B; color:white; padding:10px; text-align:center;">@lang('discount')</th>
                    <th style="background:#F59E0B; color:white; padding:10px; text-align:center;">@lang('price')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->orderDetails as $item)
                    @php
                        $totalPrice += $item->quantity * $item->price;
                        $totalDiscount += $item->discount_amount ?? 0;
                    @endphp
                    <tr>
                        <td style="padding:10px; border-bottom:1px solid #E5E7EB; text-align:center;">{{ $item->quantity }}x</td>
                        <td style="padding:10px; border-bottom:1px solid #E5E7EB; text-align:center;">{{ $item->product->name }}</td>
                        <td style="padding:10px; border-bottom:1px solid #E5E7EB; text-align:center;">{{ amountFormat(convertCentsToAmounts($item->discount_amount ?? 0), $data->branch->currency_code) }}</td>
                        <td style="padding:10px; border-bottom:1px solid #E5E7EB; text-align:center;">{{ amountFormat(convertCentsToAmounts($item->quantity * $item->price), $data->branch->currency_code) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <table style="width:100%; margin-top:20px;">
            <tr>
                <td style="padding:6px 0; text-align:right;">@lang('discount') :</td>
                <td style="padding:6px 0; text-align:right;">{{ amountFormat(convertCentsToAmounts($totalDiscount), $data->branch->currency_code) }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0; text-align:right; font-size:15px; font-weight:bold; color:#D4AF37;">{{ strtoupper(__('total')) }} :</td>
                <td style="padding:6px 0; text-align:right; font-size:18px; font-weight:bold; color:#D4AF37;">{{ amountFormat(convertCentsToAmounts($totalPrice), $data->branch->currency_code) }}</td>
            </tr>
        </table>

        <!-- PAYMENT -->
        <div style="margin:20px auto 0; background:#D4AF37; color:white; padding:10px 16px; border-radius:6px; text-align:center; display:inline-block;">
            @lang('payment_method') : <span style="font-weight:bold;">{{ $data->payment->value }}</span>
        </div>

        <!-- FOOTER -->
        <div style="margin-top:30px; text-align:center; font-size:11px; color:#6B7280;">
            Thank you for your purchase! <br>
            Powered by Khmer Angkor POS
        </div>

    </div>

</body>
</html>
