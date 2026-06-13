@extends('layouts.newspaper')

@section('content')
    <style>
        .auth-page {
            padding-top: 34px;
            padding-bottom: 56px;
            min-height: 68vh;
        }

        .auth-shell {
            width: min(100%, 760px);
            margin: 0 auto;
            border: 1px solid var(--line);
            box-shadow: 0 18px 38px rgba(10, 30, 50, 0.08);
            overflow: hidden;
            background: #fff;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
        }

        .auth-visual {
            background:
                radial-gradient(circle at 18% 18%, rgba(255,255,255,0.18) 0 2px, transparent 2px),
                linear-gradient(180deg, #0d93c5 0%, #0a79a7 100%);
            color: #fff;
            padding: 42px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
        }

        .auth-visual h1,
        .auth-form__intro h2 {
            margin: 0;
            font-family: var(--serif);
            line-height: 1.03;
        }

        .auth-visual h1 { font-size: clamp(2.2rem, 4vw, 3.5rem); }
        .auth-visual p { margin: 0; max-width: 28rem; font-size: 1rem; line-height: 1.8; color: rgba(255,255,255,.92); }

        .auth-visual__logo {
            width: 92px;
            height: 92px;
            object-fit: contain;
        }

        .auth-form {
            padding: 42px 38px;
            display: grid;
            align-content: center;
        }

        .auth-form__inner { max-width: 420px; }
        .auth-form__intro { display: grid; gap: 8px; margin-bottom: 26px; }
        .auth-form__intro h2 { font-size: clamp(1.8rem, 2.6vw, 2.5rem); color: #11161c; }
        .auth-form__intro p { margin: 0; color: #475569; line-height: 1.7; }

        .form-field { margin-bottom: 16px; }
        .form-field label { display: block; margin-bottom: 8px; font-size: 0.92rem; font-weight: 700; color: #334155; }
        .form-field input {
            width: 100%;
            min-height: 48px;
            padding: 0 15px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            font-size: 16px;
        }
        .form-field input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(12, 136, 189, 0.1);
        }

        .auth-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 22px;
            flex-wrap: wrap;
        }

        .auth-actions .button { min-height: 44px; padding-inline: 20px; }
        .auth-links { margin-top: 16px; font-size: 0.94rem; }
        .auth-links a { color: #0a79a7; font-weight: 700; }

        .auth-message {
            margin-bottom: 18px;
            padding: 12px 14px;
            background: #ecfeff;
            border: 1px solid #99f6e4;
            color: #0f766e;
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .auth-error {
            margin-bottom: 18px;
            padding: 12px 14px;
            background: #fff4f4;
            border: 1px solid #f4c5c5;
            color: #b42318;
            font-size: 0.94rem;
            line-height: 1.6;
        }

        @media (max-width: 880px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-visual { padding: 30px 26px; }
            .auth-form { padding: 30px 26px 34px; }
        }

        @media (max-width: 560px) {
            .auth-page { padding-top: 22px; }
            .auth-visual,
            .auth-form { padding-inline: 18px; }
            .auth-actions .button { width: 100%; }
            .auth-links { width: 100%; text-align: center; }
        }
    </style>

    <section class="page-shell auth-page">
        <div class="auth-shell">
            <aside class="auth-visual">
                <div>
                    <img class="auth-visual__logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                </div>

                <div>
                    <h1>{{ __('messages.reset_password') }}</h1>
                    <p>{{ __('messages.password_reset_intro') }}</p>
                </div>
            </aside>

            <div class="auth-form">
                <div class="auth-form__inner">
                    <div class="auth-form__intro">
                        <span class="kicker">{{ __('messages.reset_password') }}</span>
                        <h2>{{ __('messages.reset_password') }}</h2>
                        <p>{{ __('messages.password_reset_intro') }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="auth-error">{{ $errors->first('email') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update', ['locale' => $locale]) }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-field">
                            <label for="email">{{ __('messages.email') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" />
                        </div>

                        <div class="form-field">
                            <label for="password">{{ __('messages.new_password') }}</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" />
                        </div>

                        <div class="form-field">
                            <label for="password_confirmation">{{ __('messages.confirm_new_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>

                        <div class="auth-actions">
                            <button type="submit" class="button">{{ __('messages.reset_password') }}</button>
                        </div>

                        <div class="auth-links">
                            <a href="{{ route('login', ['locale' => $locale]) }}">{{ __('messages.back_to_login') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
