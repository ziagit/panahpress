@props([
    'story',
    'variant' => 'card',
    'showExcerpt' => true,
    'showMeta' => true,
    'showCategory' => true,
])

@php
    $title = data_get($story, 'title', '');
    $url = data_get($story, 'url', '#');
    $image = data_get($story, 'image', '');
    $category = data_get($story, 'category', '');
    $excerpt = data_get($story, 'excerpt', '');
    $summary = data_get($story, 'summary', $excerpt);
    $date = data_get($story, 'date', '');
@endphp

<article {{ $attributes->merge(['class' => 'home-story-card home-story-card--'.$variant]) }}>
    <a href="{{ $url }}" class="home-story-card__link">
        <div class="home-story-card__media">
            <img src="{{ $image }}" alt="{{ $title }}">
        </div>
        <div class="home-story-card__body">
            @if($showCategory && $category)
                <div class="kicker">{{ $category }}</div>
            @endif
            <h3 class="home-story-card__title">{{ $title }}</h3>
            @if($showExcerpt)
                <p class="home-story-card__excerpt">{{ $summary }}</p>
            @endif
            @if($showMeta && $date)
                <div class="home-story-card__meta">{{ $date }}</div>
            @endif
        </div>
    </a>
</article>
