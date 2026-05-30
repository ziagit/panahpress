@extends('layouts.admin')

@php($pageTitle = __('messages.posts'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.posts') }}</h1>
                <p class="text-muted">{{ __('messages.welcome_admin') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.posts.create', ['locale' => $locale]) }}" class="button">{{ __('messages.create_post') }}</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="label">{{ __('messages.posts') }}</div><div class="value">{{ $posts->total() }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.categories') }}</div><div class="value">{{ $statsPosts->pluck('category_id')->filter()->unique()->count() }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.published_at') }}</div><div class="value">{{ $statsPosts->whereNotNull('published_at')->count() }}</div></div>
        </div>

        <form method="GET" action="{{ route('admin.posts.index', ['locale' => $locale]) }}" class="panel-card" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center; margin-bottom:1rem;">
            <div class="form-field" style="margin-bottom:0; flex:1 1 280px;">
                <label for="search">{{ __('messages.search') }}</label>
                <input id="search" type="search" name="search" value="{{ $search }}" placeholder="{{ __('messages.search_by_title') }}" />
            </div>
            <div style="display:flex; gap:0.75rem; align-items:center; margin-top:1.35rem;">
                <button type="submit" class="button">Search</button>
                @if($search !== '')
                    <a href="{{ route('admin.posts.index', ['locale' => $locale]) }}" class="text-muted">Clear</a>
                @endif
            </div>
        </form>

        <div class="table-card">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.title_en') }}</th>
                            <th>{{ __('messages.title_fa') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.published_at') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->title('en') }}</td>
                                <td>{{ $post->title('fa') }}</td>
                                <td>{{ $post->category?->name($locale) ?? '—' }}</td>
                                <td>{{ $post->published_at?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.posts.edit', ['locale' => $locale, 'post' => $post]) }}">{{ __('messages.edit') }}</a>
                                    <form action="{{ route('admin.posts.destroy', ['locale' => $locale, 'post' => $post]) }}" method="POST" style="display:inline; margin-left:0.75rem;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;">{{ __('messages.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:1.5rem; color:#64748b;">No posts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1rem;">
            {{ $posts->links() }}
        </div>
    </section>
@endsection
