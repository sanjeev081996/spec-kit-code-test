import { test, expect } from '@playwright/test';

// Phase 1: Home returns 200 and site name text is present
test('home renders with site name', async ({ request }) => {
  const res = await request.get('/');
  expect(res.status()).toBe(200);
  const body = await res.text();
  expect(body).toMatch(/Shubh Journey Holiday/i);
});

// Phase 1: Unknown route shows 404 content
test('unknown route returns 404 with 404 marker', async ({ request }) => {
  const res = await request.get('/not-found');
  expect(res.status()).toBe(404);
  const body = await res.text();
  expect(body).toMatch(/404/);
});