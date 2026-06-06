@extends('layouts.admin')

@php($pageTitle = __('messages.authors'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.authors') }}</h1>
                <p class="text-muted">{{ __('messages.author_index_intro') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.authors.create', ['locale' => $locale]) }}" class="button">{{ __('messages.create_author') }}</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="label">{{ __('messages.authors') }}</div><div class="value">{{ $authors->total() }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.posts') }}</div><div class="value">{{ $authors->sum('posts_count') }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.role') }}</div><div class="value">{{ __('messages.author') }}</div></div>
        </div>

        <form method="GET" action="{{ route('admin.authors.index', ['locale' => $locale]) }}" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center; margin-bottom:1rem;">
            <div class="form-field" style="margin-bottom:0; flex:1 1 280px;">
                <label for="search">{{ __('messages.search') }}</label>
                <input id="search" type="search" name="search" value="{{ $search }}" placeholder="{{ __('messages.search_here') }}" />
            </div>
            <div style="display:flex; gap:0.75rem; align-items:center; margin-top:1.35rem;">
                <button type="submit" class="button">{{ __('messages.search') }}</button>
                @if($search !== '')
                    <a href="{{ route('admin.authors.index', ['locale' => $locale]) }}" class="text-muted">{{ __('messages.clear_filter') }}</a>
                @endif
            </div>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.posts') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        <tr>
                            <td>{{ $author->name }}</td>
                            <td>{{ $author->email }}</td>
                            <td>{{ __('messages.author') }}</td>
                            <td>{{ $author->posts_count }}</td>
                            <td>
                                <a href="{{ route('admin.authors.edit', ['locale' => $locale, 'author' => $author]) }}">{{ __('messages.edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:1.5rem; color:#64748b;">{{ __('messages.no_authors') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            {{ $authors->links() }}
        </div>
    </section>
@endsection
