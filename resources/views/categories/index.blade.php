@extends('layouts.newspaper')

@section('content')
    <section class="hero">
        <div class="hero-card" style="grid-column:1/-1;">
            <span class="badge">{{ __('messages.categories') }}</span>
            <h1 style="margin:0.75rem 0 0.5rem;">{{ __('messages.categories') }}</h1>
            <p class="text-muted" style="margin:0;">{{ __('messages.posts_in_category') }}</p>
        </div>
    </section>

    <section>
        <div class="grid">
            @forelse($categories as $category)
                <article class="card">
                    <a href="{{ route('categories.show', ['locale' => $locale, 'category' => $category]) }}" class="card-link">
                        <div class="meta">
                            <h3>{{ $category->name($locale) }}</h3>
                            <p class="text-muted">{{ $category->posts_count }} {{ __('messages.posts') }}</p>
                        </div>
                    </a>
                </article>
            @empty
                <div class="card" style="padding:2rem; text-align:center;">{{ __('messages.no_categories') }}</div>
            @endforelse
        </div>
    </section>
@endsection
