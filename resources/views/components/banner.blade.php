@props(['placement'])
@php($banner = \App\Models\Banner::where('placement', $placement)->first())
@if($banner)
@if($banner->link)<a href="{{ $banner->link }}" target="_blank" rel="noopener noreferrer" style="display:block">@endif
<img src="{{ route('banners.image', $banner) }}" alt="Advertisement" loading="lazy" style="display:block;width:100%;height:auto;">
@if($banner->link)</a>@endif
@elseif($placement === 'home')
<img src="{{ route('ads.banner.2nd-anniversary') }}" alt="Advertisement" class="home-ad-banner__image" loading="lazy">
@endif
