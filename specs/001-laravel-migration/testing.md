# End-to-End Testing Plan (Playwright)

This document describes how to test each phase of the Laravel migration using Playwright (view-based/E2E) plus a small set of backend checks with Pest/PHPUnit where appropriate.

## Test Stack

- E2E/UI: Microsoft Playwright (Chromium by default; WebKit/Firefox optional)
- Backend/unit: Pest/PHPUnit (already bundled by Laravel)
- Target under test: `http://localhost` served by Docker `web` service

## Prerequisites

- Docker running; project stack up: `docker compose up -d`
- App reachable: `http://localhost/` and health: `http://localhost/up`
- Node.js 18+ on host (or run Playwright in a Node container)
- Seed data loaded: `docker compose exec app php artisan db:seed --force`

Install Playwright locally (host):

```bash
npm init -y                             # In repo root (or site/ if you prefer)
npm i -D @playwright/test
npx playwright install                  # Downloads browsers
```

Recommended layout for tests:

```text
e2e/
- playwright.config.ts
- phase-0.spec.ts
- phase-1.spec.ts
- phase-2.spec.ts
- phase-3-public.spec.ts
- phase-4-inquiry.spec.ts
- phase-5-admin.spec.ts
- phase-6-seo.spec.ts
- phase-7-media.spec.ts
- phase-8-booking.spec.ts
```

Basic Playwright config (`e2e/playwright.config.ts`):

```ts
import { defineConfig } from '@playwright/test';

export default defineConfig({
  use: {
    baseURL: process.env.BASE_URL || 'http://localhost',
    headless: true,
    trace: 'on-first-retry',
  },
  reporter: [['list'], ['html', { outputFolder: 'e2e-report' }]],
});
```

Run tests:

```bash
npx playwright test e2e/phase-1.spec.ts
# or all
npx playwright test
```

---

## Phase 0: Dev Environment (Docker + Tooling)

Goals
- Containers up (`app`, `web`, `db`, `mailhog`)
- App responds 200 on `/up`, Mailhog UI loads

Checks (phase-0.spec.ts)
- GET `/up` ? 200 and body contains "OK" (Laravel health)
- Open `http://localhost:8025` ? page title contains `MailHog`

Example
```ts
import { test, expect } from '@playwright/test';

test('health endpoint is up', async ({ request }) => {
  const res = await request.get('/up');
  expect(res.status()).toBe(200);
});

test('mailhog UI loads', async ({ page }) => {
  await page.goto('http://localhost:8025');
  await expect(page).toHaveTitle(/MailHog/i);
});
```

---

## Phase 1: Setup (Layout, Assets, 404)

Goals
- Home renders, base layout present, assets loaded via Vite
- 404 page renders for unknown routes

Checks (phase-1.spec.ts)
- GET `/` ? 200, contains site name (from `APP_NAME`)
- Navbar links visible (About, Packages, Destinations, Gallery, Contact)
- Unknown route `/not-found` ? shows 404 copy

Example
```ts
import { test, expect } from '@playwright/test';

test('home renders with navbar and footer', async ({ page }) => {
  await page.goto('/');
  await expect(page.locator('nav')).toBeVisible();
  await expect(page.locator('footer')).toBeVisible();
  await expect(page.locator('text=Shubh Journey Holiday')).toBeVisible();
});

test('404 page shows for unknown route', async ({ page }) => {
  await page.goto('/not-found');
  await expect(page.getByRole('heading', { name: '404' })).toBeVisible();
});
```

---

## Phase 2: Foundational Domain (DB, Migrations, Seeds)

Goals
- DB schema present; seed content inserted (Packages, Destinations, Testimonials)

Checks (phase-2.spec.ts)
- Not strictly UI; verify via a simple page or artisan command
- Option A (simple UI hook once public pages exist): after Phase 3, ensure seeded entities appear on listings
- Option B (CLI): run `php artisan tinker` is out of scope for Playwright; rely on Laravel feature tests

Laravel feature test suggestion (Pest):
```php
public function test_database_is_seeded(): void
{
    $this->assertDatabaseHas('packages', ['slug' => 'golden-triangle-tour']);
    $this->assertDatabaseHas('destinations', ['slug' => 'goa']);
}
```

---

## Phase 3: Public Site Parity

Goals
- Pages: Home, About, Packages (index/show), Destinations (index/show), Gallery, Contact

Checks (phase-3-public.spec.ts)
- Each route returns 200 and key elements render (hero, lists, breadcrumbs/nav)
- Package/Destination pages render seeded items

Example
```ts
import { test, expect } from '@playwright/test';

test('packages index shows at least one item', async ({ page }) => {
  await page.goto('/packages');
  await expect(page.getByRole('heading', { name: /packages/i })).toBeVisible();
  await expect(page.locator('[data-testid="package-card"]').first()).toBeVisible();
});
```

---

## Phase 4: Inquiry/Contact Form

Goals
- Valid submission persists `enquiries` and sends email (captured by Mailhog)
- Invalid submission shows field-level errors
- Spam protections (honeypot, throttle) in place

Checks (phase-4-inquiry.spec.ts)
- Submit invalid payload ? see validation errors
- Submit valid payload ? success message on page; Mailhog shows a new message

Example
```ts
import { test, expect } from '@playwright/test';

const mailhog = 'http://localhost:8025';

test('contact form validates and submits', async ({ page, request }) => {
  await page.goto('/contact');
  await page.fill('input[name="name"]', 'Test User');
  await page.fill('input[name="email"]', 'test@example.com');
  await page.fill('input[name="phone"]', '9999999999');
  await page.fill('textarea[name="message"]', 'Hello!');
  await page.click('button[type="submit"]');
  await expect(page.locator('.alert-success')).toBeVisible();

  // Check Mailhog has at least one message
  const res = await request.get(`${mailhog}/api/v2/messages`);
  expect(res.status()).toBe(200);
  const data = await res.json();
  expect(data?.count ?? 0).toBeGreaterThan(0);
});
```

---

## Phase 5: Admin CRUD (when implemented)

Goals
- Admin login required; CRUD for Packages, Destinations, Testimonials, Media

Checks (phase-5-admin.spec.ts)
- Unauthenticated `/admin` ? redirected to login
- Login ? dashboard visible
- Create/Edit/Delete flows reflect on public pages

---

## Phase 6: SEO & Content Hygiene

Goals
- Meta tags, canonical URLs, sitemap and robots

Checks (phase-6-seo.spec.ts)
- `head` contains title/description and Open Graph/Twitter tags
- `/sitemap.xml` returns 200 and includes top-level routes
- `/robots.txt` contains expected directives

Example
```ts
import { test, expect } from '@playwright/test';

test('meta tags present', async ({ page }) => {
  await page.goto('/');
  const desc = await page.locator('meta[name="description"]').getAttribute('content');
  expect(desc).toBeTruthy();
});
```

---

## Phase 7: Media/Gallery

Goals
- Upload images, generate thumbs, display in gallery

Checks (phase-7-media.spec.ts)
- Upload through admin; gallery shows the image with correct alt text
- Uploaded file accessible from public disk

---

## Phase 8: Booking Request (Optional)

Goals
- Request form persists and notifies admin

Checks (phase-8-booking.spec.ts)
- Invalid ? errors; valid ? success + message in Mailhog

---

## CI Suggestions

- Add `npm ci` and `npx playwright install --with-deps` to CI job
- Start stack: `docker compose up -d`
- Wait-for-http at `http://localhost/up` (simple retry loop)
- Run `npx playwright test --reporter=html` and upload `e2e-report`

## Tips

- Use `data-testid` attributes in Blade templates for reliable selectors
- Keep E2E tests focused on user-visible behavior; defer business-rule specifics to Laravel feature tests
- Run headful locally with `npx playwright test --headed --debug` when diagnosing