@extends('layouts.admin')
@php($pageTitle = 'Banners')
@section('content')
<section class="panel-card">
<h1>Banners</h1>
<p>Upload a banner for each placement. Changes apply to both languages. JPG, PNG, WebP or GIF, up to 5 MB.</p>
@if(session('success')) <p role="status">{{ session('success') }}</p> @endif
@if($errors->any()) <div role="alert">{{ $errors->first() }}</div> @endif
@foreach(['home' => 'Home page', 'news' => 'News details sidebar'] as $placement => $label)
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.banners.update', ['locale' => $locale, 'placement' => $placement]) }}" style="margin:24px 0">
@csrf @method('PUT')
<h2>{{ $label }}</h2>
<div style="max-width:650px;margin-bottom:16px"><x-banner :placement="$placement" /></div>
<label>Banner image <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" @required(!$banners->has($placement))></label>
<label>Destination link (optional) <input type="url" name="link" maxlength="2048" placeholder="https://example.com" value="{{ $banners->get($placement)?->link }}"></label>
<p>Leave the link empty for a non-clickable banner. You can update the link without uploading the image again.</p>
<button type="submit" class="btn btn-primary">Save banner</button>
</form>
@endforeach
</section>
@endsection
