@extends('layouts.admin')

@php($pageTitle = __('messages.create_category'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.create_category') }}</h1>
                <p class="text-muted">{{ __('messages.create_category') }} {{ __('messages.categories') }}</p>
            </div>
        </div>
        @include('admin.categories.form', ['action' => route('admin.categories.store', ['locale' => $locale]), 'method' => 'POST', 'category' => null, 'locale' => $locale, 'categories' => $categories])
    </section>
@endsection
