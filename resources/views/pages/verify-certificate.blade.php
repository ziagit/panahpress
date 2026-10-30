@extends('layouts.newspaper')
@section('content')
<section class="page-shell" style="padding-block:40px;min-height:65vh;">
    <div style="max-width:560px;margin:auto;">
        <span class="kicker">Panah Press</span>
        <h1>{{ __('certificates.verify') }}</h1>
        <p>{{ __('certificates.lookup_help') }}</p>
        @if($errors->any())
            <div role="alert" style="padding:16px;background:#fff1f2;color:#9f1239;margin-block:20px;">{{ $errors->first() }}</div>
        @endif
        <form method="GET" action="{{ route('certificates.verify', ['locale' => $locale]) }}" style="display:grid;gap:18px;margin-block:24px;">
            @foreach(['number', 'verification_code'] as $field)
                <label style="display:grid;gap:8px;" for="certificate-{{ $field }}">
                    {{ __('certificates.'.$field) }}
                    <input id="certificate-{{ $field }}" name="{{ $field }}" required maxlength="{{ $field === 'number' ? 100 : 48 }}" autocomplete="off" dir="ltr" value="{{ old($field) }}" style="padding:14px;border:1px solid var(--line);width:100%;box-sizing:border-box;">
                </label>
            @endforeach
            <button class="donate-btn" type="submit">{{ __('certificates.verify') }}</button>
        </form>
        <a href="{{ route('verify', ['locale' => $locale]) }}">{{ __('certificates.card') }}</a>
    </div>
</section>
@endsection
