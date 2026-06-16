<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);

        return $locale;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $search = trim((string) $request->query('search', ''));

        $query = User::query()
            ->withCount('posts')
            ->where('role', 'author')
            ->orderBy('created_at', 'desc');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $authors = $query->paginate(20)->withQueryString();

        return view('admin.authors.index', compact('authors', 'locale', 'search'));
    }

    public function create(Request $request)
    {
        $locale = $this->setLocale($request);

        return view('admin.authors.create', compact('locale'));
    }

    public function store(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ]);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'role' => 'author',
            'avatar' => $avatarPath,
        ]);

        return Redirect::route('admin.authors.index', ['locale' => $locale])
            ->with('success', __('messages.author_saved'));
    }

    public function edit(Request $request, $locale, User $author)
    {
        $locale = $this->setLocale($request);

        abort_unless($author->role === 'author', 404);

        return view('admin.authors.edit', compact('author', 'locale'));
    }

    public function update(Request $request, $locale, User $author)
    {
        $locale = $this->setLocale($request);

        abort_unless($author->role === 'author', 404);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($author->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ]);

        $author->name = $attributes['name'];
        $author->email = $attributes['email'];

        if ($request->hasFile('avatar')) {
            if ($author->avatar) {
                Storage::disk('public')->delete($author->avatar);
            }

            $author->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if (! empty($attributes['password'])) {
            $author->password = Hash::make($attributes['password']);
        }

        $author->save();

        return Redirect::route('admin.authors.index', ['locale' => $locale])
            ->with('success', __('messages.author_saved'));
    }
}
