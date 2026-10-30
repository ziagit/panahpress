<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateVerificationController extends Controller
{
    public function index(Request $request, string $locale)
    {
        app()->setLocale($locale);
        if ($request->has('number') || $request->has('verification_code')) {
            $data = $request->validate(['number' => 'required|string|max:100', 'verification_code' => 'required|string|size:48']);
            $certificate = Certificate::where('number', strtoupper(trim($data['number'])))->where('verification_token', $data['verification_code'])->first();
            if (! $certificate) {
                return redirect()->route('certificates.verify', compact('locale'))->withErrors(['number' => __('certificates.not_found')]);
            }

            return redirect()->route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]);
        }

        return view('pages.verify-certificate', compact('locale'));
    }

    public function show(string $locale, string $token)
    {
        app()->setLocale($locale);
        $certificate = Certificate::where('verification_token', $token)->firstOrFail();

        return response()->view('pages.certificate-result', compact('certificate', 'locale'))->header('X-Robots-Tag', 'noindex, nofollow')->header('Referrer-Policy', 'no-referrer');
    }

    public function image(string $locale, string $token)
    {
        $certificate = Certificate::where('verification_token', $token)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($certificate->image_path), 404);

        return response()->file(Storage::disk('local')->path($certificate->image_path), ['X-Robots-Tag' => 'noindex, nofollow', 'Referrer-Policy' => 'no-referrer']);
    }
}
