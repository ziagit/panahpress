<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.contact_email_subject') }}</title>
</head>
<body style="margin:0; padding:24px; background:#f8fafc; color:#11161c; font-family: {{ $locale === 'fa' ? '"XB Niloofar", "Merriweather", sans-serif' : '"Merriweather", "XB Niloofar", Georgia, serif' }};">
    <div style="max-width:640px; margin:0 auto; background:#fff; border:1px solid #e1e4e8; padding:24px;">
        <h1 style="margin:0 0 16px; font-size:24px; line-height:1.2;">{{ __('messages.contact_email_subject') }}</h1>

        <p style="margin:0 0 8px;"><strong>{{ __('messages.name') }}:</strong> {{ $name }}</p>
        <p style="margin:0 0 8px;"><strong>{{ __('messages.email') }}:</strong> {{ $email }}</p>
        <p style="margin:0 0 16px;"><strong>{{ __('messages.subject') }}:</strong> {{ $subjectLine }}</p>

        <div style="padding:16px; border:1px solid #e1e4e8; background:#f8fafc; line-height:1.8; white-space:pre-wrap;">{{ $messageBody }}</div>
    </div>
</body>
</html>
