import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.ts',
      ],
      refresh: true,
    }),
    vue(),
    tailwindcss(), // ✅ Tailwind v4 official plugin
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  server: {
    host: '0.0.0.0', // ✅ allows Docker to bind to all interfaces
    port: 5173,
    strictPort: true, // ✅ fail if 5173 already in use
    cors: true,
    hmr: {
      host: 'localhost', // ✅ what your browser should connect to
      port: 5173,
    },
  },
  css: {
    postcss: {}, // ✅ make sure PostCSS is picked up automatically
  },
})
