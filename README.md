# Turath Cars

A used-car marketplace for Egypt, run by an independent broker. Owners send their cars with how they run, the paperwork and details; the broker publishes them. Built with Laravel 12, MySQL and Tailwind. English by default, Arabic (RTL) one click away.

## What is inside

- Pages: Home, About, Shop, Contact, car details and an admin dashboard.
- 240 sample Egyptian cars across all 27 governorates (seeded, deterministic).
- Search by car type/brand, price, governorate, traffic unit (وحدة المرور), colour, licence age, licence type, origin (local/customs), year, mileage.
- Transparent-background car illustrations and arrow-only sliders and pagination.
- Admin analytics: KPIs, monthly listings, status, price, brands, governorates, colours, licence status, messages.

## Setup

```bash
composer install
cp .env.example .env && php artisan key:generate
# set DB_* in .env (MySQL), then:
ADMIN_EMAIL=you@example.com ADMIN_PASSWORD='a-long-password' composer setup
php artisan storage:link
php artisan serve
```

Or import the ready database directly:

```bash
mysql -u root -p < database/sql/turath_cars.sql
```

(regenerate it with `php database/sql/build.php`).

## Front-end

`public/css/app.css` is pre-built. To change styles: `npm install && npm run build`.

## Security

CSRF on every form, strict Content-Security-Policy (no inline scripts or styles), throttled login and contact form, honeypot, admin-only access, validated whitelists for every filter, validated and randomly renamed uploads, encrypted HttpOnly SameSite sessions.

## Tests

```bash
php artisan test
```
