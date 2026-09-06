
# DocPatient

A Laravel-based medical appointment management application.

## What it does

DocPatient manages medical appointments, doctors, and cabinets (clinics), with role-oriented views for admins, doctors, and patients (`resources/views/admin`, `doctor`, `userzone`). It includes a Livewire-powered doctor search/listing component (`app/Livewire/Doctors.php`), a health tips feature backed by its own model, migrations, and service layer (`app/Models/HealthTip.php`, `app/Services/HealthTipService.php`), an IP-based geolocation service (`app/Services/IpInfoService.php`), and a news integration (`app/Services/NewsApiService.php`). File/media uploads are handled via Spatie Media Library, and the app has scheduled database/full backups via Spatie Backup.

## Tech stack

- **Backend**: Laravel (`laravel/framework`), Livewire
- **Packages**: `spatie/laravel-backup`, `spatie/laravel-medialibrary`, `spatie/laravel-flare` (error tracking), `league/flysystem-aws-s3-v3` (S3 storage), `symfony/postmark-mailer` + `wildbit/postmark-php` (transactional mail)
- **Frontend**: Tailwind CSS (with `@tailwindcss/forms`), Alpine.js, Vite (`laravel-vite-plugin`)
- **Testing**: Pest (test suite under `tests/Feature` and `tests/Unit`)
- **Deployment**: Vercel (`vercel.json`, `api/index.php` entry point, `vercel` npm dependency)

## Getting started

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Install JS dependencies
npm install

# Run database migrations
php artisan migrate

# (optional) seed sample doctors, cabinets, and health tips
php artisan db:seed
```

Then run the app and asset pipeline:

```bash
# Terminal 1: Laravel dev server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Build assets for production with `npm run build`.

Run tests with:

```bash
php artisan test
```

<!-- TODO: add a screenshot -->

## License

<!-- TODO: confirm whether a LICENSE file is present in the repository -->
No license file was found in the evidence for this repository.
