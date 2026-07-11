@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 28px; padding-bottom: 44px; min-height: 65vh;">
        <div style="max-width: 760px; margin: 0 auto; display:grid; gap: 18px;">
            <div style="display:grid; gap: 10px; text-align:center;">
                <span class="kicker">{{ __('messages.footer_verify') }}</span>
                <h1 style="margin: 0; font-family: var(--serif); font-size: clamp(1.65rem, 2.3vw, 2.2rem); line-height: 1.02; color:#11161c;">
                    {{ __('messages.verify_page_title') }}
                </h1>
                <p style="margin: 0 auto; max-width: 620px; color:#334155; font-size: 1.05rem; line-height: 1.8;">
                    {{ __('messages.verify_page_intro') }}
                </p>
                <p style="margin: 0 auto; max-width: 620px; color:#64748b; font-size: .95rem; line-height: 1.7;">
                    {{ __('messages.verify_scan_hint') }}
                </p>
            </div>

            @if ($errors->any())
                <div style="border: 1px solid #f3c0c0; background:#fff7f7; color:#9f1239; padding: 14px 16px; font-size: 0.95rem;">
                    {{ $errors->first('code') }}
                </div>
            @endif

            <form method="GET" action="{{ route('verify', ['locale' => $locale]) }}" style="display:grid; gap: 14px; margin-top: 8px; max-width: 540px; margin-inline:auto;">
                <label style="display:grid; gap: 8px;">
                    <span class="kicker">{{ __('messages.verify_code_label') }}</span>
                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="{{ __('messages.verify_input_placeholder') }}"
                        aria-label="{{ __('messages.verify_code_label') }}"
                        autocomplete="off"
                        maxlength="4"
                        style="min-height: 50px; padding: 0 16px; border: 1px solid var(--line); background:#fff; font-size: 1rem;"
                    >
                </label>
                <label style="display:grid; gap: 8px;">
                    <span class="kicker">{{ __('messages.verify_security_code_label') }}</span>
                    <input
                        type="text"
                        name="security_code"
                        value="{{ old('security_code') }}"
                        placeholder="{{ __('messages.verify_security_code_placeholder') }}"
                        aria-label="{{ __('messages.verify_security_code_label') }}"
                        autocomplete="off"
                        inputmode="numeric"
                        maxlength="6"
                        style="min-height: 50px; padding: 0 16px; border: 1px solid var(--line); background:#fff; font-size: 1rem;"
                    >
                </label>
                <div style="display:flex; justify-content:center;">
                    <button type="submit" class="donate-btn" style="border-radius: 3px; min-width: 180px; min-height: 42px;">{{ __('messages.verify_button') }}</button>
                </div>
                <p style="margin: 0; text-align:center; color:#64748b; font-size: 0.95rem;">
                    {{ __('messages.verify_help') }}
                </p>
            </form>
        </div>
    </section>
@endsection
