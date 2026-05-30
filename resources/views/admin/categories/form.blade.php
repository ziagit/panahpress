<form action="{{ $action }}" method="POST">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="form-field">
        <label for="name_en">{{ __('messages.name_en') }}</label>
        <input id="name_en" name="name_en" value="{{ old('name_en', $category?->name_en ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="name_fa">{{ __('messages.name_fa') }}</label>
        <input id="name_fa" name="name_fa" value="{{ old('name_fa', $category?->name_fa ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="parent_id">Parent menu item</label>
        <select id="parent_id" name="parent_id">
            <option value="">Top-level menu item</option>
            @foreach($categories ?? [] as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category?->parent_id) == $parent->id)>
                    {{ $parent->name_en }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-field">
        <label for="sort_order">{{ __('messages.sort_order') }}</label>
        <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" />
    </div>

    <div class="form-field" style="display:flex; align-items:center; gap:0.75rem;">
        <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true)) />
        <label for="is_active">{{ __('messages.is_active') }}</label>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">{{ __('messages.save_category') }}</button>
        <a href="{{ route('admin.categories.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>
