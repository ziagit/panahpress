<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);

        return $locale;
    }

    protected function resolvePost(mixed $post): Post
    {
        if ($post instanceof Post) {
            return $post;
        }

        $post = trim((string) $post);

        return Post::query()
            ->whereKey($post)
            ->orWhere('slug', $post)
            ->firstOrFail();
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $search = trim((string) $request->query('search', ''));

        $query = Post::with('category')->latest('created_at');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title_en', 'like', '%'.$search.'%')
                    ->orWhere('title_fa', 'like', '%'.$search.'%');
            });
        }

        $statsPosts = (clone $query)->get();
        $posts = $query->paginate(50)->withQueryString();

        return view('admin.posts.index', compact('posts', 'locale', 'search', 'statsPosts'));
    }

    public function create(Request $request)
    {
        $locale = $this->setLocale($request);
        $categories = Category::ordered()->get();

        return view('admin.posts.create', compact('locale', 'categories'));
    }

    public function store(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_fa' => ['required', 'string', 'max:255'],
            'content_en' => ['required', 'string'],
            'content_fa' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);

        $attributes['slug'] = Str::slug($attributes['title_en']) ?: Str::slug($attributes['title_fa']) ?: (string) time();
        $attributes['published_at'] = $attributes['published_at'] ?: now();

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('uploads', 'public');
        }

        Post::create($attributes);

        return Redirect::route('admin.posts.index', ['locale' => $locale])
            ->with('success', __('messages.post_saved'));
    }

    public function edit(Request $request, $locale, $post)
    {
        $locale = $this->setLocale($request);
        $post = $this->resolvePost($post);
        $categories = Category::ordered()->get();
    
        return view('admin.posts.edit', compact('post', 'locale', 'categories'));
    }

    public function update(Request $request, $locale, $post)
    {
        $locale = $this->setLocale($request);
        $post = $this->resolvePost($post);
    
        $attributes = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_fa' => ['required', 'string', 'max:255'],
            'content_en' => ['required', 'string'],
            'content_fa' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);
    
        $attributes['slug'] = Str::slug($attributes['title_en']) ?: Str::slug($attributes['title_fa']) ?: $post->slug;
        $attributes['published_at'] = $attributes['published_at'] ?: now();

        if ($request->hasFile('image')) {
            // delete old file if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $attributes['image'] = $request->file('image')->store('uploads', 'public');
        }
    
        $post->update($attributes);
    
        return Redirect::route('admin.posts.index', ['locale' => $locale])
            ->with('success', __('messages.post_saved'));
    }

    public function destroy(Request $request, $locale, $post)
    {
        $locale = $this->setLocale($request);
        $post = $this->resolvePost($post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
    
        $post->delete();
    
        return Redirect::route('admin.posts.index', ['locale' => $locale])
            ->with('success', __('messages.post_deleted'));
    }
}
