<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
class BannerController extends Controller {
    public function index(string $locale) { return view('admin.banners', ['locale' => $locale, 'banners' => Banner::all()->keyBy('placement')]); }
    public function update(Request $request, string $locale, string $placement) {
        abort_unless(in_array($placement, ['home', 'news']), 404);
        $banner = Banner::where('placement', $placement)->first();
        $data = $request->validate(['image' => ($banner ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png,webp,gif|max:5120', 'link' => 'nullable|url:http,https|max:2048']);
        $path = $request->hasFile('image') ? $request->file('image')->store('ads', 'public') : $banner->image_path;
        Banner::updateOrCreate(['placement' => $placement], ['image_path' => $path, 'alt' => 'Advertisement', 'link' => $data['link'] ?? null]);
        return redirect()->route('admin.banners.index', ['locale' => $locale])->with('success', 'Banner updated.');
    }
}
