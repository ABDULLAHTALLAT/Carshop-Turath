# Turath Cars

A simple Laravel marketplace for used cars in Egypt, run by an independent broker.
Sellers' cars are listed with photos, paperwork, how the car runs and full details.

## Pages
- **Home**, **Shop** (filters, sorting, pagination), **Car detail** (photo slider), **About**, **Contact**
- **Admin dashboard** (`/admin`): stats, cars CRUD with multi-photo upload, status changes, inbox

## Setup
```bash
composer install
composer setup            # copies .env, generates key, creates SQLite DB, migrates, links storage
# edit .env: ADMIN_EMAIL, ADMIN_PASSWORD (12+ chars), BROKER_* contact details
php artisan db:seed          # creates the admin account
php artisan serve
```
Then sign in at `/login`.

## Security
- CSRF protection, validated inputs, mass-assignment guards (`is_admin` is never fillable)
- Admin-only routes behind `auth` + `admin` middleware; login rate limited and admin accounts only
- Session fixation protection, encrypted sessions, HttpOnly + SameSite cookies
- Strict Content-Security-Policy (no inline scripts or styles), HSTS, frame/sniffing protection
- Image uploads: type/size/dimension checks and random server-side file names
- Contact form: rate limiting + honeypot
- For production set `APP_ENV=production`, `APP_DEBUG=false`, `APP_FORCE_HTTPS=true`

## Tests
```bash
php artisan test
```
