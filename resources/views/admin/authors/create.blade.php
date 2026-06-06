@extends('layouts.admin')

@php($pageTitle = __('messages.create_author'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.create_author') }}</h1>
                <p class="text-muted">{{ __('messages.author_intro') }}</p>
            </div>
        </div>
        @include('admin.authors.form', ['action' => route('admin.authors.store', ['locale' => $locale]), 'method' => 'POST', 'author' => null, 'locale' => $locale])
    </section>
@endsection
