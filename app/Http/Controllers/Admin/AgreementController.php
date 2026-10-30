<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function index(string $locale)
    {
        return view('admin.agreements', ['agreements' => Agreement::latest()->paginate(20)]);
    }

    public function store(Request $request, string $locale)
    {
        $data = $request->validate([
            'person_name' => ['required', 'string', 'max:255'],
            'agreement' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);
        $file = $request->file('agreement');
        $path = $file->store('agreements', 'local');
        abort_unless($path, 500, 'The agreement could not be uploaded.');
        try {
            Agreement::create([
                'person_name' => $data['person_name'],
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }
        return redirect()->route('admin.agreements.index', ['locale' => $locale])->with('success', 'Agreement added.');
    }

    public function download(string $locale, Agreement $agreement)
    {
        abort_unless(Storage::disk('local')->exists($agreement->file_path), 404);
        return Storage::disk('local')->download($agreement->file_path, $agreement->original_name);
    }
}
