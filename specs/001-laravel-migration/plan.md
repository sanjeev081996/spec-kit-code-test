# Implementation Plan: Laravel Migration for shubhjourneyholiday.com

**Branch**: `001-laravel-migration` | **Date**: 2025-11-01 | **Spec**: `/specs/001-laravel-migration/spec.md`
**Input**: Feature specification from `/specs/001-laravel-migration/spec.md`

## Summary

Migrate the public marketing site to a Laravel 11 application with simple coding: standard Laravel MVC, Blade templates, Bootstrap 5, and jQuery (included by request), with Docker for local development. Deliver parity for core pages first (P1), then lead capture and simple admin CRUD. Keep code SOLID and minimal, suitable for shared/VPS hosting.

## Technical Context

- Language/Version: PHP 8.2+ / Laravel 11 (latest)
- Primary Dependencies: Laravel, Blade, Bootstrap 5, jQuery; optional: Laravel Breeze (auth), Spatie Sluggable, Intervention/Image
- Storage: MySQL or MariaDB
- Testing: Pest/PHPUnit (feature + unit tests)
- Target Platform: Linux (shared/VPS), Nginx/Apache + PHP-FPM
- Local Dev: Docker (Laravel Sail preferred; custom docker-compose as fallback)
- Performance Goals: p95 page load < 1.5s; TTFB < 300ms on VPS; optimized images
- Constraints: Minimal packages, simple code paths, SOLID boundaries; compatible with shared hosting

### Local Dev (Docker)
- Option A (Preferred): Laravel Sail
  - `composer create-project laravel/laravel .` (or in container later)
  - `php artisan sail:install` (choose MySQL/MariaDB, Redis optional, Mailhog)
  - `./vendor/bin/sail up -d`
  - Use Sail for Composer/PHP/Artisan/Node: `sail composer install`, `sail npm install`, `sail npm run dev`
- Option B: Custom docker-compose (if Sail not desired)
  - Services: app (php-fpm 8.2/8.3 with pdo_mysql, gd/imagick, intl, zip), web (nginx), db (mysql:8 or mariadb:10.x), mailhog, optional redis
  - Volumes: bind-mount source; persistent DB volume
  - Tooling: composer + node/vite in app container; map 80->web; PHP ini: `upload_max_filesize`, `post_max_size`

## Constitution Check

Align with `.specify/memory/constitution.md` (SOLID):
- SRP: Controllers thin; services handle business rules; views handle presentation only
- OCP: Use interfaces/strategies for slugs, notifications, storage (avoid editing core flows)
- LSP: Implement interfaces without surprising contract changes
- ISP: Keep interfaces small (e.g., `EnquiryNotifier`, `SlugGenerator`)
- DIP: Depend on abstractions; infra (mail, storage) behind adapters

## Project Structure (Laravel)

```text
app/
  Models/
  Http/
    Controllers/
    Middleware/
  Services/
  Providers/
bootstrap/
config/
database/
  migrations/
  seeders/
public/
resources/
  views/
  lang/
routes/
  web.php
storage/
tests/
  Feature/
  Unit/
```

Structure Decision: Standard Laravel MVC with a minimal Services layer. Admin under `/admin` with auth middleware. Assets via Vite (`@vite`).

## Phases & Tasks

### Phase 0: Dev Environment (Docker + Tooling)
- [Sail] Install Sail with MySQL/MariaDB, Mailhog; run `sail up -d`
- [Compose] Add `docker-compose.yml` and `Dockerfile` (php-fpm extensions: pdo_mysql, gd/imagick, intl, zip; Composer installed)
- Generate app key: `php artisan key:generate` (or `sail artisan key:generate`)
- Install frontend deps: `npm i -D vite laravel-vite-plugin bootstrap @popperjs/core jquery` (or `sail npm ...`)
- Configure Vite and Blade `@vite(['resources/js/app.js','resources/css/app.css'])`

### Phase 1: Setup
- Configure `.env` (APP_NAME, APP_URL, DB creds, MAIL settings; dev MAIL to Mailhog)
- `php artisan storage:link`
- Base Blade layout with Bootstrap 5 and jQuery:
  - `resources/js/app.js`: `import 'bootstrap'; import '@popperjs/core'; import 'jquery';`
  - `resources/css/app.css`: `@import "bootstrap/dist/css/bootstrap.css";`
  - Use Blade components/partials for header, footer, meta
- Verify health route and 404 page

### Phase 2: Foundational Domain
- Models/migrations: Package, Destination, Testimonial, Enquiry, Media, User
- Add indexes and constraints: unique slugs, FKs, timestamps; soft deletes where useful (e.g., Testimonial)
- Services: `SlugGenerator`, `EnquiryNotifier` (Mail abstraction), optional `ImageService`
- PHP limits (dev): `upload_max_filesize`, `post_max_size`; configure `filesystems.php` disk (`public`)

### Phase 2.5: Content Inventory & Migration Plan
- Inventory current site: pages, navigation, images, downloads, meta
- Decide migration approach: manual entry via admin vs scripted import (if data available)
- Map URL structure; define 301 redirects for changed slugs/paths
- Copy static assets (logos, images) to storage/public as needed

### Phase 3: P1 — Public Site Parity
- Controllers + Blade views: Home, About, Packages index/show, Destinations index/show, Contact, Gallery
- Responsive layout using Bootstrap components (Navbar, Grid, Cards)
- Feature tests for core routes (HTTP 200) and 404

### Phase 4: P1 — Inquiry/Contact Form
- FormRequest validation, CSRF (`@csrf`), and rate limiting middleware (`throttle`)
- Enquiry persistence and admin email notification (`Mail` via queue optional)
- Spam mitigation: honeypot field + server-side checks
- Feature tests: success and validation error cases

### Phase 5: P1 — Simple Admin CRUD
- Auth scaffolding with Breeze (recommended) including password reset + throttling
- Admin routes under `/admin` with auth middleware
- CRUD for Package, Destination, Testimonial, Media; policies/guards; slug uniqueness validation
- Feature tests: admin auth guard, CRUD happy paths

### Phase 6: P2 — SEO & Content Hygiene
- Meta/title/description components; canonical URLs; Open Graph/Twitter meta
- `sitemap.xml` generator and `robots.txt`
- Apply 301 redirects per mapping from Phase 2.5

### Phase 7: P2 — Media/Gallery
- Configure disk (`public`) and upload endpoints
- Generate thumbnails/optimized variants (Intervention/Image optional) and WebP/JPEG outputs
- Attach images to Packages/Destinations; gallery page with pagination

### Phase 8: P3 — Booking Request (Optional)
- Booking model/migration referencing Package
- Form + notifications; simple admin list view
- Feature tests for submission and persistence

### Deployment
- Build assets: `npm run build` (or `sail npm run build`)
- Migrate + optimize: `php artisan migrate --force`; `php artisan config:cache route:cache view:cache`
- Ensure `APP_URL` set; force HTTPS in production middleware
- `php artisan storage:link` in target if missing
- Queue driver: `sync` for simple setups; `database`/`redis` if needed
- Backups: DB dump + uploads; document restore steps

## Testing Plan
- Feature: 200/404 routes; contact form success/fail; admin auth redirects; CRUD happy paths
- Unit: Slug generation uniqueness; services (EnquiryNotifier) with fakes
- Integration: Mail to Mailhog in dev; image upload + thumbnail

## Open Questions
- MySQL 8 vs MariaDB preference?
- Email provider in production (SMTP, SES, Mailgun)? Queue driver choice?
- Exact page list to mirror and final navigation order?
- Multilingual or blog required?

