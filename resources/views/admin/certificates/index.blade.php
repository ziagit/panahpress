@extends('layouts.admin')
@section('content')
<div class="panel-card">
    <div class="panel-header"><h1>{{ __('certificates.manage') }}</h1><a class="button" href="{{ route('admin.certificates.create', ['locale' => $locale]) }}">{{ __('certificates.create') }}</a></div>
    <form method="GET" style="display:flex;gap:12px;align-items:end;margin-bottom:24px;">
        <div class="form-field" style="flex:1;"><label for="search">{{ __('messages.search') }}</label><input id="search" name="search" type="search" value="{{ $search }}"></div>
        <button class="button">{{ __('messages.search') }}</button>
    </form>
    <div class="table-wrap"><table class="table"><thead><tr>
        @foreach(['number','recipient_name','title','status'] as $field)<th>{{ __('certificates.'.$field) }}</th>@endforeach
        <th>{{ __('messages.actions') }}</th>
    </tr></thead><tbody>
    @forelse($certificates as $certificate)
        <tr><td>{{ $certificate->number }}</td><td>{{ $certificate->recipient_name }}</td><td>{{ $certificate->title }}</td><td>{{ __('certificates.'.$certificate->status()) }}</td><td>
            <a href="{{ route('admin.certificates.edit', ['locale' => $locale, 'certificate' => $certificate]) }}">{{ __('messages.edit') }}</a> ·
            <a href="{{ route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]) }}">{{ __('messages.view_more') }}</a>
        </td></tr>
    @empty<tr><td colspan="5">{{ __('certificates.empty') }}</td></tr>@endforelse
    </tbody></table></div>
    {{ $certificates->links() }}
</div>
@endsection
