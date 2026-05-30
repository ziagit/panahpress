@extends('layouts.newspaper')

@section('content')
    <section class="category-page">
        <div class="category-layout">
            <div class="category-feed">
                @forelse($posts as $post)
                    <article class="category-item">
                        <div class="category-item__content">
                            <div class="category-item__kicker">
                                {{ $category->name($locale) }}
                            </div>

                            <h2 class="category-item__title">
                                <a href="{{ route('posts.show', ['locale' => $locale, 'post' => $post->slug]) }}">
                                    {{ $post->title($locale) }}
                                </a>
                            </h2>

                            <p class="category-item__excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content($locale)), 180) }}
                            </p>

                            <div class="category-item__meta">
                                <span>{{ $post->published_at?->translatedFormat('F j, Y') }}</span>
                                <span>{{ $category->name($locale) }}</span>
                            </div>
                        </div>

                        @if($post->image)
                            <a href="{{ route('posts.show', ['locale' => $locale, 'post' => $post->slug]) }}" class="category-item__media">
                                <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title($locale) }}">
                            </a>
                        @endif
                    </article>
                @empty
                    <div class="hero-card" style="grid-column: 1 / -1;">
                        <span class="badge">{{ __('messages.category') }}</span>
                        <h1 style="margin:0.75rem 0 0.5rem;">{{ $category->name($locale) }}</h1>
                        <p class="text-muted" style="margin:0;">{{ __('messages.no_posts') }}</p>
                    </div>
                @endforelse

                @if($posts->hasPages())
                    <div class="category-pagination" aria-label="Pagination">
                        @if($posts->onFirstPage())
                            <span class="is-disabled">«</span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" rel="prev">«</a>
                        @endif

                        @php
                            $lastPage = $posts->lastPage();
                            $currentPage = $posts->currentPage();
                            $pages = [];

                            if ($lastPage <= 7) {
                                $pages = range(1, $lastPage);
                            } else {
                                $pages = array_unique(array_filter([
                                    1,
                                    2,
                                    3,
                                    $currentPage > 4 ? null : 4,
                                    $currentPage > 4 ? $currentPage - 1 : null,
                                    $currentPage,
                                    $currentPage < $lastPage - 3 ? $currentPage + 1 : null,
                                    $lastPage - 2,
                                    $lastPage - 1,
                                    $lastPage,
                                ]));
                                sort($pages);
                            }
                        @endphp

                        @foreach($pages as $page)
                            @if(isset($prevPage) && $page > $prevPage + 1)
                                <span class="is-disabled">…</span>
                            @endif

                            @if($page == $currentPage)
                                <span class="is-active">{{ $page }}</span>
                            @else
                                <a href="{{ $posts->url($page) }}">{{ $page }}</a>
                            @endif

                            @php $prevPage = $page; @endphp
                        @endforeach

                        @if($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" rel="next">»</a>
                        @else
                            <span class="is-disabled">»</span>
                        @endif
                    </div>
                @endif
            </div>

            <aside class="category-sidebar">
                <div class="sidebar-box">
                    <h3 class="sidebar-box__title">{{ __('messages.search') }}</h3>
                    <form class="sidebar-search" method="get" action="{{ route('categories.show', ['locale' => $locale, 'category' => $category]) }}">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_here') }}" aria-label="{{ __('messages.search') }}">
                        <button type="submit" aria-label="Search">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4a6 6 0 1 1 3.8 10.64l4.78 4.78-1.42 1.42-4.78-4.78A6 6 0 0 1 10 4zm0 2a4 4 0 1 0 .01 8.01A4 4 0 0 0 10 6z"/></svg>
                        </button>
                    </form>
                </div>

                <div class="sidebar-box sidebar-panel">
                    <h3 class="sidebar-box__title">{{ __('messages.subscribe_newsletter') }}</h3>
                    <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe', ['locale' => $locale]) }}">
                        @csrf
                        <input type="hidden" name="source" value="category-sidebar">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}" aria-label="{{ __('messages.email') }}" required>
                        <button type="submit">{{ __('messages.subscribe') }}</button>
                    </form>
                </div>

                <div class="sidebar-box">
                    <h3 class="sidebar-box__title">{{ __('messages.latest') }}</h3>
                    <div class="latest-list">
                        @foreach($latestPosts as $latestPost)
                            <a class="latest-item" href="{{ route('posts.show', ['locale' => $locale, 'post' => $latestPost->slug]) }}">
                                @if($latestPost->image)
                                    <div class="latest-item__media">
                                        <img src="{{ asset('storage/'.$latestPost->image) }}" alt="{{ $latestPost->title($locale) }}">
                                    </div>
                                @endif
                                <div>
                                    <div class="latest-item__meta">
                                        {{ $latestPost->published_at?->translatedFormat('M d, Y') }} · {{ $category->name($locale) }}
                                    </div>
                                    <div class="latest-item__title">{{ $latestPost->title($locale) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-box category-ad" aria-hidden="true"></div>
            </aside>
        </div>
    </section>
@endsection
