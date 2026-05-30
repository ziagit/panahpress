@props(['story'])

@php
    $title = data_get($story, 'title', '');
    $url = data_get($story, 'url', '#');
    $image = data_get($story, 'image', '');
    $date = data_get($story, 'date', '');
    $videoUrl = data_get($story, 'video_url', $url);
@endphp

<article class="home-video-card">
    <button
        type="button"
        class="home-video-card__button"
        data-video-open
        data-video-url="{{ $videoUrl }}"
        data-video-title="{{ e($title) }}"
        data-video-image="{{ $image }}"
        data-video-date="{{ $date }}"
    >
        <div class="home-video-card__media">
            <img src="{{ $image }}" alt="{{ $title }}">
            <span class="home-video-card__play" aria-hidden="true"></span>
        </div>
        <h3 class="home-video-card__title">{{ $title }}</h3>
        @if($date)
            <div class="home-video-card__meta">{{ $date }}</div>
        @endif
    </button>
</article>
