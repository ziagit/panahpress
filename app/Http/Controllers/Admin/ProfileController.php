<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);

        return $locale;
    }

    public function edit(Request $request)
    {
        $locale = $this->setLocale($request);
        $user = $request->user();

        return view('admin.profile.edit', compact('locale', 'user'));
    }

    public function update(Request $request)
    {
        $locale = $this->setLocale($request);
        $user = $request->user();

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($attributes);

        return back()->with('success', __('messages.profile_saved'));
    }

    public function updatePassword(Request $request)
    {
        $locale = $this->setLocale($request);
        $user = $request->user();

        $attributes = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($attributes['password']),
        ]);

        return back()->with('success', __('messages.password_changed'));
    }
}
