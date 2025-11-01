# Feature Specification: Laravel Migration for shubhjourneyholiday.com

**Feature Branch**: `001-laravel-migration`  
**Created**: 2025-11-01  
**Status**: Draft  
**Input**: User description: "Migrate shubhjourneyholiday.com to Laravel with simple coding"

## User Scenarios & Testing (mandatory)

### User Story 1 - Public Site Parity (Priority: P1)

Recreate the existing public website (home, about, services/tour packages, destinations, contact, footer/nav) in Laravel using Blade templates and simple controllers, keeping the layout responsive and fast.

**Why this priority**: Users must see a functioning site immediately; establishes baseline parity and navigation.

**Independent Test**: Visiting core routes renders pages with content and navigation without backend data dependencies.

**Acceptance Scenarios**:

1. Given the app is deployed, when I visit `/`, then I see the home page with header, hero, services, and footer.
2. Given navigation is present, when I click About/Services/Contact, then the respective pages render without errors and return HTTP 200.
3. Given an unknown route, when I visit `/not-found`, then I see a friendly 404 page.

---

### User Story 2 - Inquiry/Contact Form (Priority: P1)

Provide a simple inquiry form (name, email, phone, message) that stores submissions and emails the admin.

**Why this priority**: Lead capture is core to the business; must work on day one.

**Independent Test**: Submitting the form validates input, persists an `enquiries` record, and sends an email (or logs in local).

**Acceptance Scenarios**:

1. Given valid input, when I submit the form, then I see a success message and data is saved in the database.
2. Given invalid input (e.g., missing email), when I submit, then I see field-level errors and nothing is persisted.
3. Given basic spam protection, when a bot triggers the honeypot, then the request is ignored without error to the user.

---

### User Story 3 - Simple Admin CRUD (Priority: P1)

Add a minimal password-protected admin to manage tour packages, destinations, and testimonials using straightforward CRUD screens.

**Why this priority**: Enables non-developers to update content; reduces dev churn.

**Independent Test**: Admin can create/edit/delete packages and destinations; changes reflect on public pages.

**Acceptance Scenarios**:

1. Given an authenticated admin, when I create a package with title, slug, description, price, and images, then it appears on `/packages/{slug}`.
2. Given an authenticated admin, when I edit an existing record, then the public view shows the updated content.
3. Given an unauthenticated user, when I access `/admin`, then I am redirected to login.

---

### User Story 4 - SEO & Content Hygiene (Priority: P2)

Add SEO-friendly slugs, per-page meta tags, sitemap, and robots.txt; migrate key meta from current site where available.

**Independent Test**: Pages render canonical URLs and meta; `/sitemap.xml` lists important routes.

---

### User Story 5 - Gallery/Media (Priority: P2)

Media library for images used by packages/destinations; basic upload, thumbnailing, and reuse.

**Independent Test**: Upload and attach images to a package; images serve optimized (webp/jpg) variants.

---

### User Story 6 - Booking Request (Priority: P3)

Optional booking request form referencing a selected package; store request and notify admin.

**Independent Test**: Submitting booking request creates a record and sends a notification.

### Edge Cases

- Missing or broken images: show safe placeholders and avoid layout shift.
- Large image uploads: enforce size/type limits; server-side validation.
- Duplicate slugs: enforce uniqueness with validation and safe slug generation.
- Form spam: honeypot + rate limiting; optional CAPTCHA if needed.
- Email failures: queue with retry; fall back to logging in local/dev.

## Requirements (mandatory)

### Functional Requirements

- FR-001: Render public pages using Blade templates for home, about, services/packages, destinations, contact, gallery.
- FR-002: Provide a contact/inquiry form with server-side validation, persistence, and email notification.
- FR-003: Provide admin authentication and CRUD for Packages, Destinations, Testimonials, and Media.
- FR-004: Generate SEO-friendly slugs and per-page meta; provide sitemap.xml and robots.txt.
- FR-005: Serve optimized images and support basic media management (upload, list, attach).
- FR-006: Optional booking request flow referencing a Package (P3, toggleable).
- FR-007: Content editing should be simple; avoid complex page builders.
- FR-008: Adhere to SOLID architecture and keep implementation “simple” (minimal abstractions until needed).

Unclear/Confirm:

- FR-009: Email provider preference (SMTP creds, SES, Mailgun)? [NEEDS CLARIFICATION]
- FR-010: Hosting environment (cPanel/Shared, VPS, Docker)? [NEEDS CLARIFICATION]
- FR-011: Exact list of pages from current site to mirror. [NEEDS CLARIFICATION]
- FR-012: Multi-language content? [NEEDS CLARIFICATION]

### Key Entities

- Package: id, title, slug, summary, description, price_from, duration, images, is_published.
- Destination: id, name, slug, description, hero_image, is_published.
- Testimonial: id, author, quote, rating, is_published.
- Enquiry: id, name, email, phone, message, source_page, status.
- Media: id, file_path, alt_text, mime_type, size.
- User: id, name, email, password (admin only).
- Setting (optional): site_name, contact_email, social_links, meta_defaults.

## Success Criteria (mandatory)

### Measurable Outcomes

- SC-001: Public parity for core pages delivered in first deploy (P1 complete).
- SC-002: Inquiry form produces deliverable emails and persists submissions (≥95% deliverability in staging tests).
- SC-003: Page load p95 < 1.5s on shared/VPS hosting; images optimized.
- SC-004: Admin can create/edit/delete core entities without dev assistance.

