@extends('layouts.admin', ['pageTitle' => 'Agreements'])

@section('content')
<style>
    .agreement-form-panel { padding: 1.25rem; margin-bottom: 1.25rem; }
    .agreement-form-panel h2 { margin: 0 0 1rem; font-size: 1.1rem; }
    .agreement-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .agreement-fields .form-field { min-width: 0; margin-bottom: 0; }
    .agreement-fields input { padding: .65rem .8rem; border-radius: .65rem; min-height: 2.9rem; box-sizing: border-box; }
    .agreement-fields input[type="file"] { font-size: .85rem; }
    .agreement-form-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: .85rem; }
    .agreement-form-footer .editor-help { margin: 0; font-size: .8rem; }
    .agreement-form-footer .button { padding: .65rem 1rem; white-space: nowrap; border-radius: .65rem; }
    .agreement-download { color: #2563eb; font-size: .9rem; font-weight: 600; text-decoration: none; }
    .agreement-download:hover { color: #1d4ed8; text-decoration: underline; }
    .agreement-download:focus-visible { outline: 2px solid #2563eb; outline-offset: 4px; border-radius: 2px; }
    .agreements-table th, .agreements-table td { padding: .8rem 1rem; vertical-align: middle; }
    @media (max-width: 640px) {
        .agreement-fields { grid-template-columns: 1fr; gap: .85rem; }
        .agreement-form-footer { align-items: flex-start; flex-direction: column; gap: .75rem; }
    }
</style>
<div class="panel agreement-form-panel">
    <h2>Add agreement</h2>
    <form method="POST" action="{{ route('admin.agreements.store', ['locale' => app()->getLocale()]) }}" enctype="multipart/form-data">
        @csrf
        <div class="agreement-fields">
        <div class="form-field">
            <label for="person_name">Person name</label>
            <input id="person_name" name="person_name" value="{{ old('person_name') }}" maxlength="255" required>
        </div>
        <div class="form-field">
            <label for="agreement">Agreement file</label>
            <input id="agreement" name="agreement" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required aria-describedby="agreement-help">

        </div>
        </div>
        <div class="agreement-form-footer">
            <p id="agreement-help" class="editor-help">PDF, Word, JPG or PNG · Up to 10 MB · Admin access only</p>
            <button class="button" type="submit">Save agreement</button>
        </div>
    </form>
</div>
<div class="panel table-card">
    <div class="table-wrap">
        <table class="table agreements-table">
            <thead><tr><th>Person name</th><th>Agreement</th><th>Added</th></tr></thead>
            <tbody>
                @forelse($agreements as $agreement)
                    <tr>
                        <td>{{ $agreement->person_name }}</td>
                        <td><a class="agreement-download" href="{{ route('admin.agreements.download', ['locale' => app()->getLocale(), 'agreement' => $agreement]) }}" aria-label="Download agreement for {{ $agreement->person_name }}">Download</a></td>
                        <td>{{ $agreement->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No agreements added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($agreements->hasPages())
<div class="admin-pagination-wrapper">{{ $agreements->links() }}</div>
@endif
@endsection
