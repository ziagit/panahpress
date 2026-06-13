<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    public function showLinkRequestForm(Request $request)
    {
        $locale = $this->setLocale($request);

        return view('auth.forgot-password', compact('locale'));
    }

    public function sendResetLink(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $attributes['email'])->first();

        if ($user) {
            $token = Password::broker()->createToken($user);

            $user->notify(new ResetPasswordNotification($token, $locale));
        }

        return back()->with('status', __('messages.password_reset_link_sent'));
    }

    public function showResetForm(Request $request, string $locale, string $token)
    {
        $locale = $this->setLocale($request);

        return view('auth.reset-password', [
            'locale' => $locale,
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker()->reset(
            [
                'email' => $attributes['email'],
                'token' => $attributes['token'],
                'password' => $attributes['password'],
                'password_confirmation' => $request->input('password_confirmation'),
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors([
                'email' => __($status),
            ]);
        }

        $user = User::where('email', $attributes['email'])->first();

        if ($user) {
            Auth::login($user);
        }

        return redirect()->route('admin.posts.index', ['locale' => $locale])
            ->with('success', __('messages.password_reset_success'));
    }
}
