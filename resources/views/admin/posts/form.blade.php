<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <div class="form-field">
        <label for="title_en">{{ __('messages.title_en') }}</label>
        <input id="title_en" name="title_en" value="{{ old('title_en', $post?->title_en ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="title_fa">{{ __('messages.title_fa') }}</label>
        <input id="title_fa" name="title_fa" value="{{ old('title_fa', $post?->title_fa ?? '') }}" required />
    </div>

    <div class="form-field">
        <label for="content_en">{{ __('messages.content_en') }}</label>
        <textarea id="content_en" class="rich-editor" name="content_en" rows="10">{{ old('content_en', $post?->content_en ?? '') }}</textarea>
        <p class="editor-help">Supports headings, paragraphs, lists, links, images, quotes, and video embeds. For pictures, paste a direct image URL ending in .jpg, .png, .webp, etc. For videos, use TinyMCE's Media button or paste a YouTube iframe embed code.</p>
    </div>

    <div class="form-field">
        <label for="content_fa">{{ __('messages.content_fa') }}</label>
        <textarea id="content_fa" class="rich-editor" name="content_fa" rows="10">{{ old('content_fa', $post?->content_fa ?? '') }}</textarea>
        <p class="editor-help">Supports headings, paragraphs, lists, links, images, quotes, and video embeds. For pictures, paste a direct image URL ending in .jpg, .png, .webp, etc. For videos, use TinyMCE's Media button or paste a YouTube iframe embed code.</p>
    </div>

    <div class="form-field">
        <label for="category_id">{{ __('messages.category') }}</label>
        <select id="category_id" name="category_id">
            <option value="">{{ __('messages.select_category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $post?->category_id) == $category->id)>
                    {{ $category->name($locale) }}
                    @if(! $category->is_active)
                        ({{ __('messages.inactive') }})
                    @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-field">
        <label for="image">Primary Image</label>
        @if($post?->image)
            <div style="margin-bottom:.6rem;">
                <img src="{{ asset('storage/'.$post->image) }}" alt="image" style="max-width:240px;border-radius:0;display:block;" />
            </div>
        @endif
        <input id="image" type="file" name="image" accept="image/*" />
    </div>

    <div class="form-field">
        <label for="video_url">Video URL / Embed Link</label>
        <input
            id="video_url"
            name="video_url"
            value="{{ old('video_url', $post?->video_url ?? '') }}"
            placeholder="https://www.youtube.com/watch?v=..."
        />
        <p class="editor-help">Paste a YouTube, Vimeo, or other embeddable video link here. Uploading is not required for video posts.</p>
    </div>

    @if(auth()->user()?->isAdmin())
        <div class="form-field">
            <label for="published_at">{{ __('messages.published_at') }}</label>
            <input id="published_at" type="date" name="published_at" value="{{ old('published_at', optional($post)->published_at?->format('Y-m-d')) }}" />
            <p class="editor-help">{{ __('messages.post_publish_help') }}</p>
        </div>
    @else
        <div class="form-field">
            <p class="editor-help">{{ __('messages.post_pending_review_notice') }}</p>
        </div>
    @endif

    <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; margin-top:1rem;">
        <button type="submit" class="button">{{ __('messages.save_post') }}</button>
        <a href="{{ route('admin.posts.index', ['locale' => $locale]) }}">{{ __('messages.cancel') }}</a>
    </div>
</form>

@include('admin.partials.tinymce')
