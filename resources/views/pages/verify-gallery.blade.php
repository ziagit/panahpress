@extends('layouts.newspaper')

@section('content')
    @php
        $isRtl = app()->getLocale() === 'fa';
    @endphp

    <style>
        .gallery-page {
            --gallery-bg: #f6f8fb;
            --gallery-paper: #ffffff;
            --gallery-border: rgba(20, 28, 40, 0.12);
            --gallery-accent: #3f6cb5;
            background: linear-gradient(180deg, #ffffff 0%, var(--gallery-bg) 100%);
            min-height: 100vh;
            color: #1a1a1a;
            padding: 28px 0 42px;
        }

        .gallery-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 0 18px 44px;
        }

        .gallery-card {
            background: var(--gallery-paper);
            border: 1px solid var(--gallery-border);
            box-shadow: 0 14px 34px rgba(20, 28, 40, 0.05);
            overflow: hidden;
        }

        .gallery-hero {
            display: grid;
            gap: 10px;
            padding: 28px 22px 24px;
            border-bottom: 1px solid var(--gallery-border);
            background:
                radial-gradient(circle at 50% 30%, rgba(255,255,255,0.95), rgba(255,255,255,0.72) 42%, rgba(255,255,255,0.48) 68%, transparent 85%),
                linear-gradient(180deg, #fdfdfd 0%, #eef4fb 100%);
        }

        .gallery-hero-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .gallery-back {
            color: var(--gallery-accent);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .gallery-back::before {
            content: '←';
        }

        .gallery-count {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(26, 26, 26, 0.56);
        }

        .gallery-title {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 700;
        }

        .gallery-intro {
            margin: 0;
            max-width: 680px;
            color: rgba(26, 26, 26, 0.7);
            line-height: 1.55;
        }

        .verify-ltr {
            direction: ltr;
            unicode-bidi: plaintext;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            padding: 18px 18px 22px;
        }

        .gallery-item {
            border: 1px solid var(--gallery-border);
            border-radius: 8px;
            overflow: hidden;
            background: #f8faff;
            aspect-ratio: 1 / 1;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.78);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center top;
        }

        [dir="rtl"] .gallery-back::before {
            content: '→';
        }

        @media (max-width: 900px) {
            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .gallery-page {
                padding-top: 18px;
            }

            .gallery-shell {
                padding-inline: 12px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
                padding: 14px 14px 18px;
            }
        }
    </style>

    <section class="gallery-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="gallery-shell">
            <div class="gallery-card">
                <header class="gallery-hero">
                    <div class="gallery-hero-top">
                        <a class="gallery-back" href="{{ route('verify.show', ['locale' => $locale, 'verificationCard' => $card, 'securityCode' => $card->security_code]) }}">
                            {{ __('messages.verify_gallery_back') }}
                        </a>
                        <span class="gallery-count">{{ count($galleryImages) }} {{ __('messages.verify_gallery_photos') }}</span>
                    </div>

                    <div>
                        <h1 class="gallery-title">{{ __('messages.verify_gallery_page_title') }}</h1>
                        <p class="gallery-intro">{{ __('messages.verify_gallery_page_intro') }}</p>
                    </div>
                </header>

                <div class="gallery-grid">
                    @foreach($galleryImages as $image)
                        <article class="gallery-item">
                            <img src="{{ $image }}" alt="{{ $card->full_name }} gallery image {{ $loop->iteration }}">
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
