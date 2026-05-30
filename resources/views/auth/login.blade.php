@extends('layouts.newspaper')

@section('content')
    <style>
        .login-page {
            padding-top: 34px;
            padding-bottom: 56px;
            min-height: 68vh;
        }

        .login-shell {
            max-width: 920px;
            margin: 0 auto;
            border: 1px solid var(--line);
            box-shadow: 0 18px 38px rgba(10, 30, 50, 0.08);
            overflow: hidden;
            background: #fff;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
        }

        .login-visual {
            background:
                radial-gradient(circle at 18% 18%, rgba(255,255,255,0.18) 0 2px, transparent 2px),
                linear-gradient(180deg, #0d93c5 0%, #0a79a7 100%);
            color: #fff;
            padding: 42px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
            min-height: 100%;
        }

        .login-visual__logo {
            width: 92px;
            height: 92px;
            object-fit: contain;
        }

        .login-visual h1 {
            margin: 0;
            font-family: var(--serif);
            font-size: clamp(2.2rem, 4vw, 3.5rem);
            line-height: 1.03;
        }

        .login-visual p {
            margin: 0;
            max-width: 28rem;
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255,255,255,.92);
        }

        .login-visual__notes {
            display: grid;
            gap: 10px;
        }

        .login-visual__notes span {
            display: inline-flex;
            width: fit-content;
            padding: 8px 12px;
            background: rgba(255,255,255,.12);
            border-radius: 999px;
            font-size: 0.88rem;
            letter-spacing: .02em;
        }

        .login-form {
            padding: 42px 38px;
            display: grid;
            align-content: center;
        }

        .login-form__inner {
            max-width: 420px;
        }

        .login-form__intro {
            display: grid;
            gap: 8px;
            margin-bottom: 26px;
        }

        .login-form__intro h2 {
            margin: 0;
            font-family: var(--serif);
            font-size: clamp(1.8rem, 2.6vw, 2.5rem);
            line-height: 1.08;
            color: #11161c;
        }

        .login-form__intro p {
            margin: 0;
            color: #475569;
            line-height: 1.7;
        }

        .login-form .form-field {
            margin-bottom: 16px;
        }

        .login-form .form-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #334155;
        }

        .login-form .form-field input {
            width: 100%;
            min-height: 48px;
            padding: 0 15px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .login-form .form-field input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(12, 136, 189, 0.1);
        }

        .login-form__actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 22px;
        }

        .login-form__actions .button {
            min-height: 44px;
            padding-inline: 20px;
        }

        .login-error {
            margin-bottom: 18px;
            padding: 12px 14px;
            background: #fff4f4;
            border: 1px solid #f4c5c5;
            color: #b42318;
            font-size: 0.94rem;
            line-height: 1.6;
        }

        @media (max-width: 880px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-visual {
                padding: 30px 26px;
            }

            .login-form {
                padding: 30px 26px 34px;
            }
        }

        @media (max-width: 560px) {
            .login-page {
                padding-top: 22px;
            }

            .login-visual,
            .login-form {
                padding-inline: 18px;
            }
        }
    </style>

    <section class="page-shell login-page">
        <div class="login-shell">
            <aside class="login-visual">
                <div>
                    <img class="login-visual__logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                </div>

                <div>
                    <h1>{{ __('messages.login') }}</h1>
                    <p>{{ __('messages.login_page_intro') }}</p>
                </div>

                <div class="login-visual__notes" aria-hidden="true">
                    <span>{{ __('messages.admin_panel') }}</span>
                    <span>{{ __('messages.view_more') }}</span>
                    <span>{{ __('messages.verifications') }}</span>
                </div>
            </aside>

            <div class="login-form">
                <div class="login-form__inner">
                    <div class="login-form__intro">
                        <span class="kicker">{{ __('messages.login') }}</span>
                        <h2>{{ __('messages.login') }}</h2>
                        <p>{{ __('messages.login_page_intro') }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="login-error">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login', ['locale' => $locale]) }}">
                        @csrf

                        <div class="form-field">
                            <label for="email">{{ __('messages.email') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" />
                        </div>

                        <div class="form-field">
                            <label for="password">{{ __('messages.password') }}</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" />
                        </div>

                        <div class="login-form__actions">
                            <button type="submit" class="button">{{ __('messages.login') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
