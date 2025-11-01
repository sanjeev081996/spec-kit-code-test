import { defineConfig } from '@playwright/test';
export default defineConfig({
  use: { baseURL: process.env.BASE_URL || 'http://localhost', trace: 'on-first-retry' },
  reporter: [['list'], ['html', { outputFolder: 'e2e-report' }]],
});