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
            font-size: clamp(1.55rem, 2.9vw, 2rem);
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

        .gallery-item button {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            border: 0;
            background: none;
            cursor: zoom-in;
        }

        .gallery-item button:focus-visible {
            outline: 3px solid var(--gallery-accent);
            outline-offset: -3px;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center top;
            transition: transform 0.25s ease;
        }

        .gallery-item button:hover img {
            transform: scale(1.03);
        }

        .gallery-lightbox {
            position: fixed;
            inset: 0;
            width: fit-content;
            min-width: min(280px, calc(100vw - 32px));
            max-width: calc(100vw - 32px);
            max-height: calc(100dvh - 32px);
            margin: auto;
            padding: 14px 14px 12px;
            border: 1px solid var(--gallery-border);
            border-radius: 12px;
            background: var(--gallery-paper);
            color: #1a1a1a;
            box-shadow: 0 24px 60px rgba(20, 28, 40, 0.22);
            overflow: visible;
        }

        .gallery-lightbox::backdrop {
            background: rgba(20, 28, 40, 0.28);
        }

        .gallery-lightbox-figure {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .gallery-lightbox-figure img {
            display: block;
            width: auto;
            height: auto;
            max-width: calc(100vw - 60px);
            max-height: calc(100dvh - 110px);
            border-radius: 8px;
        }

        .gallery-lightbox-counter {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: rgba(26, 26, 26, 0.56);
        }

        .gallery-lightbox-btn {
            position: absolute;
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border: 1px solid var(--gallery-border);
            border-radius: 50%;
            background: var(--gallery-paper);
            color: #1a1a1a;
            box-shadow: 0 6px 16px rgba(20, 28, 40, 0.14);
            cursor: pointer;
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .gallery-lightbox-btn:hover,
        .gallery-lightbox-btn:focus-visible {
            color: var(--gallery-accent);
            border-color: var(--gallery-accent);
            outline: none;
        }

        .gallery-lightbox-close {
            top: -14px;
            inset-inline-end: -14px;
        }

        .gallery-lightbox-prev,
        .gallery-lightbox-next {
            top: calc(50% - 14px);
            transform: translateY(-50%);
        }

        .gallery-lightbox-prev {
            inset-inline-start: 24px;
        }

        .gallery-lightbox-next {
            inset-inline-end: 24px;
        }

        .gallery-lightbox-btn svg {
            width: 20px;
            height: 20px;
        }

        [dir="rtl"] .gallery-lightbox-prev svg,
        [dir="rtl"] .gallery-lightbox-next svg {
            transform: scaleX(-1);
        }

        .gallery-lightbox[data-single] .gallery-lightbox-prev,
        .gallery-lightbox[data-single] .gallery-lightbox-next,
        .gallery-lightbox[data-single] .gallery-lightbox-counter {
            display: none;
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

            .gallery-lightbox {
                padding: 10px 10px 8px;
            }

            .gallery-lightbox-figure img {
                max-width: calc(100vw - 52px);
            }

            .gallery-lightbox-close {
                top: -12px;
                inset-inline-end: -8px;
            }

            .gallery-lightbox-prev {
                inset-inline-start: 16px;
            }

            .gallery-lightbox-next {
                inset-inline-end: 16px;
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
                            <button type="button" data-gallery-index="{{ $loop->index }}" aria-label="{{ __('messages.verify_gallery_open_photo', ['number' => $loop->iteration]) }}">
                                <img src="{{ $image }}" alt="{{ $card->full_name }} gallery image {{ $loop->iteration }}" loading="lazy">
                            </button>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>

        <dialog class="gallery-lightbox" id="gallery-lightbox" aria-label="{{ __('messages.verify_gallery_page_title') }}" @if(count($galleryImages) < 2) data-single @endif>
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-close" data-lightbox-close aria-label="{{ __('messages.close') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-prev" data-lightbox-step="-1" aria-label="{{ __('messages.previous') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
            <figure class="gallery-lightbox-figure">
                <img src="" alt="">
                <figcaption class="gallery-lightbox-counter verify-ltr"></figcaption>
            </figure>
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-next" data-lightbox-step="1" aria-label="{{ __('messages.next') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
        </dialog>
    </section>

    <script>
        (() => {
            const lightbox = document.getElementById('gallery-lightbox');
            const triggers = Array.from(document.querySelectorAll('[data-gallery-index]'));

            if (!lightbox || !triggers.length) {
                return;
            }

            const image = lightbox.querySelector('img');
            const counter = lightbox.querySelector('.gallery-lightbox-counter');
            const isRtl = document.documentElement.dir === 'rtl' || lightbox.closest('[dir="rtl"]') !== null;
            let current = 0;

            const show = (index) => {
                current = (index + triggers.length) % triggers.length;
                const source = triggers[current].querySelector('img');
                image.src = source.currentSrc || source.src;
                image.alt = source.alt;
                counter.textContent = (current + 1) + ' / ' + triggers.length;
            };

            triggers.forEach((trigger, index) => {
                trigger.addEventListener('click', () => {
                    show(index);
                    lightbox.showModal();
                });
            });

            lightbox.querySelectorAll('[data-lightbox-step]').forEach((button) => {
                button.addEventListener('click', () => show(current + Number(button.dataset.lightboxStep)));
            });

            lightbox.querySelector('[data-lightbox-close]').addEventListener('click', () => lightbox.close());

            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    lightbox.close();
                }
            });

            lightbox.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                    const forward = event.key === 'ArrowRight' ? !isRtl : isRtl;
                    show(current + (forward ? 1 : -1));
                    event.preventDefault();
                }
            });

            lightbox.addEventListener('close', () => {
                triggers[current].focus();
            });
        })();
    </script>
@endsection
