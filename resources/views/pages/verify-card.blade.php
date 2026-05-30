@extends('layouts.newspaper')

@section('content')
    @php
        $issueDate = $card->issue_date?->translatedFormat('F Y');
        $expiryDate = $card->expiry_date?->translatedFormat('F Y');
        $photoUrl = $card->photo ? asset('storage/'.$card->photo) : asset('images/logo.png');
    @endphp

    <style>
        .verify-result-shell {
            padding-top: 28px;
            padding-bottom: 56px;
        }

        .verify-result-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .verify-result-head h1 {
            margin: 0.3rem 0 0;
            font-family: var(--serif);
            font-size: clamp(2rem, 4vw, 3.25rem);
            line-height: 1.04;
            color: #11161c;
        }

        .verify-result-head p {
            margin: 0.55rem 0 0;
            color: #64748b;
            font-size: 0.98rem;
        }

        .verify-result-head .return-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            border: 1px solid var(--accent);
            border-radius: 3px;
            color: var(--accent);
            font-weight: 700;
            white-space: nowrap;
        }

        .verify-result-head .return-link:hover {
            background: var(--accent);
            color: #fff;
        }

        .press-card-stage {
            max-width: 720px;
            margin: 0 auto;
        }

        .press-card {
            position: relative;
            overflow: hidden;
            background: #0f96c5;
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(8, 48, 78, 0.18);
            padding: 26px 28px 22px;
        }

        .press-card::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 39%;
            height: 210px;
            transform: translateY(-50%);
            background: #fff;
        }

        .press-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.16), rgba(255,255,255,0) 13%),
                radial-gradient(circle at 84% 10%, rgba(255,255,255,0.12) 0 1px, transparent 1px),
                radial-gradient(circle at 88% 13%, rgba(255,255,255,0.12) 0 1px, transparent 1px);
            pointer-events: none;
        }

        .press-card__top {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            min-height: 150px;
        }

        .press-card__title {
            margin-top: 6px;
            font-size: clamp(3.6rem, 11vw, 6.2rem);
            font-weight: 900;
            letter-spacing: 0.08em;
            line-height: 1;
            text-transform: uppercase;
            color: #fff;
        }

        .press-card__portrait {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            margin-top: -72px;
            margin-bottom: 14px;
        }

        .press-card__portrait-frame {
            width: min(52vw, 320px);
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 50%;
            background: #fff;
            border: 8px solid #fff;
            box-shadow: 0 14px 30px rgba(4, 26, 40, 0.22);
        }

        .press-card__portrait-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .press-card__content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff;
        }

        .press-card__name {
            font-size: clamp(1.7rem, 3.8vw, 3.05rem);
            font-weight: 800;
            letter-spacing: 0.06em;
            line-height: 1.1;
            text-transform: capitalize;
        }

        .press-card__occupation {
            margin-top: 8px;
            font-size: clamp(1rem, 2.3vw, 1.55rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .press-card__id {
            margin-top: 8px;
            font-size: clamp(1.6rem, 3.6vw, 2.8rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .press-card__barcode {
            width: min(100%, 430px);
            height: 62px;
            margin: 18px auto 0;
            border-top: 4px solid #111;
            border-bottom: 4px solid #111;
            background:
                repeating-linear-gradient(
                    90deg,
                    #111 0 2px,
                    #fff 2px 3px,
                    #111 3px 5px,
                    #fff 5px 7px,
                    #111 7px 11px,
                    #fff 11px 15px,
                    #111 15px 17px
                );
        }

        .press-card__footer {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 124px;
            gap: 18px;
            align-items: end;
            margin-top: 16px;
            text-align: left;
        }

        [dir="rtl"] .press-card__footer {
            text-align: right;
        }

        .press-card__dates {
            display: grid;
            gap: 8px;
            font-size: clamp(0.96rem, 1.8vw, 1.2rem);
            font-weight: 700;
            line-height: 1.4;
        }

        .press-card__dates strong {
            font-weight: 800;
        }

        .press-card__brand {
            width: 124px;
            height: 124px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }

        .press-card__brand img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .press-card__hint {
            margin-top: 16px;
            color: rgba(255,255,255,.9);
            font-size: 0.92rem;
            line-height: 1.6;
            text-align: center;
        }

        @media (max-width: 720px) {
            .verify-result-head {
                align-items: flex-start;
            }

            .press-card {
                padding: 20px 18px 18px;
                border-radius: 16px;
            }

            .press-card::before {
                top: 38%;
                height: 180px;
            }

            .press-card__top {
                min-height: 122px;
            }

            .press-card__portrait {
                margin-top: -62px;
            }

            .press-card__footer {
                grid-template-columns: 1fr;
                justify-items: center;
                text-align: center;
            }
        }
    </style>

    <section class="news-shell verify-result-shell">
        <div class="verify-result-head">
            <div>
                <span class="kicker">{{ __('messages.verify_found_title') }}</span>
                <h1>{{ $card->full_name }}</h1>
                <p>{{ $card->occupation }} · {{ $card->code }}</p>
            </div>
            <a class="return-link" href="{{ route('verify', ['locale' => $locale]) }}">{{ __('messages.verify_lookup') }}</a>
        </div>

        <div class="press-card-stage">
            <article class="press-card" aria-label="{{ $card->full_name }}">
                <div class="press-card__top">
                    <div class="press-card__title">PRESS</div>
                </div>

                <div class="press-card__portrait">
                    <div class="press-card__portrait-frame">
                        <img src="{{ $photoUrl }}" alt="{{ $card->full_name }}">
                    </div>
                </div>

                <div class="press-card__content">
                    <div class="press-card__name">{{ $card->full_name }}</div>
                    <div class="press-card__occupation">{{ $card->occupation }}</div>
                    <div class="press-card__id">ID:{{ $card->code }}</div>

                    <div class="press-card__barcode" aria-hidden="true"></div>

                    <div class="press-card__footer">
                        <div class="press-card__dates">
                            <div><strong>{{ __('messages.issue_date') }}:</strong> {{ $issueDate }}</div>
                            <div><strong>{{ __('messages.expiry_date') }}:</strong> {{ $expiryDate }}</div>
                        </div>
                        <div class="press-card__brand" aria-hidden="true">
                            <img src="{{ asset('images/logo.png') }}" alt="">
                        </div>
                    </div>

                    <p class="press-card__hint">{{ __('messages.verify_found_intro') }}</p>
                </div>
            </article>
        </div>
    </section>
@endsection
