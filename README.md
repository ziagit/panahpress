PanahPress — Laravel bilingual news/blog

Quick start

1. Copy environment and set DB credentials (change to your MySQL settings):

```bash
cp .env.example .env
# edit .env: set DB_CONNECTION, DB_HOST, DB_DATABASE=panahpress, DB_USERNAME, DB_PASSWORD
php artisan key:generate
```

2. Install dependencies and build assets:

```bash
composer install
npm install
npm run build
```

3. Prepare database and storage:

```bash
# create MySQL database named 'panahpress' (or update .env accordingly)
php artisan migrate --seed
php artisan storage:link
```

4. Run locally:

```bash
php artisan serve
# open http://127.0.0.1:8000/en or /fa
```

Admin account (seeded):

- email: admin@panahpress.com
- password: password

Notes

- Upload images when creating/editing posts; they will be stored in `storage/app/public/uploads` and served from `/storage/uploads`.
- The site supports English (`/en`) and Persian (`/fa`) with RTL when Persian is selected.
- To customize styles, edit `resources/views/layouts/app.blade.php` or add your own CSS in `resources/css` and rebuild assets.

---

<!-- Original Laravel README retained below for reference -->
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
