@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 24px; min-height: 70vh;">
        <div class="page-card" style="display:grid; grid-template-columns: 320px minmax(0, 1fr); gap: 34px; align-items:center;">
            <div style="display:flex; align-items:center; justify-content:center;">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}" style="width: 260px; height: 260px; object-fit: contain;">
            </div>
            <div>
                <h1 style="margin: 0 0 12px; font-family: var(--serif); font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05; color:#11161c;">{{ __('messages.about_page_title') }}</h1>
                <p style="margin: 0 0 12px; color:#334155; font-size: 1.05rem; line-height: 1.7;">{{ __('messages.about_page_intro') }}</p>
                <p style="margin: 0; color:#475569; font-size: 1rem; line-height: 1.8;">{{ __('messages.about_page_body') }}</p>
                <p style="margin: 14px 0 0; color:#475569; font-size: 1rem; line-height: 1.8;">{{ __('messages.about_page_body_2') }}</p>
                <p style="margin: 14px 0 0; color:#475569; font-size: 1rem; line-height: 1.8;">{{ __('messages.about_page_body_3') }}</p>
            </div>
        </div>
    </section>
@endsection
