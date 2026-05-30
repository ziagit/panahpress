<?php

namespace App\Http\Controllers;

use App\Models\VerificationCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;

class VerificationController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    protected function normalizeCode(string $code): ?string
    {
        $code = strtoupper(trim($code));
        $code = preg_replace('/[^A-Z0-9]/', '', $code) ?: '';

        if ($code === '') {
            return null;
        }

        if (! str_starts_with($code, 'P')) {
            $code = 'P'.$code;
        }

        return preg_match('/^P\d{4}$/', $code) ? $code : null;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $code = $request->query('code');

        if ($code) {
            $normalized = $this->normalizeCode($code);

            if ($normalized) {
                $card = VerificationCard::where('code', $normalized)->first();

                if ($card) {
                    return redirect()->route('verify.show', [
                        'locale' => $locale,
                        'verificationCard' => $card,
                    ]);
                }
            }

            return back()
                ->withInput()
                ->withErrors(['code' => __('messages.verify_not_found')]);
        }

        return view('pages.verify', compact('locale'));
    }

    public function show(Request $request, $locale, VerificationCard $verificationCard)
    {
        $locale = $this->setLocale($request);

        return view('pages.verify-card', [
            'locale' => $locale,
            'card' => $verificationCard,
        ]);
    }
}
