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
        <label for="current_position">{{ __('messages.verify_current_position') }}</label>
        <input id="current_position" name="current_position" value="{{ old('current_position', $verification?->current_position ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="phone">{{ __('messages.contact_phone_label') }}</label>
        <input id="phone" name="phone" value="{{ old('phone', $verification?->phone ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="email">{{ __('messages.contact_email_label') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email', $verification?->email ?? '') }}" />
    </div>

    <div class="form-field">
        <label for="expertise">{{ __('messages.verify_expertise') }}</label>
        <textarea id="expertise" name="expertise" rows="3" placeholder="{{ __('messages.verify_expertise_hint') }}">{{ old('expertise', $verification?->expertise ?? '') }}</textarea>
    </div>

    <div class="form-field">
        <label for="languages">{{ __('messages.verify_languages') }}</label>
        <textarea id="languages" name="languages" rows="3" placeholder="{{ __('messages.verify_languages_hint') }}">{{ old('languages', $verification?->languages ?? '') }}</textarea>
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
        <label for="issue_date">{{ __('messages.issue_date') }}</label>
        <input id="issue_date" type="date" name="issue_date" value="{{ old('issue_date', optional($verification?->issue_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" />
    </div>

    <div class="form-field">
        <label for="expiry_date">{{ __('messages.expiry_date') }}</label>
        <input id="expiry_date" type="date" name="expiry_date" value="{{ old('expiry_date', optional($verification?->expiry_date)->format('Y-m-d') ?? now()->addYear()->format('Y-m-d')) }}" />
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

    <div class="form-field">
        <label for="gallery_photos">{{ __('messages.verify_gallery_upload') }}</label>
        <input id="gallery_photos" type="file" name="gallery_photos[]" accept="image/*" multiple />
        <p class="text-muted" style="margin-top:.45rem;">{{ __('messages.verify_gallery_upload_hint') }}</p>
        @if($verification?->galleryPhotos?->count())
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(110px,1fr)); gap:.75rem; margin-top:.85rem;">
                @foreach($verification->galleryPhotos as $galleryPhoto)
                    <figure style="margin:0;">
                        <img src="{{ asset('storage/'.$galleryPhoto->path) }}" alt="{{ $verification->full_name }} gallery photo" style="width:100%; aspect-ratio:1; object-fit:cover; border-radius:10px; border:1px solid rgba(23,23,23,.12);">
                    </figure>
                @endforeach
            </div>
        @endif
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">
            {{ $verification ? __('messages.edit_verification_card') : __('messages.create_verification_card') }}
        </button>
        <a href="{{ route('admin.verifications.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>
