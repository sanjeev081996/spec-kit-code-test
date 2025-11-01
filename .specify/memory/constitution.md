# Vet2 Dynamic Web App Constitution

## Core Principles (SOLID)

### I. Single Responsibility Principle (SRP)
Each module/class/component has exactly one reason to change. Boundaries are explicit: view logic, domain rules, and data access are separated. Avoid “god” services/components; extract cohesive functions; keep files small and purpose-driven.

### II. Open/Closed Principle (OCP)
Software entities are open for extension but closed for modification. Prefer extension points (interfaces, composition, events, configuration, feature flags) over editing existing behavior. New features integrate via strategy/adapters/plugins rather than editing core flows.

### III. Liskov Substitution Principle (LSP)
Subtypes must be substitutable for their base types without breaking expectations. Do not strengthen preconditions or weaken postconditions. Preserve invariants and behavior. Validate through contract tests against shared interfaces and public APIs.

### IV. Interface Segregation Principle (ISP)
Prefer many small, focused interfaces over fat ones. Clients should not depend on methods they do not use. In the web stack: segregate service interfaces, keep UI props minimal, design lean DTOs, and avoid catch‑all utility services.

### V. Dependency Inversion Principle (DIP)
High‑level policies do not depend on low‑level details; both depend on abstractions. Use dependency injection where reasonable, pass dependencies explicitly, and isolate framework/infra behind ports/adapters to keep the domain independent and testable.

## Architecture & Tech Standards

- Layering: presentation (UI), application (use cases), domain (business rules), infrastructure (HTTP, DB, cache). Domain has no framework imports.
- Modularity: feature‑based folders; shared kernels are small and stable; avoid cyclic dependencies.
- API design: clear contracts, versioned endpoints/schemas, backward compatibility by default; prefer idempotent operations for mutations where applicable.
- State & data: normalize client state; cache with explicit invalidation; avoid hidden global state.
- Errors & observability: structured logs, correlation IDs, actionable error messages; metrics and tracing for critical paths.
- Performance: set budgets (TTFB, LCP, API latency); use lazy loading, pagination, and streaming where appropriate.
- Security & privacy: authn/authz enforced at boundaries; input validation at edges; follow OWASP Top 10; least privilege for secrets and services.
- Accessibility: adhere to WCAG for UI; keyboard navigation and ARIA where applicable.

## Development Workflow & Quality Gates

- Branching & reviews: short‑lived feature branches; small PRs; require review with architectural and SOLID checks.
- Testing: unit tests for domain and utils; integration tests for adapters and contracts; E2E for critical journeys; include regression tests for bugs.
- CI/CD: lint, type checks, tests, and build on PR; block merge on failures; produce artifacts with provenance and changelogs.
- Definition of Done: tests added/updated; docs and API contracts updated; telemetry and alerts configured for new critical paths.
- Documentation: update readmes per feature; record decisions in lightweight ADRs; keep examples runnable.

## Governance

- This constitution supersedes conflicting local practices. Exceptions require an ADR with rationale and a rollback plan.
- Changes to core principles or architecture require PR review by maintainers and version bump of this document.
- All reviews verify SOLID adherence, modular boundaries, observability, and performance/security budgets.

**Version**: 1.0.0 | **Ratified**: 2025-11-01 | **Last Amended**: 2025-11-01

