<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-3">
            <CurrencyDollarIcon class="h-8 w-8 text-indigo-600" />
            <h1 class="text-xl font-semibold text-gray-900">Expense Tracker</h1>
          </div>
          <nav class="flex items-center space-x-4">
            <slot name="nav">
              <Link
                :href="route('expenses.index')"
                class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
                :class="{ 'text-indigo-600 bg-indigo-50': current('expenses.index') }"
              >
                Dashboard
              </Link>
              <Link
                :href="route('expenses.create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
              >
                Add Expense
              </Link>
            </slot>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <p class="text-center text-sm text-gray-500">
          &copy; 2025 Expense Tracker. Built with Laravel & Vue.js
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { CurrencyDollarIcon } from '@heroicons/vue/24/outline'
import { useRoute } from '@/composables/useRoute'

const { route, current } = useRoute()
</script>