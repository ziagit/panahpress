@extends('layouts.admin')

@php($pageTitle = __('messages.profile'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.profile') }}</h1>
                <p class="text-muted">{{ __('messages.profile_intro') }}</p>
            </div>
        </div>

        <div class="auth-layout" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
            <div class="auth-card">
                <h2 style="margin-top:0;">{{ __('messages.update_profile') }}</h2>
                <form method="POST" action="{{ route('admin.profile.update', ['locale' => $locale]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-field">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" required />
                    </div>

                    <div class="form-field">
                        <label for="email">{{ __('messages.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1.25rem;">
                        <button type="submit" class="button">{{ __('messages.save_profile') }}</button>
                    </div>
                </form>
            </div>

            <div class="auth-card">
                <h2 style="margin-top:0;">{{ __('messages.change_password') }}</h2>
                <form method="POST" action="{{ route('admin.profile.password', ['locale' => $locale]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-field">
                        <label for="current_password">{{ __('messages.current_password') }}</label>
                        <input id="current_password" type="password" name="current_password" required />
                    </div>

                    <div class="form-field">
                        <label for="password">{{ __('messages.new_password') }}</label>
                        <input id="password" type="password" name="password" required />
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation">{{ __('messages.confirm_new_password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required />
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1.25rem;">
                        <button type="submit" class="button">{{ __('messages.change_password') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
