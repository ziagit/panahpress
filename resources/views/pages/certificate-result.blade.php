@extends('layouts.newspaper')
@section('content')
@php
    $status = $certificate->status();
    $imageUrl = route('certificates.image', ['locale' => $locale, 'token' => $certificate->verification_token]);
@endphp
<style>
    .certificate-shell{max-width:1080px;margin:auto;padding-block:36px 60px;}
    .certificate-status{padding:24px;border:1px solid #a7d9be;background:#effaf3;border-radius:10px;margin-bottom:24px;}
    .certificate-status h1{margin:8px 0;color:#166534;font-family:var(--serif);font-size:clamp(1.5rem,3vw,2.2rem);}
    .certificate-status p{margin:0;color:#334155;line-height:1.7;}
    .certificate-status--expired,.certificate-status--pending{background:#fffbeb;border-color:#fcd34d;}
    .certificate-status--expired h1,.certificate-status--pending h1{color:#92400e;}
    .certificate-status--revoked{background:#fff1f2;border-color:#fda4af;}
    .certificate-status--revoked h1{color:#9f1239;}
    .certificate-body{display:grid;grid-template-columns:minmax(0,3fr) minmax(0,7fr);gap:32px;align-items:start;}
    .certificate-picture{margin:0;padding:14px;background:#f8fafc;border:1px solid var(--line);border-radius:8px;}
    .certificate-picture img{display:block;width:100%;height:auto;max-height:320px;object-fit:contain;}
    .certificate-picture figcaption{font-size:.85rem;text-align:center;margin-top:12px;line-height:1.6;}
    .certificate-details{min-width:0;}
    .certificate-details h2{margin:0 0 10px;font-size:clamp(1.4rem,3vw,2rem);font-family:var(--serif);overflow-wrap:anywhere;}
    .certificate-title{color:#475569;margin:0 0 22px;line-height:1.7;overflow-wrap:anywhere;}
    .certificate-facts{margin:0;}
    .certificate-facts div{display:grid;grid-template-columns:minmax(120px,1fr) minmax(0,2fr);gap:16px;padding-block:13px;border-bottom:1px solid var(--line);}
    .certificate-facts dt{color:#64748b;}.certificate-facts dd{margin:0;font-weight:600;overflow-wrap:anywhere;}
    .certificate-description{white-space:pre-line;line-height:1.8;overflow-wrap:anywhere;}
    .certificate-return{display:inline-block;margin-top:28px;text-decoration:underline;}
    @media(max-width:640px){.certificate-body{grid-template-columns:1fr;gap:24px;}.certificate-picture{width:min(100%,280px);box-sizing:border-box;margin-inline:auto;}.certificate-facts div{grid-template-columns:1fr;gap:5px;}}
</style>
<section class="page-shell">
<div class="certificate-shell">
    <header class="certificate-status certificate-status--{{ $status }}">
        <span class="kicker">{{ $certificate->issuer }}</span>
        <h1>@if($status === 'valid')<span aria-hidden="true">✓</span> @endif{{ __('certificates.heading_'.$status) }}</h1>
        <p>{{ __('certificates.intro_'.$status) }}</p>
    </header>
    <div class="certificate-body">
        <figure class="certificate-picture">
            <a href="{{ $imageUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('certificates.open_image') }}"><img src="{{ $imageUrl }}" alt="{{ __('certificates.image') }} — {{ $certificate->recipient_name }}"></a>
            <figcaption><a href="{{ $imageUrl }}" target="_blank" rel="noopener noreferrer">{{ __('certificates.open_image') }} ↗</a></figcaption>
        </figure>
        <article class="certificate-details">
            <h2>{{ $certificate->recipient_name }}</h2>
            <p class="certificate-title">{{ $certificate->title }}</p>
            <dl class="certificate-facts">
                <div><dt>{{ __('certificates.status') }}</dt><dd>{{ __('certificates.'.$status) }}</dd></div>
                <div><dt>{{ __('certificates.number') }}</dt><dd><bdi>{{ $certificate->number }}</bdi></dd></div>
                <div><dt>{{ __('certificates.issuer') }}</dt><dd>{{ $certificate->issuer }}</dd></div>
                <div><dt>{{ __('certificates.issue_date') }}</dt><dd>{{ $certificate->issue_date->format('d M Y') }}</dd></div>
                <div><dt>{{ __('certificates.expiry_date') }}</dt><dd>{{ $certificate->expiry_date?->format('d M Y') ?? __('certificates.no_expiry') }}</dd></div>
            </dl>
            @if($certificate->description)<p class="certificate-description">{{ $certificate->description }}</p>@endif
        </article>
    </div>
    <a class="certificate-return" href="{{ route('certificates.verify', ['locale' => $locale]) }}">{{ __('certificates.another') }}</a>
</div>
</section>
@endsection
