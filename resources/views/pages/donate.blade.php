@extends('layouts.newspaper')

@section('content')
    <section class="page-shell" style="padding-top: 24px; min-height: 70vh;">
        <div class="page-card" style="display:grid; gap: 22px;">
            <div>
                <h1 style="margin: 0 0 12px; font-family: var(--serif); font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05; color:#11161c;">{{ __('messages.donate_page_title') }}</h1>
                <p style="margin: 0 0 12px; color:#334155; font-size: 1.05rem; line-height: 1.7;">{{ __('messages.donate_page_intro') }}</p>
                <p style="margin: 0; color:#475569; font-size: 1rem; line-height: 1.8;">{{ __('messages.donate_page_body') }}</p>
            </div>

            <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px;">
                <button type="button" class="donate-btn" style="min-height: 92px; font-size: 1.4rem; border-radius: 0; background:#fff7f0; border-color:#e4c6b5; color:#8a4b1d;">$10</button>
                <button type="button" class="donate-btn" style="min-height: 92px; font-size: 1.4rem; border-radius: 0; background:#fff7f0; border-color:#e4c6b5; color:#8a4b1d;">$50</button>
                <button type="button" class="donate-btn" style="min-height: 92px; font-size: 1.4rem; border-radius: 0; background:#fff7f0; border-color:#e4c6b5; color:#8a4b1d;">$100</button>
                <div style="padding: 18px; border: 1px solid var(--line); background: #fff; display:grid; gap: 10px;">
                    <div class="kicker">{{ __('messages.donate_custom_amount') }}</div>
                    <input type="text" inputmode="decimal" placeholder="$0.00" aria-label="{{ __('messages.donate_custom_amount') }}" style="min-height: 46px; padding: 0 14px; border: 1px solid var(--line); background:#fff;">
                    <button type="button" class="donate-btn" style="border-radius: 0;">{{ __('messages.donate') }}</button>
                </div>
            </div>

            <div style="padding: 18px; border: 1px solid var(--line); background: #f8fafc; color:#475569; line-height:1.7;">
                {{ __('messages.donate_note') }}
            </div>
        </div>
    </section>
@endsection
