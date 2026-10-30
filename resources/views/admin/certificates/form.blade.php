@extends('layouts.admin')
@section('content')
<div class="panel-card">
    <div class="panel-header"><h1>{{ __('certificates.'.($certificate ? 'edit' : 'create')) }}</h1><a href="{{ route('admin.certificates.index', ['locale' => $locale]) }}">{{ __('certificates.back') }}</a></div>
    @if($errors->any())<div role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <form method="POST" enctype="multipart/form-data" action="{{ $certificate ? route('admin.certificates.update', ['locale' => $locale, 'certificate' => $certificate]) : route('admin.certificates.store', ['locale' => $locale]) }}">
        @csrf
        @if($certificate) @method('PUT') @endif
        @foreach(['number', 'recipient_name', 'title', 'issuer'] as $field)
            <div class="form-field"><label for="{{ $field }}">{{ __('certificates.'.$field) }}</label><input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $certificate?->$field ?? ($field === 'issuer' ? 'Panah Press' : '')) }}" required maxlength="{{ $field === 'number' ? 100 : 255 }}"></div>
        @endforeach
        <div class="form-field"><label for="description">{{ __('certificates.description') }}</label><textarea id="description" name="description" rows="4" maxlength="10000">{{ old('description', $certificate?->description) }}</textarea></div>
        @foreach(['issue_date', 'expiry_date'] as $field)
            <div class="form-field"><label for="{{ $field }}">{{ __('certificates.'.$field) }}</label><input id="{{ $field }}" type="date" name="{{ $field }}" value="{{ old($field, $certificate?->$field?->format('Y-m-d') ?? ($field === 'issue_date' ? now()->toDateString() : '')) }}" @required($field === 'issue_date')></div>
        @endforeach
        <div class="form-field"><label for="image">{{ __('certificates.image') }}</label><input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(!$certificate)><p class="text-muted">{{ __('certificates.image_help') }}</p></div>
        @if($certificate)
            <img src="{{ route('certificates.image', ['locale' => $locale, 'token' => $certificate->verification_token]) }}" alt="{{ __('certificates.image') }}" style="max-width:220px;max-height:180px;object-fit:contain;">
        @endif
        <input type="hidden" name="is_revoked" value="0">
        <div class="form-field"><label><input type="checkbox" name="is_revoked" value="1" @checked(old('is_revoked', $certificate?->is_revoked))> {{ __('certificates.is_revoked') }}</label></div>
        <button class="button" type="submit">{{ __('certificates.save') }}</button>
    </form>
    @if($certificate)
        <hr style="margin-block:28px;">
        <h2>{{ __('certificates.link') }}</h2><p>{{ __('certificates.link_help') }}</p>
        <div class="form-field"><label for="verification-link">{{ __('certificates.link') }}</label><input id="verification-link" readonly dir="ltr" value="{{ route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]) }}"></div>
        <div class="form-field"><label for="verification-code">{{ __('certificates.verification_code') }}</label><input id="verification-code" readonly dir="ltr" value="{{ $certificate->verification_token }}"></div>
        <a class="button" href="{{ route('admin.certificates.qr', ['locale' => $locale, 'certificate' => $certificate]) }}">{{ __('certificates.qr') }}</a>
    @endif
</div>
@endsection
