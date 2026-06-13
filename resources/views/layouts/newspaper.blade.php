<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('messages.site_name') }}</title>
    <meta name="description" content="{{ $metaDescription ?? __('messages.tagline') }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:site_name" content="{{ __('messages.site_name') }}">
    @stack('head')
    <style>
        :root {
            --paper-bg: #ffffff;
            --ink: #1b1f24;
            --muted: #6d7580;
            --line: #e1e4e8;
            --accent: #0c88bd;
            --accent-dark: #0a6f9a;
            --logo-blue: #0a8cbf;
            --navy: #0a3a68;
            --navy-2: #082c50;
            --blue-section: #0b8cbf;
            --blue-section-dark: #096f99;
            --footer: #13233a;
            --serif: Georgia, "Times New Roman", Times, serif;
            --sans: Arial, Helvetica, sans-serif;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        html { overflow-x: clip; }
        body {
            margin: 0;
            min-height: 100vh;
            background: var(--paper-bg);
            color: var(--ink);
            font-family: var(--sans);
            overflow-x: clip;
        }
        img { display: block; max-width: 100%; }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }

        .news-page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #fff;
        }

        .news-shell {
            width: min(100%, 1360px);
            margin: 0 auto;
        }

        .news-main,
        .utility-bar__inner,
        .logo-row,
        .main-nav__inner,
        .footer__inner {
            padding-inline: 22px;
        }

        .page-shell {
            width: min(100%, 1360px);
            margin: 0 auto;
            padding-inline: 22px;
        }

        .page-card {
            background: #fff;
            border: 1px solid var(--line);
            padding: 24px;
            box-shadow: none;
            border-radius: 0;
        }

        .newsletter-flash {
            width: min(100%, 1360px);
            margin: 18px auto 0;
            padding-inline: 22px;
        }

        .newsletter-flash__message {
            padding: 14px 16px;
            border: 1px solid rgba(12, 136, 189, 0.18);
            background: #eff8fc;
            color: #0f4f72;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .newsletter-flash__message.is-error {
            border-color: rgba(220, 38, 38, 0.22);
            background: #fff4f4;
            color: #9f1239;
        }

        .newsletter-inline-status {
            min-height: 1.2em;
            font-size: 0.92rem;
            line-height: 1.5;
            color: #0f4f72;
        }

        .newsletter-inline-status.is-error {
            color: #9f1239;
        }

        .utility-bar {
            border-bottom: 1px solid var(--line);
            font-size: 12px;
            color: var(--muted);
            background: #fff;
        }

        .utility-bar__inner {
            width: min(100%, 1360px);
            margin: 0 auto;
            min-height: 36px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(0, 1.8fr) minmax(220px, 1fr);
            align-items: center;
            gap: 16px;
            padding-block: 6px;
        }

        .utility-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            white-space: nowrap;
        }

        .utility-meta a {
            color: var(--muted);
        }

        .utility-meta a:hover,
        .utility-actions a:hover {
            color: var(--accent-dark);
        }

        .ticker {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            overflow: hidden;
            white-space: nowrap;
        }

        .ticker__label {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: var(--accent-dark);
            flex: 0 0 auto;
        }

        .ticker__track {
            position: relative;
            flex: 1;
            overflow: hidden;
            min-width: 0;
            mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
        }

        .ticker__content {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            min-width: max-content;
            white-space: nowrap;
            animation: ticker-scroll 26s linear infinite;
        }

        .ticker__item {
            color: var(--ink);
            font-size: 12px;
        }

        .utility-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .utility-actions a {
            color: var(--muted);
        }

        .subscribe-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 12px;
            border: 1px solid var(--accent);
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            background: #fff;
            border-radius: 2px;
        }

        .subscribe-btn:hover {
            background: var(--accent);
            color: #fff;
        }

        .logo-row {
            width: min(100%, 1360px);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-block: 8px 8px;
            background: #fff;
        }

        .logo-row__side {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--ink);
        }

        .logo-row__side--right {
            justify-content: flex-end;
            gap: 16px;
        }

        .language-switcher {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            color: var(--muted);
            font-size: 14px;
        }

        .language-switcher a {
            color: inherit;
        }

        .donate-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 14px;
            border: 1px solid var(--accent);
            color: var(--accent);
            font-size: 13px;
            font-weight: 700;
            background: #fff;
            border-radius: 3px;
            white-space: nowrap;
        }

        .donate-btn:hover {
            background: var(--accent);
            color: #fff;
        }

        .icon-btn,
        .menu-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 0;
            background: transparent;
            padding: 0;
            color: var(--ink);
            cursor: pointer;
        }

        .icon-btn svg,
        .menu-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .site-logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 0;
            background: transparent;
            color: var(--ink);
            border-radius: 0;
            padding: 0;
            cursor: pointer;
        }

        .menu-toggle span {
            display: block;
            width: 18px;
            height: 2px;
            background: currentColor;
            border-radius: 999px;
            margin: 2px 0;
        }

        .main-nav {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            background: var(--logo-blue);
        }

        .main-nav__inner {
            width: min(100%, 1360px);
            margin: 0 auto;
            padding-block: 0;
        }

        .main-nav__list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .main-nav__item {
            position: relative;
        }

        .main-nav__trigger {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            min-height: 48px;
            padding: 0 14px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            gap: 8px;
        }

        .main-nav__chevron {
            width: 12px;
            height: 12px;
            flex: 0 0 auto;
            fill: currentColor;
            opacity: 0.95;
            transition: transform 0.18s ease;
        }

        .main-nav__trigger:hover,
        .main-nav__item:hover .main-nav__trigger,
        .main-nav__trigger.is-active {
            color: #fff;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .main-nav__item:hover .main-nav__chevron,
        .main-nav__item:focus-within .main-nav__chevron {
            transform: rotate(180deg);
        }

        .main-nav__dropdown {
            position: absolute;
            top: calc(100% - 2px);
            inset-inline-start: 0;
            min-width: 260px;
            padding-top: 8px;
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease;
            z-index: 30;
        }

        .main-nav__item:hover .main-nav__dropdown,
        .main-nav__item:focus-within .main-nav__dropdown {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .main-nav__dropdown-panel {
            background: var(--logo-blue);
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            padding: 8px 0;
            box-shadow: 0 18px 30px rgba(9, 47, 78, 0.16);
        }

        .main-nav__dropdown-panel a {
            display: block;
            padding: 10px 14px;
            color: #fff;
            font-size: 14px;
            line-height: 1.2;
        }

        .main-nav__dropdown-panel a:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .news-main {
            width: min(100%, 1360px);
            margin: 0 auto;
            padding-top: 18px;
            flex: 1 0 auto;
        }

        .news-home {
            display: grid;
            gap: 28px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.35fr) minmax(320px, 1fr);
            gap: 18px;
            align-items: stretch;
            padding-top: 4px;
        }

        .hero-live-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding-top: 2px;
        }

        .hero-live-card__body {
            display: grid;
            gap: 10px;
            padding: 14px;
            background: #e8f5fb;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(11, 140, 191, 0.08);
            flex: 1;
        }

        .hero-live-card__label {
            font-size: 11px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #5b7385;
        }

        .hero-live-card__media {
            position: relative;
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .hero-live-card__media,
        .home-video-card__button {
            appearance: none;
            border: 0;
            padding: 0;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }

        .hero-live-card__media:focus-visible,
        .home-video-card__button:focus-visible {
            outline: 2px solid rgba(255, 255, 255, 0.85);
            outline-offset: 3px;
        }

        .hero-live-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-live-card__media iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        .hero-live-card__sound {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border: 0;
            border-radius: 999px;
            background: rgba(11, 27, 44, 0.82);
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            line-height: 1;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.16);
        }

        .hero-live-card__playlist {
            display: none;
        }

        .hero-live-card__playlist-item {
            appearance: none;
            border: 1px solid rgba(11, 27, 44, 0.08);
            background: #fff;
            color: #0b1b2c;
            padding: 0.65rem 0.8rem;
            border-radius: 999px;
            text-align: left;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .hero-live-card__playlist-item.is-active {
            background: #0b92d1;
            color: #fff;
            border-color: #0b92d1;
        }

        .hero-live-card__sound:hover {
            background: rgba(11, 27, 44, 0.92);
        }

        .hero-live-card__sound-icon {
            font-size: 14px;
        }

        .story-spotlight {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(250px, 0.9fr);
            gap: 16px;
            align-items: start;
        }

        .story-spotlight__image {
            display: block;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #f4f6f8;
        }

        .story-spotlight__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .story-spotlight__content {
            padding-top: 1px;
        }

        .kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #8390a2;
            font-weight: 700;
        }

        .story-title,
        .home-story-card__title,
        .video-card__title,
        .opinion-item__title,
        .read-more__link {
            font-family: var(--serif);
            font-weight: 700;
            color: #11161c;
            letter-spacing: -0.02em;
            line-height: 1.06;
        }

        .story-title a,
        .home-rail-story__title a,
        .home-story-card__title a,
        .business-lead__title a,
        .editorial-column__lead-title a,
        .video-card__title a,
        .opinion-item__title a,
        .read-more__link {
            color: inherit;
            text-decoration: none;
        }

        .story-spotlight__content .story-title {
            font-size: clamp(1rem, 1.75vw, 1.7rem);
            line-height: 1.02;
            margin: 6px 0 10px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            overflow: hidden;
        }

        .story-summary,
        .home-story-card__excerpt,
        .opinion-item__summary {
            font-size: 13px;
            line-height: 1.45;
            color: #495563;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            background: var(--navy);
            color: #fff;
            font-weight: 700;
            border: 0;
            border-radius: 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.7rem;
            background: #eaf4fb;
            color: var(--navy);
            border-radius: 0;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            width: fit-content;
        }

        .text-muted {
            color: #64748b;
        }

        .story-meta,
        .home-story-card__meta,
        .video-card__meta,
        .opinion-item__meta,
        .compact-link__meta {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .story-spotlight__meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .home-rail-story {
            display: block;
            padding: 12px 0;
        }

        .home-rail-story__inner {
            display: grid;
            grid-template-columns: 106px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
            color: inherit;
        }

        .home-rail-story__thumb {
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #eef2f5;
            flex-shrink: 0;
        }

        .home-rail-story__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .home-rail-story__content {
            display: grid;
            gap: 6px;
            align-content: start;
        }

        .home-rail-story__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 17px;
            line-height: 1.06;
            font-weight: 700;
            color: #11161c;
        }

        .featured-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            border-top: 1px solid var(--line);
            padding-top: 22px;
        }

        .home-ad-banner {
            display: block;
            margin-top: 6px;
            overflow: hidden;
            background: #fff7b8;
        }

        .home-ad-banner__image {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 1;
            object-fit: cover;
        }

        .home-story-card {
            display: grid;
            gap: 10px;
            align-content: start;
            height: 100%;
        }

        .home-story-card__link {
            display: grid;
            gap: 10px;
            color: inherit;
            height: 100%;
        }

        .home-story-card__media {
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #f2f4f7;
        }

        .home-story-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .home-story-card__body {
            display: grid;
            gap: 6px;
        }

        .home-story-card__title {
            margin: 0;
            font-size: 18px;
            line-height: 1.08;
        }

        .home-story-card--card .home-story-card__title,
        .home-story-card--business-card .home-story-card__title {
            font-size: 16px;
            line-height: 1.1;
        }

        .home-story-card--card .home-story-card__excerpt,
        .home-story-card--business-card .home-story-card__excerpt {
            font-size: 12px;
            line-height: 1.45;
        }

        .home-story-card--business-card .home-story-card__media {
            aspect-ratio: 16 / 10;
        }

        .home-story-card__excerpt {
            margin: 0;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 0 0 12px;
        }

        .section-heading h2 {
            margin: 0;
            font-family: var(--serif);
            font-size: 28px;
            line-height: 1.05;
            color: #11161c;
            position: relative;
            padding-bottom: 9px;
        }

        .section-heading h2::after {
            content: "";
            position: absolute;
            inset-inline-start: 0;
            bottom: 0;
            width: 76px;
            height: 3px;
            background: var(--navy);
        }

        .section-heading.is-dark h2 {
            color: #fff;
        }

        .section-heading.is-dark h2::after {
            background: #fff;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 18px;
            margin: 18px 0 26px;
        }

        .hero-card {
            background: #fff;
            border-radius: 0;
            padding: 24px;
            box-shadow: none;
            border: 0;
        }

        .hero-card h1 {
            margin: 0.75rem 0 0.85rem;
            font-size: clamp(2rem, 2.8vw, 3rem);
            line-height: 1.05;
        }

        .hero-card p {
            margin: 0.85rem 0 0;
            color: #475569;
        }

        .hero-card .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        .business-section,
        .editorial-columns-section,
        .opinion-section {
            border-top: 1px solid var(--line);
            padding-top: 22px;
        }

        .business-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) 240px;
            gap: 18px;
            align-items: start;
        }

        .business-lead__card {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(300px, 0.9fr);
            gap: 16px;
            align-items: start;
        }

        .business-lead__media {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .business-lead__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .business-lead__title {
            margin: 6px 0 10px;
            font-family: var(--serif);
            font-size: clamp(1.1rem, 2vw, 1.7rem);
            line-height: 1.02;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .business-weather {
            background: linear-gradient(180deg, #48a9d5 0%, #2e95c3 100%);
            color: #fff;
            padding: 18px 16px;
            min-height: 180px;
            position: relative;
            overflow: hidden;
        }

        .business-weather::before {
            content: "";
            position: absolute;
            inset: -30% -35% auto auto;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .business-weather__city {
            font-family: var(--serif);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .business-weather__temp {
            font-size: 40px;
            line-height: 1;
            font-weight: 700;
            margin: 12px 0 8px;
        }

        .business-weather__days {
            display: grid;
            gap: 8px;
            margin-top: 20px;
            font-size: 12px;
        }

        .business-weather__day {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .business-weather__day-range {
            white-space: nowrap;
            text-align: right;
        }

        .business-grid {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .editorial-columns {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            align-items: start;
        }

        .editorial-column {
            display: grid;
            gap: 10px;
            align-content: start;
        }

        .editorial-column__lead {
            display: grid;
            gap: 8px;
            color: inherit;
        }

        .editorial-column__lead-image {
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .editorial-column__lead-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .editorial-column__lead-body {
            display: grid;
            gap: 4px;
        }

        .compact-links {
            display: grid;
        }

        .compact-link {
            padding: 8px 0;
            border-top: 1px solid var(--line);
            display: grid;
            gap: 4px;
            align-items: start;
        }

        .compact-link:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .compact-link__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 16px;
            line-height: 1.18;
            font-weight: 400;
            color: #11161c;
        }

        .compact-link__meta {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .video-section {
            position: relative;
            background: linear-gradient(180deg, var(--blue-section) 0%, #0a84b6 100%);
            color: #fff;
            overflow: hidden;
            padding: 36px 0 40px;
            width: 100vw;
            margin-inline: calc(50% - 50vw);
            margin-top: 34px;
            margin-bottom: 24px;
        }

        .video-section .news-shell {
            padding-inline: 22px;
        }

        .video-section::before,
        .video-section::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }

        .video-section::before {
            width: 540px;
            height: 540px;
            inset-inline-start: -220px;
            bottom: -250px;
        }

        .video-section::after {
            width: 620px;
            height: 620px;
            inset-inline-end: -300px;
            top: -190px;
        }

        .video-section .news-shell {
            position: relative;
            z-index: 1;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .home-video-card {
            display: grid;
            gap: 8px;
            color: #fff;
        }

        .home-video-card__button {
            appearance: none;
            border: 0;
            background: transparent;
            padding: 0;
            color: inherit;
            cursor: pointer;
            display: grid;
            gap: 8px;
            text-align: start;
            width: 100%;
        }

        .home-video-card__button:focus-visible {
            outline: 2px solid rgba(255, 255, 255, 0.85);
            outline-offset: 3px;
        }

        .home-video-card__button .home-video-card__media {
            display: grid;
            gap: 8px;
        }

        .home-video-card__media {
            position: relative;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
        }

        .home-video-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(0.98);
        }

        .home-video-card__media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05), rgba(0,0,0,0.22));
        }

        .home-video-card__play {
            position: absolute;
            inset: 50% auto auto 50%;
            transform: translate(-50%, -50%);
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(16, 28, 43, 0.78);
            border: 2px solid rgba(255, 255, 255, 0.25);
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .home-video-card__play::before {
            content: "";
            margin-left: 3px;
            border-style: solid;
            border-width: 8px 0 8px 12px;
            border-color: transparent transparent transparent #fff;
        }

        .home-video-card__title {
            margin: 0;
            font-size: 16px;
            line-height: 1.08;
            color: #fff;
        }

        .home-video-card__meta {
            color: rgba(255, 255, 255, 0.8);
        }

        .video-dialog {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .video-dialog.is-open {
            display: flex;
        }

        .video-dialog__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(8, 18, 30, 0.78);
        }

        .video-dialog__panel {
            position: relative;
            z-index: 1;
            width: min(100%, 980px);
            background: #0e1722;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
        }

        .video-dialog__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 14px;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .video-dialog__title {
            font-family: var(--serif);
            font-size: 18px;
            line-height: 1.2;
            margin: 0;
        }

        .video-dialog__close {
            appearance: none;
            border: 0;
            background: transparent;
            color: #fff;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            padding: 0 2px;
        }

        .video-dialog__frame {
            aspect-ratio: 16 / 9;
            width: 100%;
            background: #000;
        }

        .video-dialog__frame iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        .opinion-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(280px, 0.78fr);
            gap: 24px;
            align-items: start;
        }

        .opinion-section {
            margin-inline: 0;
        }

        .post-page {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 320px);
            gap: 2rem;
            margin-top: 1.8rem;
        }

        .post-main {
            display: grid;
            gap: 1.5rem;
        }

        .post-page {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(0, 1fr) minmax(300px, 320px);
            align-items: start;
        }

        .post-sidebar {
            display: grid;
            gap: 1.5rem;
        }

        .post-article {
            display: grid;
            gap: 12px;
        }

        .post-article > div {
            display: grid;
            gap: 0.95rem;
        }

        .post-category {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 0.35rem 0.55rem;
            background: #0f4f97;
            color: #fff;
            font-size: 11px;
            line-height: 1;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .post-title {
            margin: 0;
            font-family: var(--serif);
            font-size: clamp(1.75rem, 2.4vw, 2.1rem);
            line-height: 1.06;
            font-weight: 700;
            color: #11161c;
            letter-spacing: -0.03em;
            max-width: none;
        }

        .post-byline {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            color: #8a95a4;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            margin: 0;
        }

        .post-share-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: nowrap;
            margin-top: 0.9rem;
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
        }

        .post-share-row > div,
        .post-share,
        .post-tools {
            min-width: 0;
            flex-shrink: 0;
        }

        .post-share {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .post-share a,
        .post-tools a,
        .post-tools button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 2px;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            border: 0;
            background: transparent;
            cursor: pointer;
            flex-shrink: 0;
        }

        .post-share a.facebook { background: #1b5fbf; }
        .post-share a.x { background: #6f6f6f; }
        .post-share a.linkedin { background: #2b86c5; }
        .post-share a.whatsapp { background: #42c41a; }
        .post-tools {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .post-tools a,
        .post-tools button {
            background: #fff;
            color: #6b7280;
            border: 1px solid #d6dde5;
        }

        .post-tools button {
            padding: 0 10px;
            min-width: 70px;
            height: 34px;
            width: auto;
            font-size: 0.85rem;
        }

        .post-featured {
            display: grid;
            gap: 4px;
            margin: 0;
            width: 100%;
        }

        .category-page {
            display: grid;
            gap: 24px;
            padding-top: 22px;
        }

        .category-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 320px);
            gap: 32px;
            align-items: start;
        }

        .category-feed {
            display: grid;
            gap: 26px;
        }

        .category-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 22px;
            align-items: start;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--line);
        }

        .category-item__content {
            display: grid;
            gap: 8px;
            align-content: start;
        }

        .category-item__media {
            display: block;
            aspect-ratio: 16 / 11;
            overflow: hidden;
            background: #f2f4f7;
        }

        .category-item__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-item__kicker {
            font-size: 10px;
            line-height: 1.1;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #8a95a4;
            font-weight: 700;
        }

        .category-item__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 28px;
            line-height: 1.08;
            font-weight: 700;
            color: #11161c;
            letter-spacing: -0.03em;
        }

        .category-item__excerpt {
            margin: 0;
            color: #475569;
            font-size: 15px;
            line-height: 1.5;
            max-width: 52ch;
        }

        .category-item__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 11px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .category-sidebar {
            display: grid;
            gap: 22px;
        }

        .sidebar-box {
            display: grid;
            gap: 12px;
        }

        .sidebar-box__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 16px;
            line-height: 1.05;
            font-weight: 700;
            color: #11161c;
            text-transform: uppercase;
        }

        .sidebar-search {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 46px;
            gap: 0;
        }

        .sidebar-search input,
        .sidebar-search button {
            min-height: 36px;
            border: 1px solid var(--line);
            border-radius: 0;
            background: #fff;
        }

        .sidebar-search input {
            padding: 0 12px;
            color: #11161c;
        }

        .sidebar-search button {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-search button svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .sidebar-panel {
            display: grid;
            gap: 8px;
            padding: 12px;
            background: #f4f4f4;
        }

        .sidebar-panel .newsletter-form {
            width: 100%;
            grid-template-columns: 1fr;
        }

        .sidebar-panel .newsletter-form input,
        .sidebar-panel .newsletter-form select,
        .sidebar-panel .newsletter-form button {
            width: 100%;
        }

        .latest-list {
            display: grid;
            gap: 10px;
        }

        .latest-item {
            display: grid;
            grid-template-columns: 96px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--line);
        }

        .latest-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .latest-item__media {
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .latest-item__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .latest-item__meta {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .latest-item__title {
            margin: 4px 0 0;
            font-family: var(--serif);
            font-size: 17px;
            line-height: 1.1;
            font-weight: 700;
            color: #11161c;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .category-ad {
            min-height: 168px;
            background: #fff7b8;
        }

        .category-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .category-pagination a,
        .category-pagination span {
            min-width: 34px;
            height: 32px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d8dee5;
            color: #334155;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
        }

        .category-pagination .is-active {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }

        .category-pagination .is-disabled {
            opacity: 0.45;
        }

        .post-image {
            border-radius: 0;
            width: 100%;
            aspect-ratio: 16 / 9;
            height: auto;
            object-fit: cover;
            margin-bottom: 0;
        }

        .post-meta {
            padding: 2rem;
            margin-bottom: 0;
        }

        .post-caption {
            color: #8a95a4;
            font-size: 12px;
            line-height: 1.35;
        }

        .post-content {
            display: grid;
            gap: 0.95rem;
            color: #11161c;
            font-size: 14px;
            line-height: 1.82;
        }

        .post-content p {
            margin: 0;
        }

        .post-content figure {
            margin: 0;
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

        .post-video {
            margin: 1.25rem 0 0;
        }

        .post-video__link {
            position: relative;
            display: block;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background: #0f172a;
        }

        .post-video__link--text {
            padding: 16px 18px;
            background: #f8fafc;
            border-color: #dbe2ea;
            color: #0f172a;
            text-decoration: none;
            display: grid;
            gap: 6px;
        }

        .post-video__link img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            margin: 0;
            display: block;
            opacity: 0.9;
        }

        .post-video__badge {
            position: absolute;
            left: 14px;
            bottom: 14px;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.88);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .post-video__link-label {
            font-family: var(--serif);
            font-size: 18px;
            line-height: 1.1;
            font-weight: 700;
            color: #11161c;
        }

        .post-video__link-url {
            font-size: 13px;
            line-height: 1.4;
            color: #475569;
            word-break: break-word;
        }

        .post-sidebar__search,
        .post-sidebar__newsletter,
        .post-sidebar__latest {
            display: grid;
            gap: 12px;
        }

        .post-sidebar .sidebar-panel {
            background: #f4f4f4;
        }

        .post-sidebar__latest-featured {
            display: grid;
            gap: 10px;
        }

        .post-sidebar__latest-featured img {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            background: #f2f4f7;
        }

        .post-sidebar__latest-featured-title {
            font-family: var(--serif);
            font-size: 22px;
            line-height: 1.12;
            font-weight: 700;
            color: #11161c;
        }

        .post-sidebar__latest-featured-excerpt {
            color: #64748b;
            font-size: 14px;
            line-height: 1.45;
        }

        .post-sidebar__latest-list {
            display: grid;
            gap: 12px;
        }

        .post-sidebar__latest-item {
            padding-top: 12px;
            border-top: 1px solid var(--line);
        }

        .post-sidebar__latest-item:first-child {
            padding-top: 0;
            border-top: 0;
        }

        .post-sidebar__latest-item-meta {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .post-sidebar__latest-item-title {
            margin: 5px 0 0;
            font-family: var(--serif);
            font-size: 15px;
            line-height: 1.2;
            font-weight: 700;
            color: #11161c;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .post-related-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .post-related-header h2 {
            margin: 0;
            font-family: var(--serif);
            font-size: 22px;
            line-height: 1.05;
            font-weight: 700;
            color: #11161c;
        }

        .post-related-header__line {
            flex: 1;
            height: 1px;
            background: var(--line);
            position: relative;
        }

        .post-related-header__line::before {
            content: "";
            position: absolute;
            left: 0;
            top: -2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--navy);
        }

        .post-related-header__controls {
            display: flex;
            gap: 4px;
        }

        .post-related-header__controls button {
            width: 28px;
            height: 28px;
            border: 1px solid #d6dde5;
            background: #fff;
            color: #11161c;
            border-radius: 2px;
            cursor: pointer;
        }

        .post-related-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .post-related-card {
            display: grid;
            gap: 8px;
        }

        .post-related-card__media {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .post-related-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-related-card__kicker {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .post-related-card__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 17px;
            line-height: 1.12;
            font-weight: 700;
            color: #11161c;
        }

        .post-related-card__meta {
            font-size: 10px;
            color: #8a95a4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .opinion-main {
            display: grid;
            gap: 18px;
        }

        .opinion-lead {
            display: grid;
            grid-template-columns: minmax(0, 1.28fr) minmax(280px, 0.92fr);
            gap: 18px;
            align-items: start;
        }

        .opinion-lead__media {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .opinion-lead__media img,
        .opinion-card__media img,
        .read-more__featured-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .opinion-lead__body {
            display: grid;
            gap: 8px;
            align-content: start;
            padding-top: 2px;
        }

        .opinion-lead__title {
            margin: 0;
            font-family: var(--serif);
            font-size: clamp(1.1rem, 2vw, 1.7rem);
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #11161c;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .opinion-lead__excerpt {
            margin: 0;
            color: #3f4d5d;
            font-size: 15px;
            line-height: 1.45;
        }

        .opinion-divider {
            border-top: 1px solid var(--line);
            margin: 0;
        }

        .opinion-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .opinion-card {
            display: grid;
            gap: 8px;
            align-content: start;
        }

        .opinion-card__media {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .opinion-card__body {
            display: grid;
            gap: 6px;
        }

        .opinion-card__title {
            margin: 0;
            font-family: var(--serif);
            font-size: 17px;
            line-height: 1.08;
            font-weight: 700;
            color: #11161c;
        }

        .read-more {
            padding-left: 0;
            display: grid;
            gap: 14px;
        }

        .read-more h3 {
            margin: 0;
            font-family: var(--serif);
            font-size: 18px;
            line-height: 1.05;
            color: #11161c;
        }

        .read-more__featured {
            display: grid;
            gap: 10px;
            color: inherit;
        }

        .read-more__featured-media {
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #f2f4f7;
        }

        .read-more__featured-title {
            font-family: var(--serif);
            font-size: 18px;
            line-height: 1.15;
            font-weight: 700;
            color: #11161c;
        }

        .read-more__list {
            display: grid;
        }

        .read-more__item {
            display: grid;
            gap: 4px;
            align-items: start;
            padding: 10px 0;
            border-top: 1px solid var(--line);
        }

        .read-more__item:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .read-more__item-title {
            font-family: var(--serif);
            font-size: 13px;
            line-height: 1.2;
            font-weight: 700;
            color: #11161c;
        }

        .footer {
            margin-top: auto;
            background: #ececec;
            color: #11161c;
            position: relative;
        }

        .footer__inner {
            width: min(100%, 1360px);
            margin: 0 auto;
            padding: 20px 14px 0;
            position: relative;
        }

        .footer__top {
            display: grid;
            gap: 10px;
            justify-items: center;
            text-align: center;
            padding-top: 6px;
        }

        .footer__brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .footer__brand img {
            width: 86px;
            height: 86px;
            object-fit: contain;
        }

        .footer__links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #1b1f24;
        }

        .footer__links a {
            color: inherit;
        }

        .footer__socials {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            padding: 4px 0 10px;
        }

        .footer__socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: #11161c;
        }

        .footer__socials svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .footer__newsletter {
            display: grid;
            gap: 14px;
            justify-items: center;
            padding: 18px 0 24px;
            text-align: center;
        }

        .footer__newsletter h4 {
            margin: 0;
            font-family: var(--serif);
            font-size: 18px;
            line-height: 1.05;
            color: #11161c;
        }

        .newsletter-form {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) 90px;
            gap: 0;
            width: min(100%, 460px);
        }

        .newsletter-form input {
            width: 100%;
            min-height: 36px;
            border: 1px solid #d8d8d8;
            background: #fff;
            color: #1b1f24;
            padding: 0 12px;
            border-radius: 0;
            outline: none;
        }

        .newsletter-form input::placeholder {
            color: #7b8794;
        }

        .newsletter-form button {
            min-height: 36px;
            border: 0;
            background: #4e97c6;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .footer-bottom {
            padding: 10px 0 10px;
            text-align: center;
            font-size: 12px;
            color: #11161c;
            position: relative;
        }

        .footer-bottom::before {
            content: "";
            position: absolute;
            left: calc(50% - 50vw);
            top: 0;
            width: 100vw;
            height: 1px;
            background: #6f6f6f;
        }

        .footer-bottom__top {
            position: fixed;
            right: 20px;
            bottom: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 2px solid #1d4777;
            border-radius: 50%;
            color: #1d4777;
            background: #ececec;
            z-index: 80;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            opacity: 0;
            pointer-events: none;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .footer-bottom__top.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .footer-bottom__top svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(10, 14, 20, 0.45);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 60;
        }

        .mobile-drawer {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            max-height: 100vh;
            background: #fff;
            transform: translateY(-102%);
            transition: transform 0.24s ease;
            z-index: 70;
            padding: 16px 16px 18px;
            display: grid;
            gap: 14px;
            overflow-y: auto;
            border: 0;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        }

        .mobile-drawer__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 0;
        }

        .mobile-drawer__links {
            display: grid;
            gap: 10px;
        }

        .mobile-drawer__links a {
            display: block;
            padding: 11px 12px;
            border: 0;
            color: var(--ink);
            font-weight: 600;
            border-radius: 4px;
            background: #fff;
        }

        .mobile-drawer__links details {
            border: 0;
            border-radius: 0;
            background: #fff;
            overflow: hidden;
        }

        .mobile-drawer__links summary {
            list-style: none;
            cursor: pointer;
            padding: 11px 12px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .mobile-drawer__links summary::-webkit-details-marker {
            display: none;
        }

        .mobile-drawer__links summary::after {
            content: "";
            width: 12px;
            height: 12px;
            flex: 0 0 auto;
            background: currentColor;
            mask: url("data:image/svg+xml,%3Csvg viewBox='0 0 12 12' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.2 4.25 6 8.05l3.8-3.8.85.85L6 9.75 1.35 5.1l.85-.85Z'/%3E%3C/svg%3E") center / contain no-repeat;
            -webkit-mask: url("data:image/svg+xml,%3Csvg viewBox='0 0 12 12' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.2 4.25 6 8.05l3.8-3.8.85.85L6 9.75 1.35 5.1l.85-.85Z'/%3E%3C/svg%3E") center / contain no-repeat;
            color: #64748b;
        }

        .mobile-drawer__submenu {
            display: grid;
            gap: 4px;
            padding: 0 0 10px;
        }

        .mobile-drawer__submenu a {
            padding: 9px 10px;
            border: 0;
            border-radius: 0;
            background: #f8fafc;
            font-weight: 600;
        }

        .mobile-drawer__links details[open] summary {
            border-bottom: 1px solid var(--line);
        }

        body.menu-open {
            overflow: hidden;
        }

        .mobile-drawer__links a.is-active {
            border-color: var(--accent);
            color: var(--accent);
        }

        body.menu-open .drawer-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        body.menu-open .mobile-drawer {
            transform: translateY(0);
        }

        @keyframes ticker-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        @media (max-width: 1180px) {
            .utility-bar__inner,
            .logo-row,
            .main-nav__inner,
            .news-main,
            .footer__inner {
                width: 100%;
            }

            .logo-row {
                grid-template-columns: auto 1fr auto;
            }

            .logo-row__side--right,
            .main-nav {
                display: none;
            }

            .menu-toggle {
                display: inline-flex;
                flex-direction: column;
            }

            .hero-grid,
            .business-layout,
            .opinion-layout {
                grid-template-columns: 1fr;
            }

            .opinion-lead,
            .opinion-grid {
                grid-template-columns: 1fr;
            }

            .category-layout,
            .category-item {
                grid-template-columns: 1fr;
            }

            .post-page,
            .post-related-grid {
                grid-template-columns: 1fr;
            }

            .video-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .featured-row,
            .business-grid,
            .editorial-columns {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-ad-banner__image {
                aspect-ratio: 16 / 6;
            }

            .footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 780px) {
            .utility-bar__inner {
                grid-template-columns: 1fr;
                justify-items: stretch;
            }

            .ticker {
                justify-content: flex-start;
            }

            .logo-row__side {
                gap: 8px;
            }

            .site-logo {
                width: 48px;
                height: 48px;
            }

            .featured-row,
            .business-grid,
            .editorial-columns,
            .video-grid,
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .home-ad-banner__image {
                aspect-ratio: 16 / 9;
            }

            .logo-row__side--right {
                display: none;
            }

            .story-spotlight,
            .business-lead__card {
                grid-template-columns: 1fr;
            }

            .hero-live-card {
                gap: 8px;
            }

            .opinion-lead {
                grid-template-columns: 1fr;
            }

            .opinion-grid {
                grid-template-columns: 1fr;
            }

            .home-rail-story__inner {
                grid-template-columns: 92px 1fr;
            }

            .category-item__title {
                font-size: 22px;
            }

            .category-item__media {
                aspect-ratio: 16 / 9;
            }

            .post-title {
                max-width: 100%;
            }
        }

        @media (min-width: 1181px) {
            .menu-btn {
                display: none;
            }
        }
    </style>
</head>
<body class="news-page">
    <div id="top"></div>
    @php
        $locale = app()->getLocale();
        $menuCategories = collect($navCategories ?? []);
        $donateUrl = config('services.stripe.donate_url');
    @endphp

        <header class="logo-row">
        <div class="logo-row__side">
            <a href="{{ route('home', ['locale' => $locale]) }}" aria-label="{{ __('messages.site_name') }}">
                <img class="site-logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
            </a>
        </div>
        <div class="logo-row__side logo-row__side--right">
            <div class="language-switcher">
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'en'])) }}">{{ __('messages.language_en') }}</a>
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'fa'])) }}">{{ __('messages.language_fa') }}</a>
            </div>
            <a class="donate-btn" href="{{ $donateUrl ?: route('donate', ['locale' => $locale]) }}" @if($donateUrl) target="_blank" rel="noopener noreferrer" @endif>{{ __('messages.donate') }}</a>
        </div>
        <button class="menu-toggle" type="button" aria-label="{{ __('messages.menu') }}" aria-controls="mobile-drawer" aria-expanded="false" data-menu-toggle>
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <div class="drawer-backdrop" data-drawer-backdrop></div>
    <aside class="mobile-drawer" id="mobile-drawer" aria-label="{{ __('messages.menu') }}">
        <div class="mobile-drawer__top">
            <a href="{{ route('home', ['locale' => $locale]) }}" aria-label="{{ __('messages.site_name') }}">
                <img class="site-logo" src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
            </a>
            <div class="language-switcher">
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'en'])) }}">{{ __('messages.language_en') }}</a>
                <a href="{{ route(request()->route()?->getName() ?: 'home', array_merge(request()->route()?->parameters() ?? [], ['locale' => 'fa'])) }}">{{ __('messages.language_fa') }}</a>
            </div>
            <a class="donate-btn" href="{{ $donateUrl ?: route('donate', ['locale' => $locale]) }}" @if($donateUrl) target="_blank" rel="noopener noreferrer" @endif>{{ __('messages.donate') }}</a>
        </div>

        <nav class="mobile-drawer__links" aria-label="{{ __('messages.menu') }}">
            <a href="{{ route('home', ['locale' => $locale]) }}" class="@if(request()->routeIs('home')) is-active @endif">{{ __('messages.home') }}</a>
            @foreach($menuCategories as $category)
                <details @if(request()->route('category')?->is($category)) open @endif>
                    <summary>{{ $category->name($locale) }}</summary>
                    <div class="mobile-drawer__submenu">
                        <a href="{{ route('categories.show', ['locale' => $locale, 'category' => $category]) }}" @if(request()->route('category')?->is($category)) class="is-active" @endif>
                            {{ __('messages.view_all_category', ['name' => $category->name($locale)]) }}
                        </a>
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', ['locale' => $locale, 'category' => $child]) }}" @if(request()->route('category')?->is($child)) class="is-active" @endif>
                                {{ $child->name($locale) }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </nav>
    </aside>

    <nav class="main-nav" aria-label="Main navigation">
        <div class="main-nav__inner">
            <ul class="main-nav__list">
                <li class="main-nav__item">
                    <a class="main-nav__trigger @if(request()->routeIs('home')) is-active @endif" href="{{ route('home', ['locale' => $locale]) }}">
                        {{ __('messages.home') }}
                    </a>
                </li>

                @foreach($menuCategories as $category)
                    <li class="main-nav__item">
                        <a class="main-nav__trigger @if(request()->route('category')?->is($category)) is-active @endif" href="{{ route('categories.show', ['locale' => $locale, 'category' => $category]) }}">
                            {{ $category->name($locale) }}
                            @if($category->children->isNotEmpty())
                                <svg class="main-nav__chevron" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
                                    <path d="M2.2 4.25 6 8.05l3.8-3.8.85.85L6 9.75 1.35 5.1l.85-.85Z"/>
                                </svg>
                            @endif
                        </a>
                        @if($category->children->isNotEmpty())
                            <div class="main-nav__dropdown">
                                <div class="main-nav__dropdown-panel">
                                    @foreach($category->children as $child)
                                        <a href="{{ route('categories.show', ['locale' => $locale, 'category' => $child]) }}">
                                            {{ $child->name($locale) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <main class="news-main">
        @if (session('newsletter_success'))
            <div class="newsletter-flash">
                <div class="newsletter-flash__message">
                    {{ session('newsletter_success') }}
                </div>
            </div>
        @elseif (session('newsletter_error'))
            <div class="newsletter-flash">
                <div class="newsletter-flash__message is-error">
                    {{ session('newsletter_error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer" id="footer">
        <div class="footer__inner">
            <div class="footer__top">
                <div class="footer__brand">
                    <a href="{{ route('home', ['locale' => $locale]) }}" aria-label="{{ __('messages.site_name') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.site_name') }}">
                    </a>
                    <div class="footer__links">
                        <a href="{{ route('contact', ['locale' => $locale]) }}">{{ __('messages.footer_contact') }}</a>
                        <a href="{{ route('about', ['locale' => $locale]) }}">{{ __('messages.footer_about') }}</a>
                        <a href="{{ route('verify', ['locale' => $locale]) }}">{{ __('messages.footer_verify') }}</a>
                        <a href="{{ route('login', ['locale' => $locale]) }}">{{ __('messages.login') }}</a>
                    </div>
                </div>

                <div class="footer__socials" aria-label="Social media">
                    <a href="https://www.facebook.com/Panahpress" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8.2h2.76l.42-3.2h-3.18V8.57c0-.93.26-1.56 1.6-1.56h1.71V4.14c-.3-.04-1.35-.14-2.56-.14-2.53 0-4.26 1.54-4.26 4.36v2.44H7.2v3.2h2.79V22h3.5z"/></svg>
                    </a>
                    <a href="https://x.com/panah_press" target="_blank" rel="noopener noreferrer" aria-label="X">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.7 7.68L23.2 22h-6.8l-5.33-6.72L5.2 22H2l7.3-8.37L.96 2H7.9l4.87 6.1L18.9 2zm-1.2 18h1.8L6.9 3.94H5L17.7 20z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@panah_press" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a3 3 0 0 0-2.12-2.12C17.6 4.6 12 4.6 12 4.6s-5.6 0-7.48.48A3 3 0 0 0 2.4 7.2 31.7 31.7 0 0 0 2 12a31.7 31.7 0 0 0 .4 4.8 3 3 0 0 0 2.12 2.12C6.4 19.4 12 19.4 12 19.4s5.6 0 7.48-.48A3 3 0 0 0 21.6 16.8 31.7 31.7 0 0 0 22 12a31.7 31.7 0 0 0-.4-4.8zM10 15.2V8.8l5.5 3.2L10 15.2z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/panah_press" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 2A2.5 2.5 0 1 0 14.5 12 2.5 2.5 0 0 0 12 9.5zM17.75 6a1.25 1.25 0 1 1-1.25 1.25A1.25 1.25 0 0 1 17.75 6z"/></svg>
                    </a>
                </div>
            </div>

            <div class="footer__newsletter">
                <h4>{{ __('messages.subscribe_newsletter') }}</h4>
                <div class="newsletter-inline-status" data-newsletter-status aria-live="polite"></div>
                <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe', ['locale' => $locale]) }}">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}" aria-label="{{ __('messages.email') }}" required>
                    <button type="submit">{{ __('messages.subscribe') }}</button>
                </form>
            </div>

            <div class="footer-bottom">
                <a class="footer-bottom__top" href="#top" aria-label="Back to top">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5 4 13l1.41 1.41L11 8.83V20h2V8.83l5.59 5.58L20 13z"/></svg>
                </a>
                <div>{{ __('messages.copyright', ['year' => 2026]) }}</div>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const topButton = document.querySelector('.footer-bottom__top');
            if (!topButton) return;

            const toggleTopButton = () => {
                const doc = document.documentElement;
                const scrollTop = window.scrollY || doc.scrollTop || 0;
                const viewportHeight = window.innerHeight || doc.clientHeight || 0;
                const pageHeight = doc.scrollHeight || 0;
                const nearBottom = scrollTop + viewportHeight >= pageHeight - 220;

                topButton.classList.toggle('is-visible', nearBottom);
            };

            window.addEventListener('scroll', toggleTopButton, { passive: true });
            window.addEventListener('resize', toggleTopButton);
            window.addEventListener('load', toggleTopButton);
            window.addEventListener('pageshow', toggleTopButton);
            toggleTopButton();
        })();

        (() => {
            const toggle = document.querySelector('[data-menu-toggle]');
            const drawer = document.getElementById('mobile-drawer');
            const backdrop = document.querySelector('[data-drawer-backdrop]');
            if (!toggle || !drawer || !backdrop) return;

            const setOpen = (open) => {
                document.body.classList.toggle('menu-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            toggle.addEventListener('click', () => {
                setOpen(!document.body.classList.contains('menu-open'));
            });

            backdrop.addEventListener('click', () => setOpen(false));

            drawer.addEventListener('click', (event) => {
                const link = event.target.closest('a');
                if (link) setOpen(false);
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setOpen(false);
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1180) setOpen(false);
            });
        })();

        (() => {
            const forms = document.querySelectorAll('form.newsletter-form');
            if (!forms.length) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const getStatusBox = (form) => {
                const parent = form.closest('.sidebar-box, .footer__newsletter, .sidebar-panel');
                if (!parent) return null;

                let box = parent.querySelector('[data-newsletter-status]');
                if (!box) {
                    box = document.createElement('div');
                    box.className = 'newsletter-inline-status';
                    box.setAttribute('data-newsletter-status', '');
                    box.setAttribute('aria-live', 'polite');
                    parent.appendChild(box);
                }

                return box;
            };

            forms.forEach((form) => {
                const status = getStatusBox(form);
                const submitButton = form.querySelector('button[type="submit"]');

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (status) {
                        status.textContent = '';
                        status.classList.remove('is-error');
                    }

                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                            },
                            body: new FormData(form),
                        });

                        const data = await response.json().catch(() => ({}));

                        if (status) {
                            status.textContent = data.message || '';
                            status.classList.toggle('is-error', !response.ok);
                        }

                        if (response.ok) {
                            form.querySelectorAll('input[type="email"]').forEach((input) => {
                                input.value = '';
                            });
                        }
                    } catch (error) {
                        if (status) {
                            status.textContent = `{{ __('messages.newsletter_request_failed') }}`;
                            status.classList.add('is-error');
                        }
                    } finally {
                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                    }
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
