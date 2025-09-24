import { defineConfig } from 'tailwindcss'

export default defineConfig({
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-bg-primary)',
        light: 'var(--color-bg-light)',
        dark: 'var(--color-bg-dark)',
        link: 'var(--color-text-link)',
        'link-hover': 'var(--color-text-link-hover)',
        border: 'var(--color-border)',
        'button-primary': 'var(--color-button-primary)',
        'button-primary-hover': 'var(--color-button-primary-hover)',
        'button-danger': 'var(--color-button-danger)',
        'button-danger-hover': 'var(--color-button-danger-hover)',
      },
      fontFamily: {
        sans: ['ui-sans-serif', 'system-ui'],
      },
      borderRadius: {
        md: '0.375rem',
      },
      boxShadow: {
        sm: '0 1px 2px 0 rgb(0 0 0 / 0.05)',
      },
    },
  },
  corePlugins: {
    preflight: true, // generates base styles like body
  },
})
