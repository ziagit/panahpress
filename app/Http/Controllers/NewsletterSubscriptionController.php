<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    public function store(Request $request)
    {
        $locale = $this->setLocale($request);
        $message = null;

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first('email');

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => $message,
                ], 422);
            }

            return back()
                ->withInput()
                ->with('newsletter_error', $message);
        }

        $attributes = $validator->validated();

        $subscriber = NewsletterSubscriber::firstOrNew([
            'email' => strtolower($attributes['email']),
        ]);

        $alreadySubscribed = $subscriber->exists;

        $subscriber->fill([
            'locale' => $locale,
            'source' => $request->input('source', 'public-site'),
        ]);

        $subscriber->save();

        $message = $alreadySubscribed
            ? __('messages.newsletter_already_subscribed')
            : __('messages.newsletter_subscribed');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
            ]);
        }

        return back()->with('newsletter_success', $message);
    }
}
