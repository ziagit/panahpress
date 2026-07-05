@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 24px; padding-bottom: 48px;">
        <div class="page-card" style="display:grid; gap: 18px;">
            <div>
                <h1 style="margin: 0 0 12px; font-family: var(--serif); font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05; color:#11161c;">{{ __('messages.contact_page_title') }}</h1>
                <p style="margin: 0; color:#334155; font-size: 1.05rem; line-height: 1.7;">{{ __('messages.contact_page_intro') }}</p>
            </div>

            @if (session('contact_success'))
                <div style="padding: 14px 16px; border: 1px solid rgba(12, 136, 189, 0.18); background: #eff8fc; color: #0f4f72; line-height: 1.6;">
                    {{ session('contact_success') }}
                </div>
            @elseif (session('contact_error'))
                <div style="padding: 14px 16px; border: 1px solid rgba(220, 38, 38, 0.22); background: #fff4f4; color: #9f1239; line-height: 1.6;">
                    {{ session('contact_error') }}
                </div>
            @endif

            <div style="padding: 18px; border: 1px solid var(--line); background: #fff;">
                <div class="kicker" style="margin-bottom: 6px;">{{ __('messages.contact_email_label') }}</div>
                <div style="color:#11161c; line-height:1.6;">
                    <a href="mailto:contact@panahpress.com">contact@panahpress.com</a>
                </div>
            </div>

            <form method="POST" action="{{ route('contact.send', ['locale' => $locale]) }}" style="display:grid; gap: 14px; margin-top: 6px;">
                @csrf
                <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('messages.name') }}" aria-label="{{ __('messages.name') }}" required style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}" aria-label="{{ __('messages.email') }}" required style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                </div>
                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="{{ __('messages.subject') }}" aria-label="{{ __('messages.subject') }}" required style="min-height: 44px; padding: 0 14px; border: 1px solid var(--line); background: #fff;">
                <textarea rows="6" name="message" placeholder="{{ __('messages.message') }}" aria-label="{{ __('messages.message') }}" required style="padding: 14px; border: 1px solid var(--line); background: #fff; resize: vertical;">{{ old('message') }}</textarea>
                <div>
                    <button type="submit" class="donate-btn" style="border-radius: 0;">{{ __('messages.send_message') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection
