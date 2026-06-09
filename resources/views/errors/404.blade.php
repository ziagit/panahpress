@extends('layouts.newspaper')

@php
    $locale = $locale ?? app()->getLocale() ?? 'en';
    $title = __('messages.page_not_found') . ' | ' . __('messages.site_name');
    $metaDescription = __('messages.page_not_found_message');
    $canonicalUrl = route('notfound', ['locale' => $locale]);
@endphp

@section('content')
    <section class="news-home">
        <div class="page-card" style="text-align:center; padding: clamp(2rem, 5vw, 4rem) 1.5rem;">
            <span class="badge">{{ __('messages.page_not_found') }}</span>
            <h1 style="margin: 1rem 0 0.5rem; font-family: var(--serif); font-size: clamp(2.2rem, 4vw, 4rem); line-height: 1.05; color: #11161c;">
                404
            </h1>
            <p style="max-width: 720px; margin: 0 auto 1.75rem; color: #475569; font-size: 1.05rem; line-height: 1.7;">
                {{ __('messages.page_not_found_message') }}
            </p>
            <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem;">
                <a href="{{ route('home', ['locale' => $locale]) }}" class="subscribe-btn">{{ __('messages.back_to_home') }}</a>
                <a href="{{ route('categories.index', ['locale' => $locale]) }}" class="subscribe-btn" style="border-color:#475569; color:#475569;">{{ __('messages.categories') }}</a>
            </div>
        </div>
    </section>
@endsection
