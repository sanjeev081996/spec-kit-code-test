import { test, expect } from '@playwright/test';

// UI-based checks for Phase 0/1

test('health /up via page returns 200 and simple body', async ({ page }) => {
  const res = await page.goto('/up');
  expect(res?.status()).toBe(200);
  await expect(page.locator('body')).toContainText(/OK|Healthy|Application|Laravel/i);
});


test('navbar links are visible and About navigates', async ({ page }) => {
  await page.goto('/');
  await expect(page.locator('nav')).toBeVisible();
  await expect(page.getByRole('link', { name: /about/i })).toBeVisible();
  await page.getByRole('link', { name: /about/i }).click();
  await expect(page).toHaveURL(/\/about$/);
  await expect(page.getByRole('heading', { name: /about/i })).toBeVisible();
});