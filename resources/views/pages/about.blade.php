@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 24px; padding-bottom: 48px; min-height: 70vh;">
        <div class="page-card" style="display:grid; grid-template-columns: 320px minmax(0, 1fr); gap: 34px; align-items:start;">
            <div style="display:flex; align-items:center; justify-content:center;">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="width: 260px; height: 260px; object-fit: contain;">
            </div>
            <div style="padding-top: 2px;">
                <h1 style="margin: 0 0 12px; font-family: var(--serif); font-size: clamp(1.55rem, 2.2vw, 2rem); line-height: 1.05; color:#11161c;">{{ __('messages.about_page_title') }}</h1>
                @foreach (__('messages.about_page_paragraphs') as $paragraph)
                    <p style="margin: {{ $loop->first ? '0 0 12px' : '14px 0 0' }}; color:#475569; font-size: 1rem; line-height: 1.85;">{{ $paragraph }}</p>
                @endforeach
                <p style="margin: 18px 0 0; color:#11161c; font-size: 1.05rem; line-height: 1.8; font-weight: 700;">{{ __('messages.about_page_signoff') }}</p>
            </div>
        </div>
    </section>
@endsection
