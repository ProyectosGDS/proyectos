import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    outDir: 'C:/laragon/www/plataforma-gds/apps/admin',
  },
  // base : '/gds/plataforma-gds/apps/admin'
  base : '/plataforma-gds/apps/admin'
})
