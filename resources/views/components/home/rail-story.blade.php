@props(['story'])

@php
    $title = data_get($story, 'title', '');
    $url = data_get($story, 'url', '#');
    $image = data_get($story, 'image', '');
    $category = data_get($story, 'category', '');
@endphp

<article class="home-rail-story">
    <a href="{{ $url }}" class="home-rail-story__inner">
        <div class="home-rail-story__thumb">
            <img src="{{ $image }}" alt="{{ $title }}">
        </div>
        <div class="home-rail-story__content">
            @if($category)
                <div class="kicker">{{ $category }}</div>
            @endif
            <h3 class="home-rail-story__title">{{ $title }}</h3>
        </div>
    </a>
</article>
