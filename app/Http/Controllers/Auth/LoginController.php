<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    public function showLoginForm(Request $request)
    {
        $locale = $this->setLocale($request);

        return view('auth.login', compact('locale'));
    }

    public function login(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($attributes, $request->boolean('remember')))
        {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.posts.index', ['locale' => $locale]));
        }

        return back()
            ->withErrors(['email' => __('messages.invalid_login')])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home', ['locale' => $this->setLocale($request)]);
    }
}
