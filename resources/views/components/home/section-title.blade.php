@props([
    'title',
    'dark' => false,
])

<div {{ $attributes->merge(['class' => 'section-heading'.($dark ? ' is-dark' : '')]) }}>
    <h2>{{ $title }}</h2>
</div>
