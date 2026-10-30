<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    public function qr(string $locale, Certificate $certificate)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(360),
            new SvgImageBackEnd
        );
        $url = route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]);
        $svg = (new Writer($renderer))->writeString($url);

        return response($svg)->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="certificate-'.$certificate->number.'-qr.svg"');
    }

    public function index(Request $request, string $locale)
    {
        app()->setLocale($locale);
        $search = trim((string) $request->query('search', ''));
        $certificates = Certificate::query()->when($search !== '', function ($query) use ($search) {
            $query->where(fn ($q) => $q->where('number', 'like', '%'.$search.'%')->orWhere('recipient_name', 'like', '%'.$search.'%'));
        })->latest()->paginate(25)->withQueryString();

        return view('admin.certificates.index', compact('locale', 'certificates', 'search'));
    }

    public function create(string $locale)
    {
        app()->setLocale($locale);

        return view('admin.certificates.form', ['locale' => $locale, 'certificate' => null]);
    }

    public function edit(string $locale, Certificate $certificate)
    {
        app()->setLocale($locale);

        return view('admin.certificates.form', compact('locale', 'certificate'));
    }

    private function attributes(Request $request, ?Certificate $certificate = null): array
    {
        $request->merge(['number' => strtoupper(trim((string) $request->input('number')))]);
        $data = $request->validate([
            'number' => ['required', 'string', 'max:100', 'regex:/^[A-Z0-9][A-Z0-9-]*$/', Rule::unique('certificates')->ignore($certificate)],
            'recipient_name' => 'required|string|max:255', 'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000', 'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date', 'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'image' => [$certificate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'is_revoked' => 'sometimes|boolean',
        ]);
        unset($data['image']);
        $data['is_revoked'] = $request->boolean('is_revoked');

        return $data;
    }

    public function store(Request $request, string $locale)
    {
        app()->setLocale($locale);
        $data = $this->attributes($request);
        $path = $request->file('image')->store('certificates', 'local');
        try {
            Certificate::create([...$data, 'image_path' => $path]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        return redirect()->route('admin.certificates.index', compact('locale'))->with('success', __('certificates.saved'));
    }

    public function update(Request $request, string $locale, Certificate $certificate)
    {
        app()->setLocale($locale);
        $data = $this->attributes($request, $certificate);
        $oldPath = $certificate->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('certificates', 'local');
        }
        try {
            $certificate->update($data);
        } catch (\Throwable $e) {
            if (isset($data['image_path'])) {
                Storage::disk('local')->delete($data['image_path']);
            }
            throw $e;
        }
        if (isset($data['image_path'])) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()->route('admin.certificates.index', compact('locale'))->with('success', __('certificates.saved'));
    }
}
