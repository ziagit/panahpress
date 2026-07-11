<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('messages.admin_panel') }} | {{ __('messages.site_name') }}</title>
    <style>
        :root {
            color-scheme: light;
            color: #0f172a;
            background: #f1f5f9;
            --iransans: 'XB Niloofar', Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-family: var(--iransans);
            font-size: 17px;
            line-height: 1.6;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        html[lang="fa"] {
            font-size: 18px;
        }
        html[lang="fa"] :is(h1, h2, h3, h4, h5, h6) {
            line-height: 1.35 !important;
        }
        body { margin: 0; min-height: 100vh; background: #f1f5f9; color: #0f172a; }
        body { font-family: var(--iransans); }
        body[dir="rtl"] { direction: rtl; }
        img { max-width: 100%; display: block; }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; }

        .admin-shell {
            display: grid;
            grid-template-columns: 270px minmax(0, 1fr);
            min-height: 100vh;
        }

        .admin-sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: #0f172a;
            padding: 1.25rem;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            border-inline-end: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 12px 0 30px rgba(15, 23, 42, 0.04);
        }

        .admin-brand {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.7rem;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .admin-brand img {
            width: 42px;
            height: 42px;
            border-radius: 0.4rem;
            object-fit: contain;
            background: #fff;
        }

        .admin-brand strong {
            display: block;
            font-size: 1rem;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .admin-brand span {
            display: block;
            color: #64748b;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.15rem;
        }

        .admin-nav {
            display: grid;
            gap: 0.4rem;
        }

        .admin-nav a,
        .admin-nav button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.85rem 0.95rem;
            border-radius: 0.9rem;
            border: 1px solid transparent;
            background: transparent;
            color: #334155;
            cursor: pointer;
            text-align: start;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .admin-nav a:hover,
        .admin-nav button:hover {
            background: #eef2ff;
            color: #0f172a;
            transform: translateX(2px);
        }

        .admin-nav a.active {
            background: #dbeafe;
            color: #1d4ed8;
            border-color: rgba(29, 78, 216, 0.12);
            font-weight: 700;
        }

        .admin-sidebar .spacer {
            flex: 1;
        }

        .admin-main {
            min-width: 0;
            padding: 1.5rem;
        }

        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .admin-topbar .crumbs {
            color: #64748b;
            font-size: 0.95rem;
        }

        .admin-topbar .title {
            margin: 0.2rem 0 0;
            font-size: clamp(1.25rem, 1.8vw, 1.8rem);
            letter-spacing: -0.03em;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.85rem 1.15rem;
            border-radius: 999px;
            background: #0f172a;
            color: #fff;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .button:hover { transform: translateY(-1px); background: #1e293b; }

        .badge { display:inline-flex; align-items:center; gap:0.4rem; padding:0.5rem 0.8rem; background:#e2e8f0; color:#0f172a; border-radius:999px; font-weight:700; font-size:0.84rem; }
        .text-muted { color:#64748b; }

        .panel-card,
        .auth-card,
        .auth-visual,
        .table-card {
            background: #fff;
            border-radius: 1.4rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        }

        .panel-card { padding: 1.5rem; }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .panel-header h1 {
            margin: 0.45rem 0 0;
            font-size: clamp(1.4rem, 2vw, 1.8rem);
            letter-spacing: -0.03em;
        }

        .panel-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .auth-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(320px, 420px);
            gap: 1rem;
        }

        .auth-visual,
        .auth-card {
            padding: 1.4rem;
            border-radius: 1.2rem;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .auth-visual {
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, 0.16), transparent 36%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .auth-visual h1 {
            margin: 0.7rem 0 0.5rem;
            font-size: clamp(1.4rem, 1.9vw, 1.9rem);
        }

        .auth-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .auth-badges span {
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            color: #334155;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin: 1rem 0 1.5rem;
        }

        .stat-card {
            padding: 1rem 1.1rem;
            border-radius: 1rem;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .stat-card .label {
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
        }

        .stat-card .value {
            margin-top: 0.35rem;
            font-size: 1.35rem;
            font-weight: 800;
        }

        .table-card { overflow: hidden; }
        .table-wrap { overflow-x: auto; }

        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td {
            text-align: start;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            vertical-align: top;
        }

        .admin-pagination-wrapper {
            overflow-x: auto;
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background: #fff;
            border-radius: 0.9rem;
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .admin-pagination-wrapper nav[role="navigation"] {
            width: 100%;
        }

        .admin-pagination-wrapper nav[role="navigation"] p {
            display: none;
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:first-child {
            display: none !important;
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:last-child {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:last-child a,
        .admin-pagination-wrapper nav[role="navigation"] > div:last-child span[aria-current="page"] span,
        .admin-pagination-wrapper nav[role="navigation"] > div:last-child span[aria-disabled="true"] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.4rem;
            min-height: 2.4rem;
            padding: 0 0.9rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: #fff;
            color: #334155;
            text-decoration: none;
            font-size: 0.93rem;
            font-weight: 600;
            transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:last-child a:hover {
            background: #f8fafc;
            border-color: rgba(148, 163, 184, 0.5);
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:last-child span[aria-current="page"] span {
            background: #0f172a !important;
            color: #fff !important;
            border-color: transparent !important;
        }

        .admin-pagination-wrapper nav[role="navigation"] > div:last-child span[aria-disabled="true"] span {
            opacity: 0.55;
            cursor: not-allowed;
            background: #f1f5f9 !important;
            color: #64748b !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
        }

        .admin-pagination-wrapper nav[role="navigation"] svg {
            width: 1rem;
            height: 1rem;
        }
        .table th {
            background: #f8fafc;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
        }

        .form-field {
            display: grid;
            gap: 0.45rem;
            margin-bottom: 1rem;
        }

        .form-field label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .form-field input,
        .form-field textarea,
        .form-field select {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: 0.9rem;
            border: 1px solid rgba(15, 23, 42, 0.14);
            background: #fff;
            color: #0f172a;
            outline: none;
        }

        .form-field input:focus,
        .form-field textarea:focus,
        .form-field select:focus {
            border-color: rgba(15, 23, 42, 0.45);
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
        }

        .editor-help {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0.35rem 0 0;
        }

        @media (max-width: 1080px) {
            .admin-shell { grid-template-columns: 220px minmax(0, 1fr); }
        }

        @media (max-width: 900px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-sidebar {
                position: relative;
                height: auto;
                border-radius: 0 0 1.2rem 1.2rem;
                border-inline-end: none;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
            }
            .admin-nav { gap: 0.35rem; }
            .admin-nav a,
            .admin-nav button { padding: 0.85rem 0.9rem; }
            .stats-grid { display: none; }
            .panel-header,
            .admin-topbar { flex-direction: column; align-items: flex-start; }
            .panel-actions { justify-content: flex-start; width: 100%; }
            .panel-actions .button { width: 100%; max-width: 320px; }
            .auth-layout { grid-template-columns: 1fr; }
            .sidebar-panel { padding: 1rem; }
            .form-field input,
            .form-field textarea,
            .form-field select { min-width: 0; }
            .admin-topbar .title { font-size: clamp(1.45rem, 4vw, 2rem); }
            .admin-brand { gap: 0.5rem; }
        }

        @media (max-width: 640px) {
            .admin-sidebar { padding: 1rem; }
            .admin-nav a,
            .admin-nav button { gap: 0.6rem; font-size: 0.94rem; }
            .panel-header,
            .admin-topbar { gap: 0.75rem; }
            .panel-actions { flex-direction: column; align-items: stretch; }
            .panel-actions .button { width: 100%; }
            .post-sidebar .sidebar-panel { padding: 1rem; }
        }
    </style>
</head>
<body>
    @php
        $currentUser = auth()->user();
        $adminNav = [
            ['label' => __('messages.posts'), 'route' => 'admin.posts.index'],
            ['label' => __('messages.categories'), 'route' => 'admin.categories.index'],
        ];

        if ($currentUser?->isAdmin()) {
            $adminNav[] = ['label' => __('messages.authors'), 'route' => 'admin.authors.index'];
            $adminNav[] = ['label' => __('messages.verifications'), 'route' => 'admin.verifications.index'];
        }

        if ($currentUser) {
            $adminNav[] = ['label' => __('messages.profile'), 'route' => 'admin.profile.edit'];
        }
    @endphp
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="{{ route('admin.posts.index', ['locale' => app()->getLocale()]) }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                <div>
                    <strong>Panahpress</strong>
                    <span>{{ __('messages.admin_panel') }}</span>
                </div>
            </a>

            <nav class="admin-nav" aria-label="Admin navigation">
                @foreach($adminNav as $item)
                    <a href="{{ route($item['route'], ['locale' => app()->getLocale()]) }}" @class(['active' => request()->routeIs($item['route'], $item['route'].'.*')])>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="spacer"></div>

            <div class="admin-nav">
                <form method="POST" action="{{ route('logout', ['locale' => app()->getLocale()]) }}">
                    @csrf
                    <button type="submit">{{ __('messages.logout') }}</button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar">
                <div>
                    <div class="crumbs">{{ __('messages.admin_panel') }}</div>
                    <h1 class="title">{{ $pageTitle ?? __('messages.admin_panel') }}</h1>
                </div>
            </div>

            @if(session('success'))
                <div class="alert" style="margin:0 0 1rem;padding:1rem 1.25rem;background:#e6fffa;color:#0f766e;border-radius:1rem;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert" style="margin:0 0 1rem;padding:1rem 1.25rem;background:#fee2e2;color:#991b1b;border-radius:1rem;">
                    <ul style="margin:0;padding-left:1.1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                var customConfirmMessage = form.getAttribute('data-confirm-message');
                var methodInput = form.querySelector('input[name="_method"][type="hidden"]');
                if (customConfirmMessage) {
                    form.addEventListener('submit', function(event) {
                        var confirmed = confirm(customConfirmMessage);
                        if (!confirmed) {
                            event.preventDefault();
                        }
                    });
                } else if (methodInput && methodInput.value.toUpperCase() === 'DELETE') {
                    form.addEventListener('submit', function(event) {
                        var confirmed = confirm('Are you sure you want to delete this item?');
                        if (!confirmed) {
                            event.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
