@extends('layouts.newspaper')

@php
    $title = __('messages.site_name');
    $metaDescription = __('messages.tagline');
    $canonicalUrl = url()->current();
@endphp

@push('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
@endpush

@section('content')
    <div class="news-home">
        <section class="hero-grid" id="top-stories">
            <article class="story-spotlight">
                <a href="{{ data_get($featuredLead, 'url', '#') }}" class="story-spotlight__image">
                    <img src="{{ data_get($featuredLead, 'image', '') }}" alt="{{ data_get($featuredLead, 'title', '') }}">
                </a>
                <div class="story-spotlight__content">
                    <div class="kicker">{{ data_get($featuredLead, 'category', 'Politics') }}</div>
                    <h1 class="story-title">
                        <a href="{{ data_get($featuredLead, 'url', '#') }}">{{ data_get($featuredLead, 'title', '') }}</a>
                    </h1>
                    <p class="story-summary">{{ data_get($featuredLead, 'summary', '') }}</p>
                    <div class="story-spotlight__meta">
                        <span class="story-meta">{{ data_get($featuredLead, 'date', '') }}</span>
                        <span class="story-meta">{{ data_get($featuredLead, 'author', 'Staff Reporter') }}</span>
                    </div>
                </div>
            </article>

            @php
                $heroVideos = collect($videoStories)
                    ->filter(fn($story) => data_get($story, 'video_url'))
                    ->map(function ($story) {
                        $videoUrl = data_get($story, 'video_url', '');
                        $playerUrl = $videoUrl.(str_contains($videoUrl, '?') ? '&' : '?').'autoplay=1&mute=1&playsinline=1&controls=1&rel=0&modestbranding=1&iv_load_policy=3&disablekb=1&fs=1&enablejsapi=1';

                        return [
                            'title' => data_get($story, 'title', ''),
                            'video_url' => $playerUrl,
                            'image' => data_get($story, 'image', ''),
                        ];
                    })
                    ->values();

                $heroVideo = $heroVideos->first();
                $heroVideoPlayerUrl = data_get($heroVideo, 'video_url', '');
            @endphp

            @php
                $heroVideosJson = htmlspecialchars(json_encode($heroVideos->toArray(), JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
            @endphp
            <aside
                class="hero-live-card"
                aria-label="{{ __('messages.live_tv') }}"
                data-hero-videos="{{ $heroVideosJson }}"
                data-unmute-label="{{ __('messages.unmute') }}"
                data-mute-label="{{ __('messages.mute') }}"
            >
                <div class="hero-live-card__body">
                    <div class="hero-live-card__label">{{ __('messages.live_tv') }}</div>
                    <div class="hero-live-card__media">
                        @if($heroVideoPlayerUrl)
                            <iframe
                                id="hero-live-tv-frame"
                                src="{{ $heroVideoPlayerUrl }}"
                                title="{{ data_get($heroVideo, 'title', __('messages.live_tv')) }}"
                                allow="autoplay; encrypted-media; picture-in-picture"
                                allowfullscreen
                                loading="eager"
                                referrerpolicy="strict-origin-when-cross-origin"
                            ></iframe>
                            <button
                                type="button"
                                class="hero-live-card__sound"
                                id="hero-live-tv-sound"
                                data-muted="true"
                                aria-pressed="true"
                                aria-label="{{ __('messages.unmute') }}"
                            >
                                <span class="hero-live-card__sound-icon" aria-hidden="true">🔇</span>
                                <span class="hero-live-card__sound-label">{{ __('messages.unmute') }}</span>
                            </button>
                        @else
                            <img src="{{ data_get($heroVideo, 'image', '') }}" alt="{{ data_get($heroVideo, 'title', '') }}">
                        @endif
                    </div>

                    @if($heroVideos->count() > 1)
                        <div class="hero-live-card__playlist" aria-label="Live video playlist">
                            @foreach($heroVideos as $index => $video)
                                <button
                                    type="button"
                                    class="hero-live-card__playlist-item{{ $index === 0 ? ' is-active' : '' }}"
                                    data-hero-video-index="{{ $index }}"
                                >
                                    {{ $video['title'] ?: __('messages.live_tv') }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </aside>
        </section>

        <section class="featured-row" aria-label="Featured stories">
            @foreach($featuredCards as $story)
                <x-home.story-card :story="$story" variant="card" :show-excerpt="true" :show-meta="false" />
            @endforeach
        </section>

        <section class="home-ad-banner" aria-label="Advertisement">
            <img
                src="{{ asset('images/ads/2nd-anniversary.JPEG') }}"
                alt="2nd Anniversary advertisement"
                class="home-ad-banner__image"
                loading="lazy"
            >
        </section>

        <section class="business-section" id="business">
            <x-home.section-title :title="__('messages.business')" />
            <div class="business-layout">
                <article class="business-lead__card">
                    <a href="{{ data_get($businessLead, 'url', '#') }}" class="business-lead__media">
                        <img src="{{ data_get($businessLead, 'image', '') }}" alt="{{ data_get($businessLead, 'title', '') }}">
                    </a>
                    <div class="business-lead__content">
                        <div class="kicker">{{ data_get($businessLead, 'category', 'Economy') }}</div>
                        <h3 class="business-lead__title">
                            <a href="{{ data_get($businessLead, 'url', '#') }}">{{ data_get($businessLead, 'title', '') }}</a>
                        </h3>
                        <p class="story-summary">{{ data_get($businessLead, 'summary', '') }}</p>
                        <div class="story-spotlight__meta">
                            <span class="story-meta">{{ data_get($businessLead, 'date', '') }}</span>
                        </div>
                    </div>
                </article>

                <aside
                    class="business-weather"
                    id="weather-widget"
                    data-weather-url="{{ route('weather.current', ['locale' => $locale]) }}"
                    aria-label="Weather widget"
                >
                    <div class="business-weather__city" data-weather-city>{{ data_get($weather, 'city', __('messages.current_location')) }}</div>
                    <div class="business-weather__icon" data-weather-icon aria-hidden="true">{{ data_get($weather, 'icon', '☀') }}</div>
                    <div class="business-weather__temp" data-weather-temperature>{{ data_get($weather, 'temperature', '+31°C') }}</div>
                    <div class="business-weather__days" data-weather-days>
                        @foreach(data_get($weather, 'days', []) as $day)
                            <div class="business-weather__day" data-weather-day>
                                <span class="business-weather__day-label" data-weather-day-label>{{ data_get($day, 'label', '') }}</span>
                                <span class="business-weather__day-range">
                                    <span data-weather-day-high>{{ data_get($day, 'high', '') }}</span>
                                    <span aria-hidden="true"> - </span>
                                    <span data-weather-day-low>{{ data_get($day, 'low', '') }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>

            <div class="business-grid">
                @foreach($businessStories as $story)
                    <x-home.story-card :story="$story" variant="business-card" :show-excerpt="true" :show-meta="true" />
                @endforeach
            </div>
        </section>

        <section class="editorial-columns-section" id="editorial-columns">
            <div class="editorial-columns">
                @foreach($editorialColumns as $column)
                    <article class="editorial-column">
                        <x-home.section-title :title="$column['title']" />

                        @if(data_get($column, 'lead.title'))
                            <a href="{{ data_get($column, 'lead.url', '#') }}" class="editorial-column__lead">
                                <div class="editorial-column__lead-image">
                                    <img src="{{ data_get($column, 'lead.image', '') }}" alt="{{ data_get($column, 'lead.title', '') }}">
                                </div>
                                <div class="editorial-column__lead-body">
                                    <h3 class="compact-link__title">{{ data_get($column, 'lead.title', '') }}</h3>
                                    @if(data_get($column, 'lead.date'))
                                        <div class="compact-link__meta">{{ data_get($column, 'lead.date') }}</div>
                                    @endif
                                </div>
                            </a>
                        @endif

                        <div class="compact-links">
                            @foreach($column['items'] as $story)
                                <x-home.compact-link :story="$story" />
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>

    <section class="video-section" id="videos">
        <div class="news-shell">
            <x-home.section-title :title="__('messages.news_current_affairs')" dark />
            <div class="video-grid">
                @foreach($videoStories as $story)
                    <x-home.video-card :story="$story" />
                @endforeach
            </div>
        </div>
    </section>

    <div class="video-dialog" id="video-dialog" aria-hidden="true">
        <div class="video-dialog__backdrop" data-video-close></div>
        <div class="video-dialog__panel" role="dialog" aria-modal="true" aria-labelledby="video-dialog-title">
            <div class="video-dialog__header">
                <h3 class="video-dialog__title" id="video-dialog-title"></h3>
                <button type="button" class="video-dialog__close" data-video-close aria-label="{{ __('messages.cancel') }}">×</button>
            </div>
            <div class="video-dialog__frame">
                <iframe
                    id="video-dialog-frame"
                    src="about:blank"
                    title="{{ __('messages.live_tv') }}"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </div>

    <section class="opinion-section" id="opinions">
        <div class="opinion-layout">
            <div class="opinion-main">
                <div class="section-heading">
                    <h2>{{ __('messages.opinions') }}</h2>
                </div>

                <article class="opinion-lead">
                    <a href="{{ data_get($opinionLead, 'url', '#') }}" class="opinion-lead__media">
                        <img src="{{ data_get($opinionLead, 'image', '') }}" alt="{{ data_get($opinionLead, 'title', '') }}">
                    </a>
                    <div class="opinion-lead__body">
                        <div class="kicker">{{ data_get($opinionLead, 'category', 'Politics') }}</div>
                        <h3 class="opinion-lead__title">
                            <a href="{{ data_get($opinionLead, 'url', '#') }}">{{ data_get($opinionLead, 'title', '') }}</a>
                        </h3>
                        <p class="opinion-lead__excerpt">{{ data_get($opinionLead, 'summary', '') }}</p>
                        <div class="story-spotlight__meta">
                            <span class="story-meta">{{ data_get($opinionLead, 'date', '') }}</span>
                        </div>
                    </div>
                </article>

                <div class="opinion-divider"></div>

                <div class="opinion-grid">
                    @foreach($opinionStories as $story)
                        <article class="opinion-card">
                            <a href="{{ data_get($story, 'url', '#') }}" class="opinion-card__media">
                                <img src="{{ data_get($story, 'image', '') }}" alt="{{ data_get($story, 'title', '') }}">
                            </a>
                            <div class="opinion-card__body">
                                <div class="kicker">{{ data_get($story, 'category', 'Politics') }}</div>
                                <h3 class="opinion-card__title">
                                    <a href="{{ data_get($story, 'url', '#') }}">{{ data_get($story, 'title', '') }}</a>
                                </h3>
                                <div class="story-meta">{{ data_get($story, 'date', '') }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <aside class="read-more">
                <h3>{{ __('messages.read_more') }}</h3>
                <a href="{{ data_get($opinionSidebarLead, 'url', '#') }}" class="read-more__featured">
                    <div class="read-more__featured-media">
                        <img src="{{ data_get($opinionSidebarLead, 'image', '') }}" alt="{{ data_get($opinionSidebarLead, 'title', '') }}">
                    </div>
                    <div class="read-more__featured-title">
                        {{ data_get($opinionSidebarLead, 'title', '') }}
                    </div>
                </a>
                <div class="read-more__list">
                    @foreach($readMoreStories as $story)
                        <a href="{{ data_get($story, 'url', '#') }}" class="read-more__item">
                            <div class="read-more__item-title">{{ data_get($story, 'title', '') }}</div>
                            <div class="story-meta">{{ data_get($story, 'date', '') }}</div>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
</section>

    @push('scripts')
        <script>
            (() => {
                const widget = document.getElementById('weather-widget');

                if (!widget) {
                    return;
                }

                const url = widget.dataset.weatherUrl;
                const cityEl = widget.querySelector('[data-weather-city]');
                const iconEl = widget.querySelector('[data-weather-icon]');
                const tempEl = widget.querySelector('[data-weather-temperature]');
                const dayRows = Array.from(widget.querySelectorAll('[data-weather-day]'));
                const updateInterval = 10 * 60 * 1000;
                const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
                let refreshTimer = null;
                let watchId = null;
                let currentCoordsKey = null;

                const setText = (selector, value, root = widget) => {
                    const element = root.querySelector(selector);
                    if (element && typeof value === 'string') {
                        element.textContent = value;
                    }
                };

                const updateWidget = (payload) => {
                    if (!payload) {
                        return;
                    }

                    setText('[data-weather-city]', payload.city ?? '');
                    setText('[data-weather-icon]', payload.icon ?? '☀');
                    setText('[data-weather-temperature]', payload.temperature ?? '');

                    const days = Array.isArray(payload.days) ? payload.days : [];

                    dayRows.forEach((row, index) => {
                        const day = days[index];

                        if (!day) {
                            row.style.display = 'none';
                            return;
                        }

                        row.style.display = '';
                        setText('[data-weather-day-label]', day.label ?? '', row);
                        setText('[data-weather-day-high]', day.high ?? '', row);
                        setText('[data-weather-day-low]', day.low ?? '', row);
                    });
                };

                const buildUrl = (latitude = null, longitude = null) => {
                    if (!url) {
                        return null;
                    }

                    const requestUrl = new URL(url, window.location.origin);

                    if (latitude !== null && longitude !== null) {
                        requestUrl.searchParams.set('lat', latitude);
                        requestUrl.searchParams.set('lon', longitude);
                        requestUrl.searchParams.set('use_current_location', '1');
                    }

                    requestUrl.searchParams.set('timezone', browserTimezone);

                    return requestUrl.toString();
                };

                const loadWeather = async (latitude = null, longitude = null) => {
                    const requestUrl = buildUrl(latitude, longitude);

                    if (!requestUrl) {
                        return;
                    }

                    try {
                        const response = await fetch(requestUrl, {
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            cache: 'no-store',
                        });

                        if (!response.ok) {
                            return;
                        }

                        const json = await response.json();
                        updateWidget(json.data ?? json);
                    } catch (error) {
                        console.debug('Weather refresh failed', error);
                    }
                };

                const startPolling = (latitude = null, longitude = null) => {
                    if (refreshTimer) {
                        window.clearInterval(refreshTimer);
                    }

                    currentCoordsKey = latitude !== null && longitude !== null
                        ? `${latitude.toFixed(3)}:${longitude.toFixed(3)}`
                        : 'fallback';

                    loadWeather(latitude, longitude);
                    refreshTimer = window.setInterval(() => loadWeather(latitude, longitude), updateInterval);
                };

                const handleSuccess = (position) => {
                    const latitude = Number(position.coords.latitude);
                    const longitude = Number(position.coords.longitude);
                    const nextKey = `${latitude.toFixed(3)}:${longitude.toFixed(3)}`;

                    if (nextKey === currentCoordsKey) {
                        return;
                    }

                    startPolling(latitude, longitude);
                };

                const handleError = () => {
                    startPolling();
                };

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(handleSuccess, handleError, {
                        enableHighAccuracy: false,
                        timeout: 8000,
                        maximumAge: 300000,
                    });

                    if (typeof navigator.geolocation.watchPosition === 'function') {
                        watchId = navigator.geolocation.watchPosition(handleSuccess, handleError, {
                            enableHighAccuracy: false,
                            timeout: 8000,
                            maximumAge: 300000,
                        });
                    }
                } else {
                    handleError();
                }

                window.addEventListener('beforeunload', () => {
                    if (watchId !== null && navigator.geolocation) {
                        navigator.geolocation.clearWatch(watchId);
                    }

                    if (refreshTimer) {
                        window.clearInterval(refreshTimer);
                    }
                });
            })();
        </script>

        <script>
            (() => {
                const heroCard = document.querySelector('.hero-live-card');
                const iframe = document.getElementById('hero-live-tv-frame');
                const button = document.getElementById('hero-live-tv-sound');
                const playlistButtons = Array.from(document.querySelectorAll('[data-hero-video-index]'));
                const heroVideos = heroCard ? JSON.parse(heroCard.dataset.heroVideos || '[]') : [];
                const labels = {
                    unmute: (heroCard && heroCard.dataset.unmuteLabel) || 'Unmute',
                    mute: (heroCard && heroCard.dataset.muteLabel) || 'Mute',
                };

                if (!iframe || !button || !heroVideos.length) {
                    return;
                }

                let activeIndex = 0;

                const updatePlaylistUI = () => {
                    playlistButtons.forEach((btn) => {
                        const index = Number(btn.dataset.heroVideoIndex);
                        btn.classList.toggle('is-active', index === activeIndex);
                    });
                };

                const loadVideo = (index) => {
                    if (index < 0 || index >= heroVideos.length || index === activeIndex) {
                        return;
                    }

                    activeIndex = index;
                    iframe.src = heroVideos[activeIndex].video_url;
                    updatePlaylistUI();
                };

                const sendCommand = (command) => {
                    if (!iframe.contentWindow) {
                        return;
                    }

                    iframe.contentWindow.postMessage(JSON.stringify({
                        event: 'command',
                        func: command,
                        args: [],
                    }), '*');
                };

                const setMuted = (muted) => {
                    button.dataset.muted = muted ? 'true' : 'false';
                    button.setAttribute('aria-pressed', muted ? 'true' : 'false');
                    button.setAttribute('aria-label', muted ? labels.unmute : labels.mute);
                    button.querySelector('.hero-live-card__sound-icon').textContent = muted ? '🔇' : '🔊';
                    button.querySelector('.hero-live-card__sound-label').textContent = muted ? labels.unmute : labels.mute;
                };

                playlistButtons.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const index = Number(btn.dataset.heroVideoIndex);
                        loadVideo(index);
                    });
                });

                window.addEventListener('message', (event) => {
                    let data = event.data;

                    if (typeof data === 'string') {
                        try {
                            data = JSON.parse(data);
                        } catch (error) {
                            return;
                        }
                    }

                    if (!data || data.event !== 'onStateChange') {
                        return;
                    }

                    if (data.info === 0 && heroVideos.length > 1) {
                        const nextIndex = (activeIndex + 1) % heroVideos.length;
                        loadVideo(nextIndex);
                    }
                });

                button.addEventListener('click', () => {
                    const muted = button.dataset.muted === 'true';

                    if (muted) {
                        sendCommand('unMute');
                        setMuted(false);
                    } else {
                        sendCommand('mute');
                        setMuted(true);
                    }
                });

                if (iframe.addEventListener) {
                    iframe.addEventListener('load', () => {
                        sendCommand('mute');
                    });
                }

                updatePlaylistUI();
            })();
        </script>

        <script>
            (() => {
                const dialog = document.getElementById('video-dialog');
                const dialogFrame = document.getElementById('video-dialog-frame');
                const dialogTitle = document.getElementById('video-dialog-title');
                const openButtons = Array.from(document.querySelectorAll('[data-video-open]'));
                const closeButtons = Array.from(document.querySelectorAll('[data-video-close]'));

                if (!dialog || !dialogFrame || !dialogTitle || !openButtons.length) {
                    return;
                }

                const closeDialog = () => {
                    dialog.classList.remove('is-open');
                    dialog.setAttribute('aria-hidden', 'true');
                    dialogFrame.src = 'about:blank';
                    dialogTitle.textContent = '';
                    document.body.style.overflow = '';
                };

                const openDialog = (button) => {
                    const videoUrl = button.dataset.videoUrl || '';
                    const title = button.dataset.videoTitle || '';

                    if (!videoUrl) {
                        return;
                    }

                    dialogTitle.textContent = title;
                    dialogFrame.src = videoUrl;
                    dialog.classList.add('is-open');
                    dialog.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                };

                openButtons.forEach((button) => {
                    button.addEventListener('click', () => openDialog(button));
                });

                closeButtons.forEach((button) => {
                    button.addEventListener('click', closeDialog);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && dialog.classList.contains('is-open')) {
                        closeDialog();
                    }
                });
            })();
        </script>
    @endpush
@endsection
