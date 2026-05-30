@extends('layouts.app')

@section('content')
    <section class="auth-layout">
        <div class="auth-visual">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.register') }}</h1>
                <p class="text-muted" style="max-width: 34rem;">
                    Create a new editorial account to manage content, categories, and publishing workflows.
                </p>
            </div>
            <div class="auth-badges">
                <span>Editor</span>
                <span>Publisher</span>
                <span>Protected Access</span>
            </div>
        </div>

        <div class="auth-card">
            <form method="POST" action="{{ route('register', ['locale' => $locale]) }}">
                @csrf

                <div class="form-field">
                    <label for="name">Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus />
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required />
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required />
                </div>

                <div class="form-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required />
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1.25rem;">
                    <button type="submit" class="button">{{ __('messages.register') }}</button>
                    <a href="{{ route('login', ['locale' => $locale]) }}">{{ __('messages.login') }}</a>
                </div>
            </form>
        </div>
    </section>
@endsection
