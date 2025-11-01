import { test, expect } from '@playwright/test';

// Phase 0: health endpoint responds
test('health /up responds 200', async ({ request }) => {
  const res = await request.get('/up');
  expect(res.status()).toBe(200);
});

// Phase 0: mailhog UI/API reachable
test('mailhog UI reachable', async ({ request }) => {
  const res = await request.get('http://localhost:8025');
  expect(res.status()).toBe(200);
});