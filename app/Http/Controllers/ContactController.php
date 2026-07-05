<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => $message,
                ], 422);
            }

            return back()
                ->withInput()
                ->with('contact_error', $message);
        }

        $attributes = $validator->validated();

        Mail::to('contact@panahpress.com')->send(
            new ContactFormMessage(
                appLocale: $locale,
                name: $attributes['name'],
                email: strtolower($attributes['email']),
                subjectLine: $attributes['subject'],
                messageBody: $attributes['message'],
            )
        );

        $message = __('messages.contact_form_sent');

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
            ]);
        }

        return back()->with('contact_success', $message);
    }
}
