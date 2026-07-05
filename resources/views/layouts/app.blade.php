<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('messages.site_name') }}</title>
    <style>
        @php
            $diroozWolVersion = @filemtime(public_path('fonts/dirooz/Without-Latin/Dirooz-WOL.woff2')) ?: time();
        @endphp
        @font-face {
            font-family: 'Dirooz WOL';
            src: url("{{ asset('fonts/dirooz/Without-Latin/Dirooz-WOL.woff2') }}?v={{ $diroozWolVersion }}") format('woff2'),
                 url("/fonts/dirooz/Without-Latin/Dirooz-WOL.woff2?v={{ $diroozWolVersion }}") format('woff2'),
                 url("{{ asset('fonts/dirooz/Without-Latin/Dirooz-WOL.woff') }}?v={{ $diroozWolVersion }}") format('woff');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Dirooz WOL';
            src: url("{{ asset('fonts/dirooz/Without-Latin/Dirooz-WOL.woff2') }}?v={{ $diroozWolVersion }}") format('woff2'),
                 url("/fonts/dirooz/Without-Latin/Dirooz-WOL.woff2?v={{ $diroozWolVersion }}") format('woff2'),
                 url("{{ asset('fonts/dirooz/Without-Latin/Dirooz-WOL.woff') }}?v={{ $diroozWolVersion }}") format('woff');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        :root {
            color-scheme: light;
            --brand-navy: #0f3b7a;
            --brand-navy-dark: #0c2f63;
            --brand-sky: #6ec1e4;
            --brand-sky-soft: #eef8fc;
            --surface: #ffffff;
            --page-bg: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --sans-base: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            --sans: var(--sans-base);
            --sans-fa: 'Dirooz WOL', var(--sans-base);
            color: var(--text);
            background: var(--page-bg);
            font-family: var(--sans);
            font-size: 16px;
            line-height: 1.6;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        html[lang="fa"] { --sans: var(--sans-fa); }
        body { margin: 0; min-height: 100vh; background: var(--page-bg); color: var(--text); }
        body { font-family: var(--sans); }
        html[lang="fa"] body,
        html[lang="fa"] body * {
            font-family: 'Dirooz WOL', var(--sans-base) !important;
        }
        html[dir="rtl"] body { direction: rtl; }
        img { max-width: 100%; display: block; }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            max-width: 1240px;
            margin: 0 auto;
            padding: 1rem 1rem 0;
        }

        .page-content {
            display: grid;
            gap: 1.5rem;
            padding-bottom: 3rem;
        }

        .site-header {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1rem;
            padding: 0.35rem 0 0.4rem;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand a {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #0f172a;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .brand-title {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.05rem;
            line-height: 1.05;
        }

        .brand-title strong {
            display: block;
            font-size: 1.18rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-title span {
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            color: #64748b;
            text-transform: uppercase;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            border-radius: 0;
            object-fit: contain;
            background: #fff;
            box-shadow: none;
            flex-shrink: 0;
            transform: scale(1.16);
            transform-origin: center;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .menu-bar {
            width: 100vw;
            margin-left: calc(50% - 50vw);
            background: #0ba8ea;
            color: #fff;
            box-shadow: none;
            margin-top: 0.9rem;
            overflow: visible;
            position: relative;
            z-index: 25;
        }

        .menu-bar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0.75rem 1rem;
        }

        .menu-bar-main {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .menu-bar-main > a,
        .menu-bar-main > button {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.15rem;
            border-radius: 0;
            color: #fff;
            font-weight: 700;
            background: transparent;
            border: 0;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            font: inherit;
            line-height: 1;
            transition: transform .2s ease, opacity .2s ease, color .2s ease;
        }

        .menu-bar-main > a:hover,
        .menu-bar-main > button:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .menu-bar-main > a::after,
        .menu-bar-main > button::after {
            content: '';
            display: block;
            position: absolute;
            inset-inline: 0;
            bottom: -0.18rem;
            height: 2px;
            background: rgba(255, 255, 255, 0.88);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform .22s ease;
        }

        .menu-dropdown {
            position: relative;
            padding-bottom: 0.45rem;
        }

        .menu-dropdown:hover > button::after,
        .menu-dropdown:focus-within > button::after {
            transform: scaleX(1);
        }

        .menu-submenu {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .menu-submenu a {
            color: #0f172a;
            font-size: 0.92rem;
            padding: 0.4rem 0.65rem;
            border-radius: 0;
            background: rgba(255, 255, 255, 0.38);
            border: 1px solid rgba(15, 23, 42, 0.08);
            transition: background .2s ease, transform .2s ease;
        }

        .menu-submenu a:hover {
            background: rgba(255, 255, 255, 0.7);
            transform: translateY(-1px);
        }

        .menu-dropdown-panel {
            position: absolute;
            top: calc(100% + 0.6rem);
            inset-inline-start: 0;
            min-width: 220px;
            background: #fff;
            color: #0f172a;
            border-radius: 0;
            box-shadow: 0 18px 40px rgba(15, 59, 122, 0.16);
            padding: 0.65rem;
            display: none;
            z-index: 120;
            border: 1px solid rgba(15, 59, 122, 0.08);
            backdrop-filter: blur(6px);
        }

        .menu-dropdown:hover .menu-dropdown-panel,
        .menu-dropdown:focus-within .menu-dropdown-panel {
            display: grid;
            gap: 0.25rem;
        }

        .menu-dropdown-panel a {
            display: block;
            padding: 0.65rem 0.75rem;
            border-radius: 0;
            color: #0f172a;
            font-weight: 600;
            transition: background .2s ease, transform .2s ease, color .2s ease;
        }

        .menu-dropdown-panel a:hover {
            background: var(--brand-sky-soft);
            transform: translateX(2px);
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-links a,
        .lang-switcher a {
            color: #334155;
            font-weight: 600;
            padding: 0.7rem 1rem;
            border-radius: 0;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-links a:hover,
        .lang-switcher a:hover {
            background: rgba(15, 23, 42, 0.06);
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.28rem;
            width: 46px;
            height: 46px;
            padding: 0;
            background: #fff;
            color: #0f172a;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 0;
            cursor: pointer;
            box-shadow: none;
        }

        .menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            border-radius: 0;
            background: currentColor;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.42);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            z-index: 40;
        }

        .mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            left: auto;
            width: min(86vw, 320px);
            height: 100vh;
            background: #fff;
            transform: translateX(100%);
            transition: transform 0.25s ease;
            z-index: 50;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: none;
            overflow-y: auto;
        }

        .drawer-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .drawer-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 0;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #fff;
            cursor: pointer;
            color: #0f172a;
            font-size: 1.2rem;
            line-height: 1;
        }

        .drawer-section {
            display: grid;
            gap: 0.75rem;
        }

        .drawer-section h3 {
            margin: 0;
            font-size: 0.95rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .drawer-links {
            display: grid;
            gap: 0.4rem;
        }

        .drawer-links a {
            display: block;
            padding: 0.9rem 1rem;
            border-radius: 0;
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
        }

        .drawer-links a:hover {
            background: var(--brand-sky-soft);
        }

        body.menu-open .drawer-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        body.menu-open .mobile-drawer {
            transform: translateX(0);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.85rem 1.2rem;
            background: var(--brand-navy);
            color: #fff;
            border-radius: 0;
            font-weight: 700;
            border: 1px solid transparent;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .button:hover { transform: translateY(-1px); background: var(--brand-navy-dark); }
        .badge { display: inline-flex; align-items:center; gap:0.4rem; padding:0.55rem 0.8rem; background:var(--brand-sky-soft); color:var(--brand-navy); border-radius:0; font-weight:700; font-size:0.85rem; }
        .text-muted { color:#64748b; }

        .hero { display:grid; grid-template-columns: 1.3fr 0.9fr; gap: 1.5rem; margin: 2rem 0 1.8rem; }
        .hero-card { background: #ffffff; border-radius: 0; padding: 1.5rem; box-shadow: none; border: 0; }
        .hero-card h1 { margin: 0.75rem 0 0.85rem; font-size: clamp(2rem, 2.8vw, 3rem); line-height: 1.05; }
        .hero-card p { margin: 0.85rem 0 0; color: #475569; }
        .hero-card .meta { display:flex; flex-wrap:wrap; gap:0.75rem; margin-top:1rem; color:#64748b; font-size:0.95rem; }
        .featured img { border-radius: 0; }
        .newsroom-home { display:grid; gap:2rem; }
        .news-featured-card {
            display:grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(320px, 0.9fr);
            gap: 1.5rem;
            align-items: stretch;
        }
        .news-featured-media img {
            width: 100%;
            height: 100%;
            min-height: 360px;
            object-fit: cover;
            border-radius: 0;
        }
        .news-featured-content {
            display:grid;
            gap:1rem;
            padding:1.2rem 0.25rem;
            align-content:start;
        }
        .news-kicker {
            display:inline-flex;
            align-items:center;
            gap:0.45rem;
            width: fit-content;
            padding:0.45rem 0.75rem;
            border-radius:0;
            background: var(--brand-navy);
            color:#fff;
            font-size:0.78rem;
            font-weight:700;
            letter-spacing:0.08em;
            text-transform:uppercase;
        }
        .news-featured-content h1 {
            margin:0;
            font-size: clamp(2.1rem, 3.8vw, 3.75rem);
            line-height:1.03;
            letter-spacing:-0.04em;
        }
        .news-featured-content .excerpt {
            color:#475569;
            font-size:1rem;
            line-height:1.75;
            max-width: 58ch;
        }
        .news-featured-meta {
            display:flex;
            flex-wrap:wrap;
            gap:0.75rem;
            color:#64748b;
            font-size:0.92rem;
        }
        .news-rail {
            display:grid;
            gap:0.95rem;
            align-content:start;
        }
        .news-rail-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            padding-bottom:0.9rem;
            border-bottom:1px solid rgba(15, 23, 42, 0.08);
        }
        .news-rail-header h3,
        .news-section-header h2 {
            margin:0;
            font-size:1.15rem;
            letter-spacing:-0.02em;
        }
        .story-rail {
            display:grid;
            gap:0.85rem;
        }
        .story-rail a,
        .story-item a {
            display:flex;
            gap:0.75rem;
            align-items:flex-start;
            padding:0.85rem;
            border-radius:0;
            background:#f8fafc;
            transition: background .2s ease, transform .2s ease;
        }
        .story-rail a:hover,
        .story-item a:hover {
            background: var(--brand-sky-soft);
            transform: translateY(-1px);
        }
        .story-rail img {
            width:84px;
            height:58px;
            object-fit:cover;
            border-radius:0;
            flex-shrink:0;
        }
        .story-rail-title {
            font-weight:700;
            color:#0f172a;
            line-height:1.35;
            display:-webkit-box;
            -webkit-line-clamp:3;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }
        .story-rail-meta {
            margin-top:0.25rem;
            color:#64748b;
            font-size:0.8rem;
        }
        .news-section {
            display:grid;
            gap:1rem;
        }
        .news-section-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            padding:0.2rem 0;
        }
        .news-section-grid {
            display:grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(320px, 0.75fr);
            gap:1rem;
        }
        .lead-story {
            display:grid;
            gap:0.9rem;
            border-radius:0;
            overflow:hidden;
            background:#fff;
            box-shadow: none;
            border: 0;
        }
        .lead-story img {
            width:100%;
            height:260px;
            object-fit:cover;
        }
        .lead-story-content {
            padding:1rem 1rem 1.2rem;
            display:grid;
            gap:0.65rem;
        }
        .lead-story-content h3 {
            margin:0;
            font-size:1.15rem;
            line-height:1.2;
        }
        .lead-story-content p {
            margin:0;
            color:#475569;
            line-height:1.7;
        }
        .news-list {
            display:grid;
            gap:0.85rem;
        }
        .story-item {
            background:#fff;
            border-radius:0;
            overflow:hidden;
            box-shadow: none;
            border: 0;
        }
        .story-item a {
            background:#fff;
            border-radius:0;
            align-items:center;
        }
        .story-item img {
            width:92px;
            height:72px;
            object-fit:cover;
            border-radius:0;
            flex-shrink:0;
        }
        .story-item-title {
            font-weight:700;
            color:#0f172a;
            line-height:1.35;
        }
        .story-item-excerpt {
            color:#64748b;
            font-size:0.88rem;
            line-height:1.5;
            margin-top:0.2rem;
            display:-webkit-box;
            -webkit-line-clamp:2;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }

        .section-title { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin: 2rem 0 1rem; }
        .section-title h2 { margin: 0; font-size: 1.35rem; }
        .grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; }
        .related-articles-grid { display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:1rem; }
        .card { background:white; border-radius:0; overflow:hidden; box-shadow:none; border:0; }
        .card-link { display:block; color: inherit; }
        .card .meta { padding: 1rem 1rem 1.2rem; }
        .card h3 { margin:0 0 0.65rem; font-size:1.05rem; }
        .card p { margin:0 0 0.9rem; color:#475569; font-size:0.95rem; line-height:1.65; }
        .card img { width:100%; height:220px; object-fit:cover; }

        .post-page { display:grid; grid-template-columns: 1.3fr 0.9fr; gap:2rem; margin-top:1.8rem; }
        .post-sidebar { display:grid; gap:1.5rem; }
        .post-main { display:grid; gap:1.5rem; }

        .footer {
            margin-top: auto;
            padding: 2rem 0 0;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            color: #475569;
            font-size: 0.95rem;
        }
        .footer-grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1.5rem; }
        .footer-grid h3 { margin:0 0 0.7rem; font-size:1rem; color:#0f172a; }
        .footer-links { display:grid; gap:0.55rem; }
        .footer-links a { color:#334155; }
        .footer-socials { display:flex; flex-wrap:wrap; gap:0.6rem; }
        .footer-socials a {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:42px;
            height:42px;
            border-radius:0;
            background:#f8fafc;
            border:1px solid rgba(15,23,42,0.08);
            color:#0f172a;
            transition: transform .2s ease, background .2s ease;
        }
        .footer-socials a:hover { background: var(--brand-sky-soft); transform: translateY(-1px); }
        .footer-socials svg { width:18px; height:18px; fill: currentColor; }
        .footer-bottom {
            margin-top: 1.5rem;
            padding-top: 0;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }

        .page-shell {
            display: grid;
            gap: 1.5rem;
        }

        .auth-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 460px);
            gap: 1.5rem;
            align-items: stretch;
            margin-top: 1.5rem;
        }

        .auth-visual,
        .auth-card,
        .panel-card,
        .stats-card,
        .table-card {
            background: #fff;
            border-radius: 0;
            box-shadow: none;
            border: 0;
        }

        .auth-visual {
            padding: 2rem;
            background:
                radial-gradient(circle at top left, rgba(110, 193, 228, 0.18), transparent 34%),
                radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.08), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
        }

        .auth-visual h1 {
            margin: 1rem 0 0.75rem;
            font-size: clamp(2rem, 3vw, 3.3rem);
            line-height: 1.02;
        }

        .auth-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .auth-badges span {
            padding: 0.55rem 0.8rem;
            border-radius: 0;
            background: rgba(255, 255, 255, 0.7);
            color: #334155;
            font-weight: 600;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .auth-card {
            padding: 2rem;
        }

        .panel-card {
            padding: 1.5rem;
        }

        .panel-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .panel-header h1 {
            margin: 0.5rem 0 0;
            font-size: clamp(1.8rem, 2.5vw, 2.6rem);
        }

        .panel-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin: 1rem 0 1.5rem;
        }

        .stat-card {
            padding: 1rem 1.15rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .stat-card .label {
            color: #64748b;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stat-card .value {
            margin-top: 0.35rem;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
        }

        .table-card {
            overflow: hidden;
            border-radius: 0;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: start;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            vertical-align: top;
        }

        .table th {
            font-size: 0.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
        }

        .table tbody tr:hover {
            background: rgba(248, 250, 252, 0.8);
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
            border-radius: 0;
            border: 1px solid rgba(15, 23, 42, 0.14);
            background: #fff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-field input:focus,
        .form-field textarea:focus,
        .form-field select:focus {
            border-color: rgba(15, 23, 42, 0.45);
            box-shadow: none;
            outline: 2px solid rgba(15, 59, 122, 0.25);
            outline-offset: 1px;
        }

        .editor-help {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0.35rem 0 0;
        }

        .post-content :is(h1, h2, h3, h4, h5, h6) {
            margin: 1.4em 0 0.55em;
            line-height: 1.15;
            color: #0f172a;
        }

        .post-content h1 { font-size: clamp(2rem, 3vw, 2.8rem); }
        .post-content h2 { font-size: 1.8rem; }
        .post-content h3 { font-size: 1.45rem; }
        .post-content p,
        .post-content ul,
        .post-content ol,
        .post-content blockquote,
        .post-content pre,
        .post-content table {
            margin: 0 0 1rem;
        }

        .post-content ul,
        .post-content ol {
            padding-inline-start: 1.5rem;
        }

        .post-content li {
            margin: 0.35rem 0;
        }

        .post-content blockquote {
            padding: 1rem 1.1rem;
            border-inline-start: 4px solid #cbd5e1;
            background: #f8fafc;
            color: #475569;
            border-radius: 0;
        }

        .post-content img {
            border-radius: 0;
            margin: 1.25rem 0;
        }

        .post-content iframe,
        .post-content video {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 16 / 9;
            height: auto;
            border: 0;
        }

        @media (max-width: 980px) {
            .hero, .post-page, .footer-grid, .grid { grid-template-columns: 1fr; }
            .related-articles-grid { grid-template-columns: 1fr; }
            .news-featured-card,
            .news-section-grid {
                grid-template-columns: 1fr;
            }
            .news-featured-media img {
                min-height: 260px;
            }
            .hero-card, .post-hero .post-meta, .post-content { padding: 1.5rem; }
            .auth-layout, .stats-grid { grid-template-columns: 1fr; }
            .site-header { grid-template-columns: 1fr auto; }
            .header-nav, .header-actions, .menu-bar { display: none; }
            .menu-toggle { display: inline-flex; }
        }

        @media (min-width: 981px) {
            .drawer-backdrop,
            .mobile-drawer {
                display: none;
            }
        }

        @media (max-width: 650px) {
            .site-header { padding-top: 0.35rem; padding-bottom: 0.35rem; }
            .nav-links a, .lang-switcher a { padding: 0.65rem 0.8rem; }
            .brand-logo { width: 50px; height: 50px; }
            .brand-logo { transform: scale(1.12); }
            .card img { height: 180px; }
            .hero { margin-top: 1rem; }
            .auth-card, .auth-visual, .panel-card { padding: 1.25rem; }
            .news-featured-content h1 { font-size: clamp(1.8rem, 8vw, 2.4rem); }
            .news-rail img,
            .story-item img {
                width: 76px;
                height: 56px;
            }
            .page { padding-inline: 0.75rem; }
        }
    </style>
</head>
<body>
    @php $menuCategories = collect($navCategories ?? [])->take(4); @endphp
    <div class="page">
        <header class="site-header">
            <div class="brand">
                <a class="brand-link" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                    <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                </a>
            </div>

            <div></div>

            <div class="header-actions">
                <div class="lang-switcher">
                    <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'en'])) }}">EN</a>
                    <span aria-hidden="true">|</span>
                    <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'fa'])) }}">FA</a>
                </div>
            </div>

            <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="mobile-drawer" aria-expanded="false" data-menu-toggle>
                <span></span>
                <span></span>
                <span></span>
            </button>
        </header>

        <nav class="menu-bar" aria-label="{{ __('messages.menu') }}">
            <div class="menu-bar-inner">
                <div class="menu-bar-main">
                    @foreach($menuCategories as $category)
                        @php $categoryMenuPosts = $category->posts()->published()->orderByRaw('COALESCE(published_at, created_at) desc')->take(4)->get(); @endphp
                        <div class="menu-dropdown">
                            <button type="button" aria-haspopup="true" aria-expanded="false">
                                {{ $category->name() }}
                                <span aria-hidden="true">▾</span>
                            </button>
                            <div class="menu-dropdown-panel">
                                <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category]) }}">{{ __('messages.all_sections') }}: {{ $category->name() }}</a>
                                @forelse($categoryMenuPosts as $post)
                                    <a href="{{ route('posts.show', ['locale' => app()->getLocale(), 'post' => $post->slug]) }}">{{ $post->title() }}</a>
                                @empty
                                    <span style="padding:0.65rem 0.75rem; color:#64748b; font-size:0.92rem;">{{ __('messages.no_posts') }}</span>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </nav>

        @if(session('success'))
            <div class="alert" style="margin:1rem 0;padding:1rem 1.25rem;background:#e6fffa;color:#0f766e;border-radius:0;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert" style="margin:1rem 0;padding:1rem 1.25rem;background:#fee2e2;color:#991b1b;border-radius:0;">
                <ul style="margin:0;padding-left:1.1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="page-content">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer-grid">
                <div>
                    <h3>{{ __('messages.site_name') }}</h3>
                    <p class="text-muted">{{ __('messages.tagline') }}</p>
                </div>
                <div>
                    <h3>{{ __('messages.categories') }}</h3>
                    <div class="footer-links">
                        @foreach($menuCategories as $category)
                            <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category]) }}">{{ $category->name() }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3>{{ __('messages.follow_us') }}</h3>
                    <div class="footer-socials" aria-label="{{ __('messages.follow_us') }}">
                        <a href="https://www.facebook.com/Panahpress" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8.2h2.76l.42-3.2h-3.18V8.57c0-.93.26-1.56 1.6-1.56h1.71V4.14c-.3-.04-1.35-.14-2.56-.14-2.53 0-4.26 1.54-4.26 4.36v2.44H7.2v3.2h2.79V22h3.5z"/></svg>
                        </a>
                        <a href="https://x.com/panah_press" target="_blank" rel="noopener noreferrer" aria-label="X">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.7 7.68L23.2 22h-6.8l-5.33-6.72L5.2 22H2l7.3-8.37L.96 2H7.9l4.87 6.1L18.9 2zm-1.2 18h1.8L6.9 3.94H5L17.7 20z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/panah_press" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 2A2.5 2.5 0 1 0 14.5 12 2.5 2.5 0 0 0 12 9.5zM17.75 6a1.25 1.25 0 1 1-1.25 1.25A1.25 1.25 0 0 1 17.75 6z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@panah_press" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a3 3 0 0 0-2.12-2.12C17.6 4.6 12 4.6 12 4.6s-5.6 0-7.48.48A3 3 0 0 0 2.4 7.2 31.7 31.7 0 0 0 2 12a31.7 31.7 0 0 0 .4 4.8 3 3 0 0 0 2.12 2.12C6.4 19.4 12 19.4 12 19.4s5.6 0 7.48-.48A3 3 0 0 0 21.6 16.8 31.7 31.7 0 0 0 22 12a31.7 31.7 0 0 0-.4-4.8zM10 15.2V8.8l5.5 3.2L10 15.2z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                {{ __('messages.copyright', ['year' => date('Y')]) }}
            </div>
        </footer>
    </div>

    <div class="drawer-backdrop" data-menu-close></div>
    <aside class="mobile-drawer" id="mobile-drawer" aria-label="Mobile navigation">
        <div class="drawer-top">
                <div class="brand">
                    <a class="brand-link" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                        <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                    </a>
                </div>
            <button class="drawer-close" type="button" aria-label="Close menu" data-menu-close>&times;</button>
        </div>

            <div class="drawer-section">
                <h3>{{ __('messages.categories') }}</h3>
                <div class="drawer-links">
                    @foreach($menuCategories as $category)
                    <a href="{{ route('home', ['locale' => app()->getLocale(), 'category' => $category->slug]) }}">{{ $category->name() }}</a>
                    @endforeach
                </div>
            </div>

        <div class="drawer-section">
            <h3>{{ __('messages.language') }}</h3>
            <div class="drawer-links">
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'en'])) }}">English</a>
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'fa'])) }}">فارسی</a>
            </div>
        </div>
    </aside>

    <script>
        (() => {
            const body = document.body;
            const toggle = document.querySelector('[data-menu-toggle]');
            const closers = document.querySelectorAll('[data-menu-close]');
            const mobileQuery = window.matchMedia('(max-width: 980px)');

            if (!toggle) return;

            const openMenu = () => {
                if (!mobileQuery.matches) return;
                body.classList.add('menu-open');
                toggle.setAttribute('aria-expanded', 'true');
            };

            const closeMenu = () => {
                body.classList.remove('menu-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            const syncMenuState = () => {
                if (!mobileQuery.matches) {
                    closeMenu();
                }
            };

            toggle.addEventListener('click', () => {
                if (!mobileQuery.matches) {
                    closeMenu();
                    return;
                }
                body.classList.contains('menu-open') ? closeMenu() : openMenu();
            });

            closers.forEach((closer) => closer.addEventListener('click', closeMenu));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });

            document.querySelectorAll('.mobile-drawer a').forEach((link) => {
                link.addEventListener('click', closeMenu);
            });

            mobileQuery.addEventListener('change', syncMenuState);
            window.addEventListener('pageshow', syncMenuState);
            syncMenuState();
        })();
    </script>
</body>
</html>
