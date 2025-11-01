import { test, expect } from '@playwright/test';

test.describe('Phase 3 public pages', () => {
  test('packages index shows card and package detail loads', async ({ page }) => {
    await page.goto('/packages');
    await expect(page.getByRole('heading', { name: /packages/i })).toBeVisible();
    const first = page.locator('[data-testid="package-card"]').first();
    await expect(first).toBeVisible();

    // Click first card link
    const link = first.getByRole('link').first();
    const href = await link.getAttribute('href');
    await link.click();
    await expect(page).toHaveURL(href!);
    await expect(page.locator('[data-testid="package-title"]')).toBeVisible();
  });

  test('destinations index shows card and detail loads', async ({ page }) => {
    await page.goto('/destinations');
    await expect(page.getByRole('heading', { name: /destinations/i })).toBeVisible();
    const first = page.locator('[data-testid="destination-card"]').first();
    await expect(first).toBeVisible();
    const link = first.getByRole('link').first();
    const href = await link.getAttribute('href');
    await link.click();
    await expect(page).toHaveURL(href!);
    await expect(page.locator('[data-testid="destination-title"]')).toBeVisible();
  });
});