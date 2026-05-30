@extends('layouts.admin')

@php($pageTitle = __('messages.edit_category'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.edit_category') }}</h1>
                <p class="text-muted">{{ __('messages.edit_category') }} {{ __('messages.categories') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.categories.index', ['locale' => $locale]) }}" class="button" style="background:#475569;">{{ __('messages.back_to_dashboard') }}</a>
            </div>
        </div>
        @include('admin.categories.form', ['action' => route('admin.categories.update', ['locale' => $locale, 'category' => $category]), 'method' => 'PUT', 'category' => $category, 'locale' => $locale, 'categories' => $categories])
    </section>
@endsection
