<template>
  <AppLayout>
    <Head title="Expense Tracker" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3">
          <CurrencyDollarIcon class="h-8 w-8 text-indigo-600" />
          <h1 class="text-3xl font-bold text-gray-900">Expense Dashboard</h1>
        </div>
        <Link
          :href="route('expenses.create')"
          class="btn btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Expense
        </Link>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
          <div class="card-body">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <CurrencyDollarIcon class="h-8 w-8 text-green-600" />
              </div>
              <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Expenses</p>
                <p class="text-2xl font-semibold text-gray-900">${{ formatCurrency(currentTotal) }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <DocumentTextIcon class="h-8 w-8 text-blue-600" />
              </div>
              <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Total Records</p>
                <p class="text-2xl font-semibold text-gray-900">{{ expenses.data.length }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <TagIcon class="h-8 w-8 text-purple-600" />
              </div>
              <div class="ml-5">
                <p class="text-sm font-medium text-gray-500">Categories</p>
                <p class="text-2xl font-semibold text-gray-900">{{ categories.length }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Category Chart -->
      <div v-if="categoryStats.length" class="card">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900">Expenses by Category</h3>
        </div>
        <div class="card-body">
          <PieChart :chartData="pieChartData" />
        </div>
      </div>

      <!-- Filters -->
      <div class="card">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900">Filters</h3>
        </div>
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select v-model="form.category" class="form-select">
                <option value="all">All Categories</option>
                <option v-for="category in categories" :key="category" :value="category">
                  {{ category }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input type="date" v-model="form.date" class="form-input" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
              <input type="date" v-model="form.start_date" class="form-input" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
              <input type="date" v-model="form.end_date" class="form-input" />
            </div>
          </div>
          <div class="mt-4">
            <button @click="clearFilters" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Expenses Table -->
      <div class="card">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900">Recent Expenses</h3>
        </div>
        <div class="card-body">
          <div v-if="expenses.data.length === 0" class="text-center py-12">
            <DocumentTextIcon class="h-12 w-12 text-gray-400 mx-auto mb-4" />
            <p class="text-gray-500 text-lg">No expenses found</p>
            <p class="text-gray-400 text-sm">Add your first expense to get started!</p>
            <Link
              :href="route('expenses.create')"
              class="btn btn-primary mt-4"
            >
              Add Your First Expense
            </Link>
          </div>

          <div v-else class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Description
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Amount
                  </th>
                  <th class="relative px-6 py-3">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="expense in expenses.data" :key="expense.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(expense.date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ expense.description }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      :class="getCategoryColor(expense.category)"
                    >
                      {{ expense.category }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${{ formatCurrency(expense.amount) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                    <Link
                      :href="route('expenses.edit', { id: expense.id })"
                      class="text-indigo-600 hover:text-indigo-900 font-medium"
                    >
                      Edit
                    </Link>
                    <button
                      @click="deleteExpense(expense.id)"
                      class="text-red-600 hover:text-red-900 font-medium"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="expenses.last_page > 1" class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-700">
              Showing {{ expenses.from }} to {{ expenses.to }} of {{ expenses.total }} results
            </div>
            <div class="flex space-x-2">
              <Link
                v-if="expenses.prev_page_url"
                :href="expenses.prev_page_url"
                class="btn btn-secondary"
              >
                Previous
              </Link>
              <Link
                v-if="expenses.next_page_url"
                :href="expenses.next_page_url"
                class="btn btn-secondary"
              >
                Next
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'
import AppLayout from '@/Layout/AppLayout.vue'
import PieChart from './PieChart.vue'
import { useRoute } from '@/composables/useRoute'
import {
  CurrencyDollarIcon,
  DocumentTextIcon,
  TagIcon,
  PlusIcon,
} from '@heroicons/vue/24/outline'

interface Expense {
  id: number
  amount: string
  description: string
  category: string
  date: string
}

interface CategoryStat {
  category: string
  total: string
}

interface Props {
  expenses: {
    data: Expense[]
    last_page: number
    prev_page_url?: string
    next_page_url?: string
    from?: number
    to?: number
    total?: number
  }
  categories: string[]
  categoryStats: CategoryStat[]
  filters: Record<string, string>
  totalExpenses: number
  currentTotal: string
}

const props = defineProps<Props>()
const { route } = useRoute()

const form = reactive({
  category: props.filters.category || 'all',
  date: props.filters.date || '',
  start_date: props.filters.start_date || '',
  end_date: props.filters.end_date || '',
})

const formatCurrency = (amount: string | number) => {
  return parseFloat(amount.toString()).toFixed(2)
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const getCategoryColor = (category: string) => {
  const colors: Record<string, string> = {
    'Transport': 'bg-blue-100 text-blue-800',
    'Food & Drink': 'bg-green-100 text-green-800',
    'Shopping': 'bg-purple-100 text-purple-800',
    'Entertainment': 'bg-yellow-100 text-yellow-800',
    'Health': 'bg-red-100 text-red-800',
    'Others': 'bg-gray-100 text-gray-800',
  }
  return colors[category] || 'bg-gray-100 text-gray-800'
}

const applyFilters = () => {
  const params: Record<string, string> = {}
  if (form.category && form.category !== 'all') params.category = form.category
  if (form.date) params.date = form.date
  if (form.start_date) params.start_date = form.start_date
  if (form.end_date) params.end_date = form.end_date

  router.get(route('expenses.index'), params, {
    preserveState: true,
    replace: true,
  })
}

const clearFilters = () => {
  form.category = 'all'
  form.date = ''
  form.start_date = ''
  form.end_date = ''
  applyFilters()
}

const deleteExpense = (id: number) => {
  if (!confirm('Are you sure you want to delete this expense?')) return

  router.delete(route('expenses.destroy', { id }), {
    onSuccess: () => {
      // The page will be refreshed automatically
    },
  })
}

// Prepare data for PieChart
const pieChartData = {
  labels: props.categoryStats.map(s => s.category),
  datasets: [
    {
      label: 'Expenses',
      data: props.categoryStats.map(s => parseFloat(s.total)),
      backgroundColor: [
        '#3B82F6', // blue
        '#10B981', // green
        '#8B5CF6', // purple
        '#F59E0B', // yellow
        '#EF4444', // red
        '#6B7280', // gray
        '#EC4899', // pink
        '#14B8A6', // teal
      ],
    },
  ],
}

// Watch for form changes and apply filters
watch(form, applyFilters, { deep: true })
</script>