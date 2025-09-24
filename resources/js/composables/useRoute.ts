// resources/js/composables/useRoute.ts
import { usePage } from '@inertiajs/vue3'

type RouteDef = string | ((params?: Record<string, any>) => string)

export const useRoute = () => {
  // Allow both static and dynamic routes
  const routes: Record<string, RouteDef> = {
    'expenses.index': '/expenses',
    'expenses.create': '/expenses/create',
    'expenses.show': (params?: Record<string, any>) => `/expenses/${params?.id}`,
    'expenses.edit': (params?: Record<string, any>) => `/expenses/${params?.id}/edit`,
  }

  // Generate URL for a given route name
  const route = (name: string, params?: Record<string, any>): string => {
    const r = routes[name]
    if (!r) throw new Error(`Route [${name}] not defined`)
    return typeof r === 'function' ? r(params) : r
  }

  // Active route checker (works without Ziggy)
  const current = (name: string): boolean => {
    const page = usePage()
    const url = (page.props as any)?.url || window.location.pathname
    const expected = route(name, { id: '__dummy__' }).replace('/__dummy__', '')
    return url.startsWith(expected)
  }

  return { route, current }
}
