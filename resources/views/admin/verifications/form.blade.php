<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="form-field">
        <label for="code">{{ __('messages.card_id') }}</label>
        <input id="code" name="code" value="{{ old('code', $verification?->code ?? '') }}" maxlength="4" required />
    </div>

    <div class="form-field">
        <label for="security_code">{{ __('messages.security_code') }}</label>
        <input id="security_code" name="security_code" value="{{ old('security_code', $verification?->security_code ?? '') }}" inputmode="numeric" maxlength="6" />
    </div>

    <div class="form-field">
        <label for="full_name">{{ __('messages.full_name') }}</label>
        <input id="full_name" name="full_name" value="{{ old('full_name', $verification?->full_name ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="occupation">{{ __('messages.occupation') }}</label>
        <input id="occupation" name="occupation" value="{{ old('occupation', $verification?->occupation ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="profile_org">{{ __('messages.verify_profile_org_label') }}</label>
        <input id="profile_org" name="profile_org" value="{{ old('profile_org', $verification?->profile_org ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="current_position">{{ __('messages.verify_current_position') }}</label>
        <input id="current_position" name="current_position" value="{{ old('current_position', $verification?->current_position ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="field">{{ __('messages.verify_field') }}</label>
        <input id="field" name="field" value="{{ old('field', $verification?->field ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="location">{{ __('messages.verify_location') }}</label>
        <input id="location" name="location" value="{{ old('location', $verification?->location ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="short_bio">{{ __('messages.verify_short_bio') }}</label>
        <textarea id="short_bio" name="short_bio" rows="4">{{ old('short_bio', $verification?->short_bio ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="about_text">{{ __('messages.verify_about_section') }}</label>
        <textarea id="about_text" name="about_text" rows="4">{{ old('about_text', $verification?->about_text ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="achievements">{{ __('messages.verify_achievements') }}</label>
        <textarea id="achievements" name="achievements" rows="5" placeholder="{{ __('messages.verify_achievements_hint') }}">{{ old('achievements', $verification?->achievements ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="timeline">{{ __('messages.verify_timeline') }}</label>
        <textarea id="timeline" name="timeline" rows="4" placeholder="{{ __('messages.verify_timeline_hint') }}">{{ old('timeline', $verification?->timeline ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="quote_text">{{ __('messages.verify_quote_section') }}</label>
        <textarea id="quote_text" name="quote_text" rows="3">{{ old('quote_text', $verification?->quote_text ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="birth_date">{{ __('messages.birth_date') }}</label>
        <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', optional($verification?->birth_date)->format('Y-m-d')) }}" required />
    </div>

    <div class="form-field">
        <label for="photo">{{ __('messages.photo') }}</label>
        @if($verification?->photo)
            <div style="margin-bottom:.6rem;">
                <img src="{{ asset('storage/'.$verification->photo) }}" alt="{{ $verification->full_name }}" style="max-width:220px;border-radius:0;display:block;" />
            </div>
        @endif
        <input id="photo" type="file" name="photo" accept="image/*" @if(!$verification) required @endif />
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">
            {{ $verification ? __('messages.edit_verification_card') : __('messages.create_verification_card') }}
        </button>
        <a href="{{ route('admin.verifications.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>
