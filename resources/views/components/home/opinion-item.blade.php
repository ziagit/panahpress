@props(['story'])

@php
    $title = data_get($story, 'title', '');
    $url = data_get($story, 'url', '#');
    $avatar = data_get($story, 'avatar', '');
    $author = data_get($story, 'author', 'Staff Reporter');
    $summary = data_get($story, 'summary', '');
    $date = data_get($story, 'date', '');
@endphp

<article class="opinion-item">
    <img class="opinion-item__avatar" src="{{ $avatar }}" alt="{{ $author }}">
    <div class="opinion-item__body">
        <div class="opinion-item__author">{{ $author }}</div>
        <h3 class="opinion-item__title"><a href="{{ $url }}">{{ $title }}</a></h3>
        <p class="opinion-item__summary">{{ $summary }}</p>
        @if($date)
            <div class="opinion-item__meta">{{ $date }}</div>
        @endif
    </div>
</article>
