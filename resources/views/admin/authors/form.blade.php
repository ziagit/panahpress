<form action="{{ $action }}" method="POST">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="form-field">
        <label for="name">{{ __('messages.name') }}</label>
        <input id="name" name="name" value="{{ old('name', $author?->name ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="email">{{ __('messages.email') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email', $author?->email ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="password">{{ __('messages.password') }}</label>
        <input id="password" type="password" name="password" @if(! $author) required @endif />
        <p class="editor-help">
            {{ $author ? __('messages.author_password_help_existing') : __('messages.author_password_help_new') }}
        </p>
    </div>

    <div class="form-field">
        <label>{{ __('messages.role') }}</label>
        <input type="text" value="{{ __('messages.author') }}" disabled />
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">{{ $author ? __('messages.save_author') : __('messages.create_author') }}</button>
        <a href="{{ route('admin.authors.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>
