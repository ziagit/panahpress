@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 24px;">
        <div class="page-card" style="display:grid; gap: 18px;">
            <div>
                <h1 style="margin: 0 0 12px; font-family: var(--serif); font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05; color:#11161c;">{{ __('messages.contact_page_title') }}</h1>
                <p style="margin: 0; color:#334155; font-size: 1.05rem; line-height: 1.7;">{{ __('messages.contact_page_intro') }}</p>
            </div>

            <div style="padding: 18px; border: 1px solid var(--line); background: #fff;">
                <div class="kicker" style="margin-bottom: 6px;">{{ __('messages.contact_email_label') }}</div>
                <div style="color:#11161c; line-height:1.6;">
                    <a href="mailto:info@panahpress.com">info@panahpress.com</a><br>
                    <a href="mailto:news@panahpress.com">news@panahpress.com</a>
                </div>
            </div>

            <form style="display:grid; gap: 14px; margin-top: 6px;">
                <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                    <input type="text" placeholder="{{ __('messages.name') }}" aria-label="{{ __('messages.name') }}" style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                    <input type="email" placeholder="{{ __('messages.email') }}" aria-label="{{ __('messages.email') }}" style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                </div>
                <input type="text" placeholder="{{ __('messages.subject') }}" aria-label="{{ __('messages.subject') }}" style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                <textarea rows="6" placeholder="{{ __('messages.message') }}" aria-label="{{ __('messages.message') }}" style="padding: 14px; border: 1px solid var(--line); background: #fff; resize: vertical;"></textarea>
                <div>
                    <button type="button" class="donate-btn" style="border-radius: 0;">{{ __('messages.send_message') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection
