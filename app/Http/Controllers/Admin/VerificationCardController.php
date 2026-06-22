<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class VerificationCardController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);

        return $locale;
    }

    protected function generateSecurityCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (VerificationCard::where('security_code', $code)->exists());

        return $code;
    }

    public function index(Request $request)
    {
        $locale = $this->setLocale($request);
        $search = trim((string) $request->query('search', ''));

        $query = VerificationCard::query()->latest('created_at');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('occupation', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%')
                    ->orWhere('security_code', 'like', '%'.$search.'%');
            });
        }

        $cards = $query->paginate(50)->withQueryString();

        return view('admin.verifications.index', compact('cards', 'locale', 'search'));
    }

    public function create(Request $request)
    {
        $locale = $this->setLocale($request);

        return view('admin.verifications.create', compact('locale'));
    }

    public function store(Request $request)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'code' => ['required', 'string', 'max:4', 'regex:/^P\d{3}$/', 'unique:verification_cards,code'],
            'security_code' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'profile_org' => ['nullable', 'string', 'max:255'],
            'short_bio' => ['nullable', 'string'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'about_text' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'timeline' => ['nullable', 'string'],
            'quote_text' => ['nullable', 'string'],
            'full_name' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'photo' => ['required', 'image', 'max:10240'],
        ]);

        $attributes['code'] = strtoupper(trim($attributes['code']));
        $attributes['security_code'] = preg_replace('/\D/', '', (string) ($attributes['security_code'] ?? '')) ?: $this->generateSecurityCode();
        $attributes['issue_date'] = now()->toDateString();
        $attributes['expiry_date'] = now()->addYear()->toDateString();

        if ($request->hasFile('photo')) {
            $attributes['photo'] = $request->file('photo')->store('verification-cards', 'public');
        }

        VerificationCard::create($attributes);

        return Redirect::route('admin.verifications.index', ['locale' => $locale])
            ->with('success', __('messages.verification_card_saved'));
    }

    public function edit(Request $request, $locale, VerificationCard $verification)
    {
        $locale = $this->setLocale($request);

        return view('admin.verifications.edit', compact('verification', 'locale'));
    }

    public function update(Request $request, $locale, VerificationCard $verification)
    {
        $locale = $this->setLocale($request);

        $attributes = $request->validate([
            'code' => ['required', 'string', 'max:4', 'regex:/^P\d{3}$/', 'unique:verification_cards,code,'.$verification->id],
            'security_code' => ['required', 'string', 'regex:/^\d{6}$/'],
            'profile_org' => ['nullable', 'string', 'max:255'],
            'short_bio' => ['nullable', 'string'],
            'current_position' => ['nullable', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'about_text' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'timeline' => ['nullable', 'string'],
            'quote_text' => ['nullable', 'string'],
            'full_name' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $attributes['code'] = strtoupper(trim($attributes['code']));
        $attributes['security_code'] = preg_replace('/\D/', '', (string) $attributes['security_code']) ?: '';

        if ($request->hasFile('photo')) {
            if ($verification->photo) {
                Storage::disk('public')->delete($verification->photo);
            }

            $attributes['photo'] = $request->file('photo')->store('verification-cards', 'public');
        }

        $verification->update($attributes);

        return Redirect::route('admin.verifications.index', ['locale' => $locale])
            ->with('success', __('messages.verification_card_saved'));
    }

    public function destroy(Request $request, $locale, VerificationCard $verification)
    {
        $locale = $this->setLocale($request);

        if ($verification->photo) {
            Storage::disk('public')->delete($verification->photo);
        }

        $verification->delete();

        return Redirect::route('admin.verifications.index', ['locale' => $locale])
            ->with('success', __('messages.verification_card_deleted'));
    }
}
