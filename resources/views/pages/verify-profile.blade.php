@extends('layouts.newspaper')

@section('content')
    @php
        $isRtl = app()->getLocale() === 'fa';

        $photoUrl = $card->photo ? asset('storage/'.$card->photo) : asset('images/logo.png');
        $issueDate = $card->issue_date?->translatedFormat('F j, Y');
        $expiryDate = $card->expiry_date?->translatedFormat('F j, Y');
        $birthDate = $card->birth_date?->translatedFormat('F j, Y');

        $currentPosition = $card->current_position ?: $card->occupation;
        $location = $card->location ?: __('messages.verify_location_value');
        $shortBio = $card->short_bio ?: '';

        $languagesList = collect(preg_split('/,|\r\n|\r|\n/', (string) ($card->languages ?: '')))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $expertiseItems = [
            __('messages.verify_expertise_1'),
            __('messages.verify_expertise_2'),
            __('messages.verify_expertise_3'),
            __('messages.verify_expertise_4'),
            __('messages.verify_expertise_5'),
            __('messages.verify_expertise_6'),
        ];

        $expertiseList = collect(preg_split('/,|\r\n|\r|\n/', (string) ($card->expertise ?: '')))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($expertiseList === []) {
            $expertiseList = $expertiseItems;
        }

        $phone = $card->phone ?: '';
        $email = $card->email ?: '';
    @endphp

    <style>
        .portfolio-page {
            --portfolio-bg: #f6f8fb;
            --portfolio-paper: #ffffff;
            --portfolio-ink: #1a1a1a;
            --portfolio-muted: rgba(26, 26, 26, 0.66);
            --portfolio-border: rgba(20, 28, 40, 0.12);
            --portfolio-accent: #3f6cb5;
            --portfolio-accent-soft: rgba(63, 108, 181, 0.12);
            background: linear-gradient(180deg, #ffffff 0%, var(--portfolio-bg) 100%);
            color: var(--portfolio-ink);
            padding: 28px 0 0;
            min-height: 100vh;
            overflow: hidden;
        }

        .portfolio-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 0 18px 44px;
        }

        .portfolio-card {
            background: var(--portfolio-paper);
            border: 1px solid var(--portfolio-border);
            box-shadow: 0 14px 34px rgba(20, 28, 40, 0.05);
        }

        .portfolio-hero {
            display: grid;
            place-items: center;
            text-align: center;
            min-height: 380px;
            padding: 72px 30px 58px;
            gap: 18px;
            background:
                radial-gradient(circle at 50% 28%, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.82) 34%, rgba(255, 255, 255, 0.56) 58%, transparent 78%),
                linear-gradient(180deg, #fdfdfd 0%, #eff4fb 100%);
            border-bottom: 1px solid var(--portfolio-border);
        }

        .portfolio-avatar {
            width: 144px;
            height: 144px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(63, 108, 181, 0.18), rgba(63, 108, 181, 0.1));
            display: grid;
            place-items: center;
            box-shadow: inset 0 0 0 1px rgba(63, 108, 181, 0.14);
            overflow: hidden;
        }

        .portfolio-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .portfolio-kicker {
            margin: 0;
            font-size: 0.9rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--portfolio-accent);
            font-weight: 700;
        }

        .portfolio-name {
            margin: 0;
            font-size: clamp(2.15rem, 4.8vw, 3.35rem);
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 700;
        }

        .portfolio-subtitle {
            margin: 0;
            font-size: 1.02rem;
            line-height: 1.45;
            color: var(--portfolio-muted);
            max-width: 620px;
        }

        .portfolio-section {
            padding: 36px 32px 38px;
            border-top: 1px solid var(--portfolio-border);
            background: #ffffff;
        }

        .portfolio-section:first-child {
            border-top: 0;
        }

        .portfolio-section-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .portfolio-section-head::before {
            content: '';
            width: 2px;
            height: 18px;
            border-radius: 999px;
            background: var(--portfolio-accent);
            opacity: 0.42;
            flex: 0 0 auto;
        }

        .portfolio-section-head h2 {
            margin: 0;
            font-size: 1.05rem;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .portfolio-body {
            font-size: 1.02rem;
            line-height: 1.75;
            padding-top: 2px;
            color: rgba(26, 26, 26, 0.92);
            white-space: pre-line;
        }

        .portfolio-meta {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px 16px;
            color: rgba(26, 26, 26, 0.68);
            font-size: 0.92rem;
        }

        .portfolio-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .verify-ltr {
            direction: ltr;
            unicode-bidi: plaintext;
        }

        .portfolio-gallery {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .portfolio-tile {
            min-height: 118px;
            border-radius: 10px;
            border: 1px solid rgba(20, 28, 40, 0.12);
            background: #f7f9fc;
            text-decoration: none;
            display: grid;
            place-items: stretch;
            text-align: center;
            padding: 0;
            overflow: hidden;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.78);
            position: relative;
            color: inherit;
            cursor: pointer;
        }

        .portfolio-tile--more {
            background: linear-gradient(180deg, #f7f9fc, #eef3f9);
        }

        .portfolio-gallery-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center top;
        }

        .portfolio-tile-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.22), rgba(247, 249, 252, 0.88));
        }

        .portfolio-tile--more .portfolio-tile-overlay {
            gap: 6px;
            padding: 16px;
        }

        .portfolio-tile .icon {
            width: 30px;
            height: 30px;
            color: rgba(26, 26, 26, 0.74);
        }

        .portfolio-tile .icon svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .portfolio-tile .value {
            display: block;
            font-size: 1.9rem;
            line-height: 1;
            font-weight: 800;
            color: rgba(34, 48, 90, 0.82);
        }

        .portfolio-tile .label {
            display: block;
            margin-top: 8px;
            font-size: 0.88rem;
            line-height: 1.2;
            color: rgba(26, 26, 26, 0.9);
            font-weight: 600;
            text-align: center;
        }

        .portfolio-tile__caption {
            padding: 12px 10px 11px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
        }

        .portfolio-expertise {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .portfolio-skill {
            min-height: 0;
            border-radius: 999px;
            border: 1px solid rgba(20, 28, 40, 0.12);
            background: linear-gradient(180deg, #ffffff, #f7f9fc);
            display: inline-flex;
            align-items: center;
            text-align: start;
            padding: 13px 18px;
            width: fit-content;
        }

        .portfolio-skill strong {
            display: block;
            font-size: 0.92rem;
            line-height: 1.25;
            font-weight: 700;
        }

        .portfolio-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 44px;
            align-items: baseline;
            padding-top: 8px;
        }

        .portfolio-tag {
            text-align: start;
        }

        .portfolio-tag .language {
            display: block;
            font-size: 0.74rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(26, 26, 26, 0.52);
            margin-bottom: 6px;
        }

        .portfolio-tag strong {
            display: block;
            font-size: 0.95rem;
            line-height: 1.25;
            font-weight: 700;
        }

        .portfolio-contact {
            display: grid;
            gap: 28px;
        }

        .portfolio-contact-card {
            max-width: 405px;
            padding: 0;
        }

        .portfolio-contact-list {
            display: grid;
            gap: 26px;
        }

        .portfolio-contact-row {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .portfolio-contact-icon {
            width: 22px;
            height: 22px;
            color: rgba(63, 108, 181, 0.26);
            flex: 0 0 auto;
            margin-top: 2px;
        }

        .portfolio-contact-icon svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .portfolio-contact-text {
            display: grid;
            gap: 4px;
        }

        .portfolio-contact-text .label {
            display: block;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(26, 26, 26, 0.52);
        }

        .portfolio-contact-card a,
        .portfolio-contact-card span {
            color: var(--portfolio-accent);
            font-weight: 700;
            font-size: 0.96rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .portfolio-footer-meta {
            margin-top: 36px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px 18px;
            color: rgba(26, 26, 26, 0.64);
            font-size: 0.9rem;
        }

        .portfolio-footer-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .portfolio-footer {
            text-align: center;
            padding: 30px 14px 38px;
            font-size: 0.78rem;
            color: rgba(26, 26, 26, 0.52);
            letter-spacing: 0.03em;
        }

        @media (max-width: 900px) {
            .portfolio-gallery,
            .portfolio-contact {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .portfolio-page {
                padding-top: 18px;
            }

            .portfolio-shell {
                padding-inline: 12px;
            }

            .portfolio-hero {
                min-height: 320px;
                padding: 54px 18px 44px;
            }

            .portfolio-section {
                padding: 34px 20px 36px;
            }

            .portfolio-gallery,
            .portfolio-contact {
                grid-template-columns: 1fr;
            }

            .portfolio-gallery > :not(.portfolio-tile--more) {
                display: none;
            }

            .portfolio-gallery .portfolio-tile--more {
                min-height: 160px;
                width: 100%;
            }

            .portfolio-avatar {
                width: 120px;
                height: 120px;
            }
        }
    </style>

    <section class="portfolio-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="portfolio-shell">
            <div class="portfolio-card">
                <header class="portfolio-hero">
                    <div class="portfolio-avatar" aria-hidden="true">
                        <img src="{{ $photoUrl }}" alt="{{ $card->full_name }}">
                    </div>

                    <div>
                        <p class="portfolio-kicker">{{ $currentPosition }}</p>
                        <h1 class="portfolio-name">{{ $card->full_name }}</h1>
                        <p class="portfolio-subtitle">
                            {{ $location }} · {{ $card->occupation }}
                        </p>
                        <div class="portfolio-meta">
                            <span><strong>{{ __('messages.card_id') }}:</strong> <span class="verify-ltr">{{ $card->code }}</span></span>
                            @if($birthDate)
                                <span><strong>{{ __('messages.birth_date') }}:</strong> <span class="verify-ltr">{{ $birthDate }}</span></span>
                            @endif
                        </div>
                    </div>
                </header>

                <section class="portfolio-section">
                    <div class="portfolio-section-head">
                        <h2>{{ __('messages.verify_about_portfolio') }}</h2>
                    </div>
                    <div class="portfolio-body">
                        {{ $shortBio }}
                    </div>
                </section>

                <section class="portfolio-section">
                    <div class="portfolio-section-head">
                        <h2>{{ __('messages.verify_gallery') }}</h2>
                    </div>

                    <div class="portfolio-gallery">
                        @foreach(array_slice($galleryImages ?? [], 0, 3) as $image)
                            <article class="portfolio-tile" aria-label="{{ __('messages.verify_gallery') }} {{ $loop->iteration }}">
                                <img class="portfolio-gallery-image" src="{{ $image }}" alt="{{ $card->full_name }} gallery image {{ $loop->iteration }}">
                            </article>
                        @endforeach

                        <a
                            class="portfolio-tile portfolio-tile--more"
                            href="{{ route('verify.gallery', ['locale' => $locale, 'verificationCard' => $card, 'securityCode' => $card->security_code]) }}"
                            aria-label="{{ __('messages.verify_view_all_photos') }}"
                        >
                            <img class="portfolio-gallery-image" src="{{ $galleryImages[3] ?? $photoUrl }}" alt="{{ $card->full_name }} gallery more">
                            <span class="portfolio-tile-overlay">
                                <span class="value">+{{ max(count($galleryImages ?? []) - 3, 1) }}</span>
                                <span class="label">{{ __('messages.verify_view_all_photos') }}</span>
                            </span>
                        </a>
                    </div>
                </section>

                <section class="portfolio-section">
                    <div class="portfolio-section-head">
                        <h2>{{ __('messages.verify_expertise') }}</h2>
                    </div>

                    <div class="portfolio-expertise">
                        @foreach($expertiseList as $expertise)
                            <article class="portfolio-skill">
                                <strong>{{ $expertise }}</strong>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="portfolio-section">
                    <div class="portfolio-section-head">
                        <h2>{{ __('messages.verify_languages') }}</h2>
                    </div>

                    <div class="portfolio-tags">
                        @foreach($languagesList as $language)
                            <article class="portfolio-tag">
                                <span class="language">{{ $language }}</span>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="portfolio-section">
                    <div class="portfolio-section-head">
                        <h2>{{ __('messages.verify_contact') }}</h2>
                    </div>

                    <div class="portfolio-contact">
                        @if($email !== '')
                            <article class="portfolio-contact-card">
                                <div class="portfolio-contact-list">
                                    <div class="portfolio-contact-row">
                                        <span class="portfolio-contact-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                                <path d="m4 7 8 6 8-6" />
                                            </svg>
                                        </span>
                                        <div class="portfolio-contact-text">
                                            <span class="label">{{ __('messages.contact_email_label') }}</span>
                                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif

                        @if($phone !== '')
                            <article class="portfolio-contact-card">
                                <div class="portfolio-contact-list">
                                    <div class="portfolio-contact-row">
                                        <span class="portfolio-contact-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.82 19.82 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.82 19.82 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.92.32 1.82.59 2.68a2 2 0 0 1-.45 2.11L8 9.76a16 16 0 0 0 6.24 6.24l1.25-1.25a2 2 0 0 1 2.11-.45c.86.27 1.76.47 2.68.59A2 2 0 0 1 22 16.92Z" />
                                            </svg>
                                        </span>
                                        <div class="portfolio-contact-text">
                                            <span class="label">{{ __('messages.contact_phone_label') }}</span>
                                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endif
                    </div>

                    <div class="portfolio-footer-meta">
                        @if($issueDate)
                            <span><strong>{{ __('messages.issue_date') }}:</strong> <span class="verify-ltr">{{ $issueDate }}</span></span>
                        @endif
                        @if($expiryDate)
                            <span><strong>{{ __('messages.expiry_date') }}:</strong> <span class="verify-ltr">{{ $expiryDate }}</span></span>
                        @endif
                    </div>
                </section>
            </div>

            <div class="portfolio-footer">
                {{ __('messages.verify_portfolio_footer') }}
            </div>
        </div>
    </section>
@endsection
