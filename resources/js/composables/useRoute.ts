// resources/js/composables/useRoute.ts
export const useRoute = () => {
  // Define all routes with proper parameter handling
  const routes: Record<string, string | ((params?: Record<string, any>) => string)> = {
    'expenses.index': '/expenses',
    'expenses.create': '/expenses/create',
    'expenses.show': (params?: Record<string, any>) => `/expenses/${params?.id}`,
    'expenses.edit': (params?: Record<string, any>) => `/expenses/${params?.id}/edit`,
    'expenses.store': '/expenses',
    'expenses.update': (params?: Record<string, any>) => `/expenses/${params?.id}`,
    'expenses.destroy': (params?: Record<string, any>) => `/expenses/${params?.id}`,
  }

  // Generate URL for a given route name
  const route = (name: string, params?: Record<string, any>): string => {
    const routeDef = routes[name]
    if (!routeDef) {
      console.error(`Route [${name}] not defined`)
      return '#'
    }
    
    if (typeof routeDef === 'function') {
      return routeDef(params)
    }
    
    return routeDef
  }

  // Check if current route matches
  const current = (name: string): boolean => {
    try {
      const currentPath = window.location.pathname
      const routePath = route(name)
      return currentPath === routePath || currentPath.startsWith(routePath + '/')
    } catch {
      return false
    }
  }

  return { route, current }
}