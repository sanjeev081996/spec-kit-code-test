---

description: "Concrete task list for Laravel migration with Docker, Blade, Bootstrap, jQuery"
---

# Tasks: Laravel Migration for shubhjourneyholiday.com

**Input**: `/specs/001-laravel-migration/plan.md`, `/specs/001-laravel-migration/spec.md`
**Prerequisites**: plan.md and spec.md are present

## Format: `[ID] [P?] [Story] Description`

- [P]: Can run in parallel (different files, no dependencies)
- [Story]: US1 (Public Parity), US2 (Inquiry), US3 (Admin), US4 (SEO), US5 (Media), US6 (Booking)

---

## Phase 0: Dev Environment (Docker + Tooling)

- [x] T0001 [P] Create `docker` folder and add `docker-compose.yml` with services: app (php-fpm), web (nginx), db (mysql/mariadb), mailhog
- [x] T0002 [P] Add `Dockerfile` for app with PHP 8.3, extensions: pdo_mysql, intl, zip, gd/imagick; install Composer
- [x] T0003 Add nginx vhost config to serve `public/` (mount into web container)
- [x] T0004 Bring up stack: `docker compose up -d` (or Sail alternative)
- [x] T0005 Run Composer (in container): `composer create-project laravel/laravel .` (skip if project pre-existing)
- [x] T0006 Generate app key: `php artisan key:generate`
- [x] T0007 [P] Add Node/Vite deps: `npm i -D vite laravel-vite-plugin bootstrap @popperjs/core jquery`
- [x] T0008 Verify Vite dev server and PHP app respond locally

---

## Phase 1: Setup

- [x] T0100 Configure `.env` (APP_NAME, APP_URL, DB creds, MAIL to Mailhog)
- [x] T0101 Run `php artisan storage:link`
- [x] T0102 Create base layout `resources/views/layouts/app.blade.php` with `@vite(['resources/js/app.js','resources/css/app.css'])`
- [x] T0103 [P] Create shared partials: `resources/views/partials/{header,footer,meta}.blade.php`
- [x] T0104 [P] Wire assets: `resources/js/app.js` (import bootstrap, popper, jquery); `resources/css/app.css` (import bootstrap CSS)
- [x] T0105 Add health and 404 routes in `routes/web.php`; create `resources/views/errors/404.blade.php`

---

## Phase 2: Foundational Domain

- [ ] T0200 Create models and migrations: `app/Models/{Package,Destination,Testimonial,Enquiry,Media}.php`
- [ ] T0201 Define migrations in `database/migrations/*` with FKs, unique indexes on slugs, timestamps
- [ ] T0202 [P] Enable soft deletes where applicable (e.g., Testimonial)
- [ ] T0203 Create services: `app/Services/SlugGenerator.php`, `app/Services/EnquiryNotifier.php`
- [ ] T0204 Configure `config/filesystems.php` disks; set `public` for uploads
- [ ] T0205 Set PHP upload limits in dev (Docker PHP ini) for images
- [ ] T0206 [P] Seed sample data: `database/seeders/{PackageSeeder,DestinationSeeder,TestimonialSeeder}.php`

---

## Phase 2.5: Content Inventory & Migration Plan

- [ ] T0250 Inventory current site pages, nav, assets, and meta (document in `specs/001-laravel-migration/research.md`)
- [ ] T0251 Produce URL map and 301 redirect plan (old → new paths) in `specs/001-laravel-migration/redirects.md`
- [ ] T0252 Copy static assets (logos, hero images) into `public/` or storage and reference paths

---

## Phase 3: User Story 1 — Public Site Parity (P1)

- [ ] T0300 [US1] Routes in `routes/web.php` for: `/`, `/about`, `/packages`, `/packages/{slug}`, `/destinations`, `/destinations/{slug}`, `/gallery`, `/contact`
- [ ] T0301 [US1] Controllers in `app/Http/Controllers/Public/{HomeController,AboutController,PackageController,DestinationController,GalleryController,ContactController}.php`
- [ ] T0302 [US1] Views in `resources/views/public/{home,about,packages/index,packages/show,destinations/index,destinations/show,gallery,contact}.blade.php`
- [ ] T0303 [P] [US1] Navbar/footer with active state; responsive Bootstrap layout
- [ ] T0304 [US1] Feature tests (Pest) for each route returning 200 and custom 404

**Checkpoint**: Public navigation and pages render with sample content

---

## Phase 4: User Story 2 — Inquiry/Contact Form (P1)

- [ ] T0400 [US2] FormRequest `app/Http/Requests/StoreEnquiryRequest.php` with validation (name, email, phone, message)
- [ ] T0401 [US2] Update `ContactController@submit` to persist `Enquiry` and dispatch notification via `EnquiryNotifier`
- [ ] T0402 [US2] Add CSRF (`@csrf`) and honeypot field; apply rate limiting middleware `throttle`
- [ ] T0403 [US2] Feature tests: success path (DB persisted, fake mail asserted) and validation failures

**Checkpoint**: Leads captured and visible in DB; emails delivered to Mailhog in dev

---

## Phase 5: User Story 3 — Simple Admin CRUD (P1)

- [ ] T0500 [US3] Install Breeze (or minimal auth); configure routes under `/admin` with auth middleware
- [ ] T0501 [US3] Admin controllers/views for CRUD: Package, Destination, Testimonial, Media
- [ ] T0502 [US3] Policies/guards; enforce slug uniqueness; simple flash messages for create/update/delete
- [ ] T0503 [US3] Feature tests: unauthenticated redirected; CRUD happy paths work

**Checkpoint**: Non-developers can update content safely

---

## Phase 6: User Story 4 — SEO & Content Hygiene (P2)

- [ ] T0600 [US4] Blade meta component (title, description, og/twitter)
- [ ] T0601 [US4] Add canonical URLs; implement 301 redirects per mapping
- [ ] T0602 [US4] `sitemap.xml` route/controller and static `robots.txt`

---

## Phase 7: User Story 5 — Media/Gallery (P2)

- [ ] T0700 [US5] Configure uploads to `public` disk; UI for image upload
- [ ] T0701 [US5] Optional Intervention/Image thumbnail + webp generation service
- [ ] T0702 [US5] Attach images to Packages/Destinations; gallery listing with pagination

---

## Phase 8: User Story 6 — Booking Request (Optional, P3)

- [ ] T0800 [US6] Booking model/migration referencing Package
- [ ] T0801 [US6] Form + controller to persist and notify; admin list view
- [ ] T0802 [US6] Feature tests for submission and validation

---

## Deployment & Ops

- [ ] T0900 Build assets: `npm run build`; verify `@vite` paths in Blade
- [ ] T0901 Run `php artisan migrate --force`; `php artisan config:cache route:cache view:cache`
- [ ] T0902 Ensure `APP_URL` and HTTPS redirect middleware; `php artisan storage:link`
- [ ] T0903 Document DB/upload backups and restore; choose queue driver (sync vs database/redis)

---

## Testing Summary

- [ ] T1000 Feature tests: 200/404 routes; contact form success/fail; admin guards; CRUD paths
- [ ] T1001 Unit tests: SlugGenerator; EnquiryNotifier (with fakes)
- [ ] T1002 Integration: Mail to Mailhog; image upload + thumbnail

---

## Questions for You

1) Hosting: MySQL 8 or MariaDB? Shared hosting vs VPS? Docker in production or only dev?
2) Email provider for production (SMTP details, SES, Mailgun)? Use queue or send sync?
3) Exact pages/sections to mirror from the current site (final nav order)? Any pages to drop/add?
4) Do you want multilingual support or a blog/news section in scope?
5) Prefer Laravel Sail for dev environment, or keep custom docker-compose approach?

