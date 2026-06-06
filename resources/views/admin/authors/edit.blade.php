@extends('layouts.admin')

@php($pageTitle = __('messages.edit_author'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.edit_author') }}</h1>
                <p class="text-muted">{{ __('messages.author_edit_intro') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.authors.index', ['locale' => $locale]) }}" class="button" style="background:#475569;">{{ __('messages.back_to_dashboard') }}</a>
            </div>
        </div>
        @include('admin.authors.form', ['action' => route('admin.authors.update', ['locale' => $locale, 'author' => $author]), 'method' => 'PUT', 'author' => $author, 'locale' => $locale])
    </section>
@endsection
