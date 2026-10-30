@extends('layouts.admin', ['pageTitle' => 'Banners'])

@section('content')
<style>
    .banner-intro { margin: 0 0 1.25rem; color: #64748b; font-size: .9rem; line-height: 1.6; }
    .banner-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; }
    .banner-card { min-width: 0; padding: 1.25rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; }
    .banner-card-header { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: 1rem; }
    .banner-card h2 { margin: 0; font-size: 1.05rem; }
    .banner-status { padding: .3rem .6rem; border-radius: 999px; background: #f1f5f9; color: #64748b; font-size: .75rem; white-space: nowrap; }
    .banner-status.is-uploaded { background: #ecfdf5; color: #047857; }
    .banner-preview { display: flex; align-items: center; justify-content: center; height: 180px; padding: .75rem; margin-bottom: 1.1rem; overflow: hidden; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: .75rem; }
    .banner-preview img { display: block; max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; }
    .banner-empty { text-align: center; color: #94a3b8; font-size: .85rem; }
    .banner-card .form-field { margin-bottom: .85rem; }
    .banner-card .form-field label { font-size: .85rem; }
    .banner-card .form-field input { box-sizing: border-box; min-width: 0; padding: .7rem .8rem; border-radius: .65rem; font-size: .85rem; }
    .banner-card .editor-help { margin: .25rem 0 0; font-size: .78rem; line-height: 1.5; }
    .banner-card-footer { display: flex; justify-content: flex-end; margin-top: 1.1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .banner-card .button { padding: .65rem 1rem; border-radius: .65rem; font-size: .85rem; }
    @media (max-width: 1100px) { .banner-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .banner-card { padding: 1rem; } .banner-preview { height: 150px; } }
</style>
<p class="banner-intro">Manage banners for your home page and news sidebar. Updates appear in both languages.</p>
<div class="banner-grid">
    @foreach(['home' => 'Home page', 'news' => 'News details sidebar'] as $placement => $label)
        @php($banner = $banners->get($placement))
        <form class="banner-card" method="POST" enctype="multipart/form-data" action="{{ route('admin.banners.update', ['locale' => $locale, 'placement' => $placement]) }}">
            @csrf
            @method('PUT')
            <div class="banner-card-header">
                <h2>{{ $label }}</h2>
                <span class="banner-status {{ $banner ? 'is-uploaded' : '' }}">{{ $banner ? 'Uploaded' : ($placement === 'home' ? 'Default banner' : 'No banner') }}</span>
            </div>
            <div class="banner-preview">
                @if($banner)
                    <img src="{{ route('banners.image', $banner) }}" alt="{{ $label }} banner preview">
                @elseif($placement === 'home')
                    <img src="{{ route('ads.banner.2nd-anniversary') }}" alt="Default home page banner">
                @else
                    <span class="banner-empty">Upload an image to add a sidebar banner</span>
                @endif
            </div>
            <div class="form-field">
                <label for="image-{{ $placement }}">{{ $banner ? 'Replace banner image' : 'Banner image' }}</label>
                <input id="image-{{ $placement }}" type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" aria-describedby="image-help-{{ $placement }}" @required(!$banner)>
                <p id="image-help-{{ $placement }}" class="editor-help">JPG, PNG, WebP or GIF · Up to 5 MB</p>
            </div>
            <div class="form-field">
                <label for="link-{{ $placement }}">Destination link <span class="text-muted">(optional)</span></label>
                <input id="link-{{ $placement }}" type="url" name="link" maxlength="2048" placeholder="https://example.com" value="{{ $banner?->link }}" aria-describedby="link-help-{{ $placement }}">
                <p id="link-help-{{ $placement }}" class="editor-help">Leave empty for a non-clickable banner. {{ $banner ? 'Keep the current image by leaving the upload empty.' : '' }}</p>
            </div>
            <div class="banner-card-footer"><button type="submit" class="button">Save banner</button></div>
        </form>
    @endforeach
</div>
@endsection
