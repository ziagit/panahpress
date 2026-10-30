@extends('layouts.admin', ['pageTitle' => __('certificates.manage')])
@section('content')
<style>
    .certificates-panel { padding: 1.25rem; }
    .certificates-panel .panel-header { align-items: center; gap: 1rem; }
    .certificates-panel .panel-header h1 { margin: 0; font-size: 1.15rem; }
    .certificates-panel .button { min-height: 2.75rem; padding: .65rem 1rem; border-radius: .65rem; box-sizing: border-box; font-size: .9rem; text-decoration: none; white-space: nowrap; }
    .certificate-search { display: flex; align-items: flex-end; gap: .75rem; margin-bottom: 1.25rem; }
    .certificate-search .form-field { flex: 1; min-width: 0; margin: 0; }
    .certificate-search label { font-size: .85rem; }
    .certificate-search input { box-sizing: border-box; height: 2.75rem; padding: .65rem .85rem; border-radius: .65rem; }
    .certificates-panel .table th, .certificates-panel .table td { padding: .85rem 1rem; vertical-align: middle; }
    .certificate-actions { display: flex; align-items: center; gap: .85rem; white-space: nowrap; }
    .certificate-actions a { color: #2563eb; font-weight: 600; font-size: .85rem; text-decoration: none; }
    .certificate-actions a:hover { text-decoration: underline; }
    @media (max-width: 640px) {
        .certificates-panel { padding: 1rem; }
        .certificates-panel .panel-header { align-items: stretch; }
        .certificates-panel .panel-header .button { align-self: flex-start; }
        .certificate-search { flex-wrap: wrap; }
        .certificate-search .form-field { flex-basis: 100%; }
        .certificate-search .button { width: 100%; }
    }
</style>
<div class="panel-card certificates-panel">
    <div class="panel-header"><h1>{{ __('certificates.manage') }}</h1><a class="button" href="{{ route('admin.certificates.create', ['locale' => $locale]) }}">{{ __('certificates.create') }}</a></div>
    <form method="GET" class="certificate-search">
        <div class="form-field"><label for="search">{{ __('messages.search') }}</label><input id="search" name="search" type="search" value="{{ $search }}"></div>
        <button type="submit" class="button">{{ __('messages.search') }}</button>
    </form>
    <div class="table-wrap"><table class="table"><thead><tr>
        @foreach(['number','recipient_name','title','status'] as $field)<th>{{ __('certificates.'.$field) }}</th>@endforeach
        <th>{{ __('messages.actions') }}</th>
    </tr></thead><tbody>
    @forelse($certificates as $certificate)
        <tr><td>{{ $certificate->number }}</td><td>{{ $certificate->recipient_name }}</td><td>{{ $certificate->title }}</td><td>{{ __('certificates.'.$certificate->status()) }}</td><td><div class="certificate-actions">
            <a href="{{ route('admin.certificates.edit', ['locale' => $locale, 'certificate' => $certificate]) }}">{{ __('messages.edit') }}</a>
            <a href="{{ route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]) }}">{{ __('messages.view_more') }}</a>
        </div></td></tr>
    @empty<tr><td colspan="5">{{ __('certificates.empty') }}</td></tr>@endforelse
    </tbody></table></div>
    @if($certificates->hasPages())
        <div class="admin-pagination-wrapper">{{ $certificates->links() }}</div>
    @endif
</div>
@endsection
