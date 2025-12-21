<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 auto !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>

<body style="font-family: 'Khmer OS Siemreap Regular', Arial, sans-serif; font-size: 16px;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="height:100vh;width:100%;text-align:center;">
        <tr>
            <td align="center" valign="middle" style="padding:40px 10px;">
                <table role="presentation" class="email-container" width="420" cellpadding="0" cellspacing="0"
                    style="max-width:420px;width:100%;background:#F8F8F8;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.05);margin:auto;">
                    <tr>
                        <td
                            style="background:#3c2a21;padding:16px;text-align:center;color:#ffffff;font-size:17px;font-weight:bold;border-radius:8px 8px 0 0;">
                            {{ $app_name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px;text-align:center;color:#a47e3c;font-size:15px;line-height:1.5;">
                            <p style="margin:0 0 16px 0;">Use this code to verify your identity and reset your password.
                            </p>
                            <div
                                style="display:inline-block;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;padding:12px 24px;font-size:26px;font-weight:bold;letter-spacing:4px;color:#a47e3c;">
                                {{ $otp }}
                            </div>
                            <p style="margin:15px 0 0 0;font-size:14px;color:#a47e3c;">
                                This code will expire in 24 hours.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
