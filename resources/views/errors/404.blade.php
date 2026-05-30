@extends('layouts.app')

@section('content')
    <section class="hero" style="margin-top:2rem;">
        <div class="hero-card" style="grid-column:1/-1; text-align:center; padding:3rem 2rem;">
            <span class="badge">{{ __('messages.page_not_found') }}</span>
            <h1 style="margin:1rem 0 0.75rem; font-size:clamp(2.2rem, 4vw, 4rem); line-height:1.05;">
                404
            </h1>
            <p class="text-muted" style="max-width:720px; margin:0 auto 1.5rem; font-size:1.05rem;">
                {{ __('messages.page_not_found_message') }}
            </p>
            <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem;">
                <a href="{{ route('home', ['locale' => $locale ?? app()->getLocale()]) }}" class="button">{{ __('messages.back_to_home') }}</a>
                <a href="{{ route('categories.index', ['locale' => $locale ?? app()->getLocale()]) }}" class="button" style="background:#475569;">{{ __('messages.categories') }}</a>
            </div>
        </div>
    </section>
@endsection
