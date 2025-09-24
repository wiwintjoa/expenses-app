import './bootstrap'
import '../css/app.css' // Ensure CSS is imported first

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

// Import your custom route composable
import { useRoute } from '@/composables/useRoute'

const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'ExpenseTracker'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name: string) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ) as Promise<any>,
  setup({ el, app, props, plugin }) {
    const vueApp = createApp({ render: () => h(app, props) })

    // Use Inertia plugin
    vueApp.use(plugin)

    // Provide your custom route helper globally
    const { route } = useRoute()
    vueApp.provide('route', route)

    // Wait for next paint to mount the app
    requestAnimationFrame(() => {
      vueApp.mount(el)
      document.body.classList.remove('inertia-loading')
      document.body.classList.add('inertia-loaded')
    })
  },
})

// Initialize Inertia progress bar
InertiaProgress.init({ color: '#4B5563' })
