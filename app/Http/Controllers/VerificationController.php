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

        return preg_match('/^P\d{3}$/', $code) ? $code : null;
    }

    protected function normalizeSecurityCode(string $code): ?string
    {
        $code = preg_replace('/\D/', '', trim($code)) ?: '';

        if ($code === '') {
            return null;
        }

        return preg_match('/^\d{6}$/', $code) ? $code : null;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $code = $request->query('code');
        $securityCode = $request->query('security_code');

        if ($code || $securityCode) {
            $normalized = $this->normalizeCode((string) $code);
            $normalizedSecurityCode = $this->normalizeSecurityCode((string) $securityCode);

            if ($normalized && $normalizedSecurityCode) {
                $card = VerificationCard::where('code', $normalized)
                    ->where('security_code', $normalizedSecurityCode)
                    ->first();

                if ($card) {
                    return redirect()->route('verify.show', [
                        'locale' => $locale,
                        'verificationCard' => $card,
                        'securityCode' => $normalizedSecurityCode,
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

        $securityCode = $this->normalizeSecurityCode((string) $request->query('securityCode', ''));

        if (! $securityCode || $securityCode !== $verificationCard->security_code) {
            return redirect()
                ->route('verify', ['locale' => $locale])
                ->withInput($request->query())
                ->withErrors(['code' => __('messages.verify_not_found')]);
        }

        return view('pages.verify-profile', [
            'locale' => $locale,
            'card' => $verificationCard,
        ]);
    }
}
