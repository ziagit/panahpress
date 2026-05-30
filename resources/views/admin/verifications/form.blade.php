<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="form-field">
        <label for="full_name">{{ __('messages.full_name') }}</label>
        <input id="full_name" name="full_name" value="{{ old('full_name', $verification?->full_name ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="occupation">{{ __('messages.occupation') }}</label>
        <input id="occupation" name="occupation" value="{{ old('occupation', $verification?->occupation ?? '') }}" required />
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

    @if($verification)
        <div class="form-field">
            <label>{{ __('messages.card_id') }}</label>
            <input value="{{ $verification->code }}" readonly />
        </div>
    @endif

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">
            {{ $verification ? __('messages.edit_verification_card') : __('messages.create_verification_card') }}
        </button>
        <a href="{{ route('admin.verifications.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>
