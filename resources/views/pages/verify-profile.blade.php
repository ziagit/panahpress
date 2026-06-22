@extends('layouts.newspaper')

@section('content')
    @php
        $isRtl = app()->getLocale() === 'fa';
        $issueDate = $card->issue_date?->translatedFormat('F j, Y');
        $expiryDate = $card->expiry_date?->translatedFormat('F j, Y');
        $birthDate = $card->birth_date?->translatedFormat('F j, Y');
        $photoUrl = $card->photo ? asset('storage/'.$card->photo) : asset('images/logo.png');
        $profileOrg = $card->profile_org ?: __('messages.verify_profile_org');
        $shortBio = $card->short_bio ?: __('messages.verify_short_bio_text', ['name' => $card->full_name]);
        $currentPosition = $card->current_position ?: $card->occupation;
        $field = $card->field ?: __('messages.verify_field_value');
        $location = $card->location ?: __('messages.verify_location_value');
        $aboutText = $card->about_text ?: __('messages.verify_about_text');
        $quoteText = $card->quote_text ?: __('messages.verify_quote_text');

        $achievements = collect(preg_split('/\r\n|\r|\n/', (string) ($card->achievements ?: '')))
            ->filter()
            ->values()
            ->all();

        if ($achievements === []) {
            $achievements = [
                __('messages.verify_achievement_1'),
                __('messages.verify_achievement_2'),
                __('messages.verify_achievement_3'),
                __('messages.verify_achievement_4'),
                __('messages.verify_achievement_5'),
            ];
        }

        $timeline = collect(preg_split('/\r\n|\r|\n/', (string) ($card->timeline ?: '')))
            ->filter()
            ->values()
            ->all();

        if ($timeline === []) {
            $timeline = [
                __('messages.verify_timeline_1'),
                __('messages.verify_timeline_2'),
                __('messages.verify_timeline_3'),
            ];
        }
    @endphp

    <style>
        .verify-profile-page {
            background: #eef8fd;
            color: #151515;
            padding: 52px 0 76px;
            min-height: 70vh;
            overflow: hidden;
            --profile-primary: #0a8cbf;
            --profile-primary-deep: #066787;
            --profile-primary-soft: rgba(10, 140, 191, 0.08);
            --profile-border: rgba(10, 140, 191, 0.16);
            --profile-surface: #f7fcff;
        }

        .verify-profile-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 12px;
        }

        .verify-profile-layout {
            display: grid;
            gap: 34px;
        }

        .verify-profile-hero {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .verify-profile-avatar {
            flex: 0 0 172px;
            width: 172px;
            aspect-ratio: 1;
            border-radius: 32px;
            overflow: hidden;
            background: #dff3fb;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
        }

        .verify-profile-copy {
            flex: 1 1 auto;
            min-width: 0;
        }

        .verify-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .verify-profile-name {
            margin: 0;
            font-size: clamp(2.3rem, 5vw, 3.85rem);
            line-height: 0.96;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #141414;
        }

        .verify-profile-subtitle {
            margin-top: 8px;
            display: grid;
            gap: 2px;
            font-size: clamp(1.02rem, 1.6vw, 1.38rem);
            line-height: 1.22;
            color: #1c1c1c;
        }

        .verify-profile-subtitle .muted {
            color: rgba(28, 28, 28, 0.9);
        }

        .verify-profile-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 1.02rem;
            font-weight: 700;
            color: var(--profile-primary-deep);
            width: fit-content;
        }

        .verify-profile-badge svg {
            width: 22px;
            height: 22px;
            flex: 0 0 auto;
        }

        .verify-section {
            display: grid;
            gap: 10px;
        }

        .verify-section h2 {
            margin: 0;
            font-size: clamp(1.55rem, 2.4vw, 2rem);
            line-height: 1.12;
            font-weight: 800;
            color: #1a1a1a;
        }

        .verify-section p {
            margin: 0;
            font-size: 1.03rem;
            line-height: 1.5;
            color: #1e1e1e;
        }

        .verify-key-grid {
            border-top: 1px solid var(--profile-border);
            border-left: 1px solid var(--profile-border);
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .verify-key-item {
            padding: 13px 14px;
            border-right: 1px solid var(--profile-border);
            border-bottom: 1px solid var(--profile-border);
            font-size: 1.01rem;
            line-height: 1.35;
            background: var(--profile-surface);
        }

        .verify-key-item strong {
            font-weight: 800;
        }

        .verify-list {
            margin: 0;
            padding-inline-start: 22px;
            display: grid;
            gap: 8px;
            font-size: 1.02rem;
            line-height: 1.4;
        }

        .verify-timeline {
            display: grid;
            gap: 6px;
            font-size: 1.02rem;
            line-height: 1.4;
        }

        .verify-quote {
            padding: 18px 20px;
            border-radius: 12px;
            background: #f2fbff;
            box-shadow: inset 0 0 0 1px var(--profile-border);
            font-size: 1.02rem;
            line-height: 1.5;
        }

        .verify-meta {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            color: rgba(28, 28, 28, 0.78);
            font-size: 0.94rem;
        }

        .verify-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .verify-ltr {
            direction: ltr;
            unicode-bidi: plaintext;
        }

        .verify-separator {
            height: 1px;
            background: rgba(10, 140, 191, 0.12);
        }

        .verify-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border: 1px solid var(--profile-border);
            border-radius: 999px;
            color: var(--profile-primary-deep);
            font-weight: 700;
            width: fit-content;
            margin-bottom: 8px;
            background: #f7fcff;
        }

        .verify-profile-divider {
            height: 1px;
            background: rgba(10, 140, 191, 0.14);
        }

        [dir="rtl"] .verify-profile-hero {
            flex-direction: row;
        }

        [dir="rtl"] .verify-profile-name,
        [dir="rtl"] .verify-profile-subtitle,
        [dir="rtl"] .verify-section,
        [dir="rtl"] .verify-meta,
        [dir="rtl"] .verify-key-item,
        [dir="rtl"] .verify-list,
        [dir="rtl"] .verify-timeline,
        [dir="rtl"] .verify-quote {
            text-align: right;
        }

        [dir="rtl"] .verify-list {
            padding-inline-start: 0;
            padding-inline-end: 22px;
        }

        [dir="rtl"] .verify-key-grid {
            border-left: 0;
            border-right: 1px solid var(--profile-border);
        }

        [dir="rtl"] .verify-key-item {
            border-right: 0;
            border-left: 1px solid var(--profile-border);
        }

        [dir="rtl"] .verify-profile-copy {
            text-align: left;
        }

        [dir="rtl"] .verify-profile-badge {
            justify-content: flex-start;
        }

        [dir="rtl"] .verify-meta {
            gap: 8px 12px;
        }

        [dir="rtl"] .verify-meta span {
            direction: rtl;
        }

        @media (max-width: 720px) {
            .verify-profile-page {
                padding-top: 24px;
                padding-bottom: 54px;
            }

            .verify-profile-wrap {
                padding: 0 10px;
            }

            .verify-profile-hero {
                flex-direction: column;
                gap: 16px;
            }

            .verify-profile-avatar {
                width: 156px;
                border-radius: 30px;
            }

            .verify-key-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="verify-profile-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="verify-profile-wrap">
            <a class="verify-back" href="{{ route('verify', ['locale' => $locale]) }}">{{ __('messages.verify_lookup') }}</a>

            <div class="verify-profile-layout">
                <header class="verify-profile-hero">
                    <div class="verify-profile-avatar">
                        <img src="{{ $photoUrl }}" alt="{{ $card->full_name }}">
                    </div>

                    <div class="verify-profile-copy">
                        <h1 class="verify-profile-name">{{ $card->full_name }}</h1>
                        <div class="verify-profile-subtitle">
                            <span>{{ $card->occupation }}</span>
                            <span class="muted">{{ $profileOrg }}</span>
                        </div>
                        <div class="verify-profile-badge" aria-label="{{ __('messages.verify_status_verified') }}">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 2.75a9.25 9.25 0 1 0 9.25 9.25A9.26 9.26 0 0 0 12 2.75Z" fill="var(--profile-primary-deep)"/>
                                <path d="m9.35 12.15 1.78 1.77 3.98-4.14" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{ __('messages.verify_status_verified') }}</span>
                        </div>
                    </div>
                </header>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_short_bio') }}</h2>
                    <p>{{ $shortBio }}</p>
                </section>

                <div class="verify-profile-divider"></div>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_key_information') }}</h2>
                    <div class="verify-key-grid">
                        <div class="verify-key-item">
                            <strong>{{ __('messages.verify_current_position') }}:</strong> {{ $currentPosition }}
                        </div>
                        <div class="verify-key-item">
                            <strong>{{ __('messages.verify_field') }}:</strong> {{ $field }}
                        </div>
                        <div class="verify-key-item">
                            <strong>{{ __('messages.verify_location') }}:</strong> {{ $location }}
                        </div>
                        <div class="verify-key-item">
                            <strong>{{ __('messages.verify_status_label') }}:</strong> {{ __('messages.verify_status_verified') }}
                        </div>
                    </div>
                    <div class="verify-meta">
                        <span><strong>{{ __('messages.card_id') }}:</strong> <span class="verify-ltr">{{ $card->code }}</span></span>
                        <span><strong>{{ __('messages.security_code') }}:</strong> <span class="verify-ltr">{{ $card->security_code }}</span></span>
                        <span><strong>{{ __('messages.birth_date') }}:</strong> <span class="verify-ltr">{{ $birthDate }}</span></span>
                    </div>
                </section>

                <div class="verify-separator"></div>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_about_section') }}</h2>
                    <p>{{ $aboutText }}</p>
                </section>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_achievements') }}</h2>
                    <ul class="verify-list">
                        @foreach ($achievements as $achievement)
                            <li>{{ $achievement }}</li>
                        @endforeach
                    </ul>
                </section>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_timeline') }} <span style="font-weight:400; color: rgba(26, 26, 26, 0.55);">{{ __('messages.verify_timeline_optional') }}</span></h2>
                    <div class="verify-timeline">
                        @foreach ($timeline as $item)
                            <div>{{ $item }}</div>
                        @endforeach
                    </div>
                </section>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_quote_section') }}</h2>
                    <div class="verify-quote">
                        {{ $quoteText }}
                    </div>
                </section>

                <section class="verify-section">
                    <h2>{{ __('messages.verify_profile_record') }}</h2>
                    <div class="verify-meta">
                        <span><strong>{{ __('messages.issue_date') }}:</strong> <span class="verify-ltr">{{ $issueDate }}</span></span>
                        <span><strong>{{ __('messages.expiry_date') }}:</strong> <span class="verify-ltr">{{ $expiryDate }}</span></span>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
