@props(['story'])

@php
    $title = data_get($story, 'title', '');
    $url = data_get($story, 'url', '#');
    $date = data_get($story, 'date', '');
@endphp

<a href="{{ $url }}" class="compact-link">
    <div>
        <p class="compact-link__title">{{ $title }}</p>
        @if($date)
            <div class="compact-link__meta">{{ $date }}</div>
        @endif
    </div>
</a>
