import './bootstrap'
import '../css/app.css'

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
    const vueApp = createApp({ render: () => h(app, props) }).use(plugin)

    // Provide your custom route helper globally
    const { route } = useRoute()
    vueApp.provide('route', route)

    vueApp.mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
