@extends('layouts.admin')

@php($pageTitle = __('messages.create_post'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.create_post') }}</h1>
                <p class="text-muted">{{ __('messages.create_post') }} {{ __('messages.posts') }}</p>
            </div>
        </div>
        @include('admin.posts.form', ['action' => route('admin.posts.store', ['locale' => $locale]), 'method' => 'POST', 'post' => null, 'locale' => $locale])
    </section>
@endsection
