@extends('layouts.admin')

@section('content')
    <div class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.edit_verification_card') }}</h1>
                <p class="text-muted">
                    <span style="display:inline-flex; gap:0.5rem; flex-wrap:wrap;">
                        <span style="padding:0.2rem 0.55rem; border:1px solid rgba(23,23,23,.14); border-radius:999px;">{{ $verification->formattedCode() }}</span>
                        <span style="padding:0.2rem 0.55rem; border:1px solid rgba(23,23,23,.14); border-radius:999px;">{{ $verification->formattedSecurityCode() }}</span>
                    </span>
                </p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.verifications.index', ['locale' => $locale]) }}" class="button" style="background:#475569;">{{ __('messages.back_to_dashboard') }}</a>
            </div>
        </div>

        @include('admin.verifications.form', ['action' => route('admin.verifications.update', ['locale' => $locale, 'verification' => $verification]), 'method' => 'PUT', 'verification' => $verification, 'locale' => $locale])
    </div>
@endsection
