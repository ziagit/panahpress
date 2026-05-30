@extends('layouts.newspaper')

@section('content')
    @php
        $sidebarLead = $relatedPosts->first();
        $sidebarList = $relatedPosts->skip(1)->take(4);
        $relatedCards = $relatedArticles->take(3);
        $caption = \Illuminate\Support\Str::limit(strip_tags($post->content($locale)), 130);
        $shareUrl = route('posts.show', ['locale' => $locale, 'post' => $post->slug]);
        $shareUrlEncoded = urlencode($shareUrl);
        $shareTitle = urlencode($post->title($locale));
    @endphp

    @push('head')
        <meta property="og:type" content="article">
        <meta property="og:title" content="{{ $post->title($locale) }}">
        <meta property="og:description" content="{{ $caption }}">
        <meta property="og:url" content="{{ $shareUrl }}">
        @if($post->image)
            <meta property="og:image" content="{{ asset('storage/'.$post->image) }}">
        @endif
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $post->title($locale) }}">
        <meta name="twitter:description" content="{{ $caption }}">
        @if($post->image)
            <meta name="twitter:image" content="{{ asset('storage/'.$post->image) }}">
        @endif
    @endpush

    <section class="post-page">
        <article class="post-main post-article">
            <div>
                <div class="post-category">
                    {{ $post->category?->name($locale) ?? __('messages.posts') }}
                </div>
                <h1 class="post-title">{{ $post->title($locale) }}</h1>
                <div class="post-byline">
                    <span>{{ __('messages.by') }} {{ $post->user?->name ?? 'Staff Reporter' }}</span>
                    <span>{{ $post->published_at?->translatedFormat('M d, Y') }}</span>
                </div>
            </div>

            <div class="post-share-row">
                <div class="post-share" aria-label="Social share">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrlEncoded }}" class="facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer">f</a>
                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrlEncoded }}&text={{ $shareTitle }}" class="x" aria-label="X" target="_blank" rel="noopener noreferrer">x</a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrlEncoded }}" class="linkedin" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">in</a>
                    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrlEncoded }}" class="whatsapp" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">wa</a>
                </div>
            </div>

            <figure class="post-featured">
                @if($post->image)
                    <img class="post-image" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title($locale) }}">
                @endif
                <figcaption class="post-caption">
                    {{ $caption }}
                </figcaption>
            </figure>

            <div class="post-content">
                {!! $post->content($locale) !!}
            </div>

            @if($relatedCards->isNotEmpty())
                <section style="margin-top: 18px;">
                    <div class="post-related-header">
                        <h2>{{ __('messages.related_posts') }}</h2>
                        <div class="post-related-header__line"></div>
                        <div class="post-related-header__controls" aria-hidden="true">
                            <button type="button">‹</button>
                            <button type="button">›</button>
                        </div>
                    </div>

                    <div class="post-related-grid">
                        @foreach($relatedCards as $article)
                            <article class="post-related-card">
                                <a class="post-related-card__media" href="{{ route('posts.show', ['locale' => $locale, 'post' => $article->slug]) }}">
                                    @if($article->image)
                                        <img src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->title($locale) }}">
                                    @endif
                                </a>
                                <div class="post-related-card__kicker">
                                    {{ $article->category?->name($locale) ?? __('messages.posts') }}
                                </div>
                                <h3 class="post-related-card__title">
                                    <a href="{{ route('posts.show', ['locale' => $locale, 'post' => $article->slug]) }}">
                                        {{ $article->title($locale) }}
                                    </a>
                                </h3>
                                <div class="post-related-card__meta">
                                    {{ $article->published_at?->translatedFormat('M d, Y') }}
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        <aside class="post-sidebar">
            <div class="sidebar-box post-sidebar__search">
                    <h3 class="sidebar-box__title">{{ __('messages.search') }}</h3>
                <form class="sidebar-search" method="get">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_here') }}" aria-label="{{ __('messages.search') }}">
                    <button type="submit" aria-label="Search">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4a6 6 0 1 1 3.8 10.64l4.78 4.78-1.42 1.42-4.78-4.78A6 6 0 0 1 10 4zm0 2a4 4 0 1 0 .01 8.01A4 4 0 0 0 10 6z"/></svg>
                    </button>
                </form>
            </div>

            <div class="sidebar-box post-sidebar__newsletter sidebar-panel">
                <h3 class="sidebar-box__title">{{ __('messages.subscribe_newsletter') }}</h3>
                <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe', ['locale' => $locale]) }}">
                    @csrf
                    <input type="hidden" name="source" value="post-sidebar">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}" aria-label="{{ __('messages.email') }}" required>
                    <button type="submit">{{ __('messages.subscribe') }}</button>
                </form>
            </div>

            <div class="sidebar-box post-sidebar__latest">
                <h3 class="sidebar-box__title">{{ __('messages.latest') }}</h3>

                @if($sidebarLead)
                    <a class="post-sidebar__latest-featured" href="{{ route('posts.show', ['locale' => $locale, 'post' => $sidebarLead->slug]) }}">
                        @if($sidebarLead->image)
                            <img src="{{ asset('storage/'.$sidebarLead->image) }}" alt="{{ $sidebarLead->title($locale) }}">
                        @endif
                        <div class="post-sidebar__latest-featured-title">{{ $sidebarLead->title($locale) }}</div>
                        <div class="post-sidebar__latest-featured-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags($sidebarLead->content($locale)), 110) }}
                        </div>
                    </a>
                @endif

                <div class="post-sidebar__latest-list">
                    @foreach($sidebarList as $item)
                        <article class="post-sidebar__latest-item">
                            <div class="post-sidebar__latest-item-meta">
                                {{ $item->published_at?->translatedFormat('M d, Y') }} · {{ $item->category?->name($locale) ?? __('messages.posts') }}
                            </div>
                            <div class="post-sidebar__latest-item-title">
                                <a href="{{ route('posts.show', ['locale' => $locale, 'post' => $item->slug]) }}">
                                    {{ $item->title($locale) }}
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="category-ad" aria-hidden="true"></div>
        </aside>
    </section>
@endsection
