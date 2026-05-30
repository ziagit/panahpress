@extends('layouts.admin')

@php($pageTitle = __('messages.edit_post'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.edit_post') }}</h1>
                <p class="text-muted">{{ __('messages.edit_post') }} {{ __('messages.posts') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.posts.index', ['locale' => $locale]) }}" class="button" style="background:#475569;">{{ __('messages.back_to_dashboard') }}</a>
            </div>
        </div>
        @include('admin.posts.form', ['action' => route('admin.posts.update', ['locale' => $locale, 'post' => $post]), 'method' => 'PUT', 'post' => $post, 'locale' => $locale])
    </section>
@endsection
