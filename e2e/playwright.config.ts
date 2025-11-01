import { defineConfig } from '@playwright/test';

export default defineConfig({
  outputDir: 'e2e/test-results',
  use: {
    baseURL: process.env.BASE_URL || 'http://localhost',
    headless: true,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  reporter: [['list'], ['html', { outputFolder: 'e2e-report' }]],
});