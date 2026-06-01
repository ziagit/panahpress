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
        <meta property="og:image" content="{{ $post->imageUrl() }}">
        <meta property="og:image:secure_url" content="{{ $post->imageUrl() }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:alt" content="{{ $post->title($locale) }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $post->title($locale) }}">
        <meta name="twitter:description" content="{{ $caption }}">
        <meta name="twitter:image" content="{{ $post->imageUrl() }}">
        <link rel="image_src" href="{{ $post->imageUrl() }}">
    @endpush

    <section class="post-page">
        <article class="post-main post-article">
            <div>
                <div class="post-category">
                    {{ $post->category?->name($locale) ?? __('messages.posts') }}
                </div>
                <h1 class="post-title">{{ $post->title($locale) }}</h1>
                <div class="post-byline">
                    @php
                        $authorName = $post->user?->name;
                        $authorName = $authorName && $authorName !== 'PanahPress Admin'
                            ? $authorName
                            : 'PANAHPRESS';
                    @endphp
                    <span>{{ __('messages.by') }} {{ $authorName }}</span>
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

                <div class="post-tools">
                    <button
                        type="button"
                        id="post-share-copy"
                        class="post-share-copy"
                        data-share-url="{{ $shareUrl }}"
                        aria-label="Copy post URL"
                    >
                        Copy
                    </button>
                </div>
            </div>

            <figure class="post-featured">
                            <img class="post-image" src="{{ $post->imageUrl() }}" alt="{{ $post->title($locale) }}">
                <figcaption class="post-caption">
                    {{ $caption }}
                </figcaption>
            </figure>

            <div class="post-content">
                {!! $post->content($locale) !!}
            </div>

            @push('scripts')
                <script>
                    (() => {
                        const copyButton = document.getElementById('post-share-copy');

                        if (!copyButton || !navigator.clipboard) {
                            return;
                        }

                        copyButton.addEventListener('click', () => {
                            const shareUrl = copyButton.dataset.shareUrl || window.location.href;

                            navigator.clipboard.writeText(shareUrl).then(() => {
                                copyButton.textContent = 'Copied';
                                setTimeout(() => {
                                    copyButton.textContent = 'Copy';
                                }, 1500);
                            }).catch(() => {
                                window.prompt('Copy this URL:', shareUrl);
                            });
                        });
                    })();
                </script>
            @endpush

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
                                                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->title($locale) }}">
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
                        <img src="{{ $sidebarLead->imageUrl() }}" alt="{{ $sidebarLead->title($locale) }}">
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
