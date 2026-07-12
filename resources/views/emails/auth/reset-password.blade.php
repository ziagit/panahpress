<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f9fc;font-family:{{ app()->getLocale() === 'fa' ? '"XB Niloofar", "Merriweather", sans-serif' : '"Merriweather", "XB Niloofar", Georgia, serif' }};color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f9fc;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(180deg,#0d93c5 0%,#0a79a7 100%);padding:28px 32px;color:#ffffff;">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding-right:16px;">
                                        <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="{{ $appName }}" width="72" height="72" style="display:block;border:0;outline:none;text-decoration:none;width:72px;height:72px;object-fit:contain;">
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <div style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;opacity:0.9;margin-bottom:6px;">{{ $appName }}</div>
                                        <div style="font-size:24px;line-height:1.2;font-weight:700;">{{ $buttonText }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <div style="font-size:16px;line-height:1.8;color:#334155;margin-bottom:16px;">{{ $greeting }}</div>
                            <div style="font-size:16px;line-height:1.8;color:#334155;margin-bottom:24px;">{{ $line }}</div>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <a href="{{ $resetUrl }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;padding:14px 22px;border-radius:999px;font-weight:700;">{{ $buttonText }}</a>
                                    </td>
                                </tr>
                            </table>

                            <div style="font-size:13px;line-height:1.7;color:#64748b;word-break:break-word;">
                                {{ $expiration }}
                            </div>

                            <div style="margin-top:18px;font-size:13px;line-height:1.7;color:#64748b;word-break:break-word;">
                                {{ $resetUrl }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
