@extends('layouts.admin')

@php($pageTitle = __('messages.categories'))

@section('content')
    <section class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.categories') }}</h1>
                <p class="text-muted">{{ __('messages.welcome_admin') }}</p>
            </div>
            @if($canManageCategories)
                <div class="panel-actions">
                    <a href="{{ route('admin.categories.create', ['locale' => $locale]) }}" class="button">{{ __('messages.create_category') }}</a>
                </div>
            @endif
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="label">{{ __('messages.categories') }}</div><div class="value">{{ $categories->count() }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.is_active') }}</div><div class="value">{{ $categories->where('is_active', true)->count() }}</div></div>
            <div class="stat-card"><div class="label">{{ __('messages.posts') }}</div><div class="value">{{ $categories->sum('posts_count') }}</div></div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu level</th>
                            <th>{{ __('messages.name_en') }}</th>
                            <th>{{ __('messages.name_fa') }}</th>
                            <th>Parent</th>
                            <th>{{ __('messages.sort_order') }}</th>
                            <th>{{ __('messages.is_active') }}</th>
                            <th>{{ __('messages.posts') }}</th>
                            @if($canManageCategories)
                                <th>{{ __('messages.actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->parent_id ? 'Submenu' : 'Menu item' }}</td>
                                <td>{{ $category->name_en }}</td>
                                <td>{{ $category->name_fa }}</td>
                                <td>{{ $category->parent?->name_en ?? '—' }}</td>
                                <td>{{ $category->sort_order }}</td>
                                <td>{{ $category->is_active ? __('messages.is_active') : __('messages.inactive') }}</td>
                                <td>{{ $category->posts_count }}</td>
                                @if($canManageCategories)
                                    <td>
                                        <a href="{{ route('admin.categories.edit', ['locale' => $locale, 'category' => $category]) }}">{{ __('messages.edit') }}</a>
                                        <form action="{{ route('admin.categories.destroy', ['locale' => $locale, 'category' => $category]) }}" method="POST" style="display:inline; margin-left:0.75rem;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;">{{ __('messages.delete') }}</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
