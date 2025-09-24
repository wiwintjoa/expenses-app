<template>
  <AppLayout>
    <Head title="Expense Tracker" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center space-x-2">
            <!-- Smaller, proportional icon -->
            <CurrencyDollarIcon class="h-6 w-6 text-green-600" />
            <h1 class="text-3xl font-bold text-zinc-900">Expense Tracker</h1>
          </div>
          <Link
            :href="route('expenses.create')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition-colors"
          >
            Add Expense
          </Link>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5 flex items-center">
              <CurrencyDollarIcon class="h-8 w-8 text-green-600" />
              <div class="ml-5">
                <p class="text-sm font-medium text-zinc-500">Total Expenses</p>
                <p class="text-2xl font-semibold text-zinc-900">${{ formatCurrency(currentTotal) }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5 flex items-center">
              <DocumentTextIcon class="h-8 w-8 text-blue-600" />
              <div class="ml-5">
                <p class="text-sm font-medium text-zinc-500">Total Records</p>
                <p class="text-2xl font-semibold text-zinc-900">{{ expenses.data.length }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-5 flex items-center">
              <TagIcon class="h-8 w-8 text-purple-600" />
              <div class="ml-5">
                <p class="text-sm font-medium text-zinc-500">Categories</p>
                <p class="text-2xl font-semibold text-zinc-900">{{ categories.length }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Category Chart -->
        <div v-if="categoryStats.length" class="bg-white shadow rounded-lg mb-6 p-6">
          <h3 class="text-lg font-medium text-zinc-900 mb-4">Expenses by Category</h3>
          <PieChart :chartData="pieChartData" />
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
          <h3 class="text-lg font-medium text-zinc-900 mb-4">Filters</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-zinc-700 mb-1">Category</label>
              <select
                v-model="form.category"
                class="block w-full border-zinc-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="all">All Categories</option>
                <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-zinc-700 mb-1">Date</label>
              <input
                type="date"
                v-model="form.date"
                class="block w-full border-zinc-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-zinc-700 mb-1">Start Date</label>
              <input
                type="date"
                v-model="form.start_date"
                class="block w-full border-zinc-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-zinc-700 mb-1">End Date</label>
              <input
                type="date"
                v-model="form.end_date"
                class="block w-full border-zinc-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
          </div>
          <div class="mt-4">
            <button @click="clearFilters" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Clear Filters</button>
          </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-medium text-zinc-900 mb-4">Recent Expenses</h3>

          <div v-if="expenses.data.length === 0" class="text-center py-8">
            <DocumentTextIcon class="h-12 w-12 text-zinc-400 mx-auto mb-4" />
            <p class="text-zinc-500">No expenses found. Add your first expense to get started!</p>
          </div>

          <div v-else class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-zinc-300">
              <thead class="bg-zinc-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Category</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Amount</th>
                  <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-zinc-200">
                <tr v-for="expense in expenses.data" :key="expense.id" class="hover:bg-zinc-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900">{{ formatDate(expense.date) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900">{{ expense.description }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="getCategoryColor(expense.category)">
                      {{ expense.category }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900">${{ formatCurrency(expense.amount) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <Link :href="route('expenses.edit', { id: expense.id })" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                    <button @click="deleteExpense(expense.id)" class="text-red-600 hover:text-red-900">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="expenses.last_page > 1" class="mt-6 flex justify-between">
            <Link v-if="expenses.prev_page_url" :href="expenses.prev_page_url" class="px-4 py-2 border rounded-md text-zinc-700 hover:bg-zinc-50">Previous</Link>
            <Link v-if="expenses.next_page_url" :href="expenses.next_page_url" class="px-4 py-2 border rounded-md text-zinc-700 hover:bg-zinc-50">Next</Link>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>


<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, reactive, watch, onMounted } from 'vue'
import AppLayout from '@/Layout/AppLayout.vue'
import { useRoute } from '@/composables/useRoute'
import { Chart, ArcElement, Tooltip, Legend } from 'chart.js'
import { CurrencyDollarIcon, DocumentTextIcon, TagIcon } from '@heroicons/vue/24/outline'
import PieChart from './PieChart.vue' 

Chart.register(ArcElement, Tooltip, Legend)

interface Expense { id: number; amount: string; description: string; category: string; date: string }
interface CategoryStat { category: string; total: string }
interface Props {
  expenses: { data: Expense[]; last_page: number; prev_page_url?: string; next_page_url?: string }
  categories: string[]
  categoryStats: CategoryStat[]
  filters: Record<string, string>
  totalExpenses: number
  currentTotal: string
}

const { route } = useRoute()
const props = defineProps<Props>()

const form = reactive({
  category: props.filters.category || 'all',
  date: props.filters.date || '',
  start_date: props.filters.start_date || '',
  end_date: props.filters.end_date || '',
})

const chartCanvas = ref<HTMLCanvasElement | null>(null)

const formatCurrency = (amount: string | number) => parseFloat(amount.toString()).toFixed(2)
const formatDate = (date: string) => new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })

const getCategoryColor = (category: string) => {
  const colors: Record<string, string> = {
    'Transport': 'bg-blue-100 text-blue-800',
    'Food & Drink': 'bg-green-100 text-green-800',
    'Shopping': 'bg-purple-100 text-purple-800',
    'Entertainment': 'bg-yellow-100 text-yellow-800',
    'Health': 'bg-red-100 text-red-800',
    'Others': 'bg-zinc-100 text-zinc-800',
  }
  return colors[category] || 'bg-zinc-100 text-zinc-800'
}

const getChartColor = (index: number) => ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#6B7280','#EC4899','#14B8A6'][index % 8]

const applyFilters = () => {
  const params: Record<string, string> = {}
  if (form.category && form.category !== 'all') params.category = form.category
  if (form.date) params.date = form.date
  if (form.start_date) params.start_date = form.start_date
  if (form.end_date) params.end_date = form.end_date
  router.get(route('expenses.index'), params, { preserveState: true, replace: true })
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
      props.expenses.data = props.expenses.data.filter(exp => exp.id !== id)
    },
  })
}

// Prepare data for PieChart.vue
const pieChartData = {
  labels: props.categoryStats.map(s => s.category),
  datasets: [
    {
      label: 'Expenses',
      data: props.categoryStats.map(s => parseFloat(s.total)),
      backgroundColor: ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#6B7280','#EC4899','#14B8A6'],
    },
  ],
}

watch(form, applyFilters, { deep: true })

onMounted(() => {
  if (!chartCanvas.value || props.categoryStats.length === 0) return
  try {
    new Chart(chartCanvas.value, {
      type: 'pie',
      data: {
        labels: props.categoryStats.map(s => s.category),
        datasets: [{
          data: props.categoryStats.map(s => parseFloat(s.total)),
          backgroundColor: props.categoryStats.map((_, i) => getChartColor(i)),
        }],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'right' } },
      },
    })
  } catch (error) {
    console.error('Chart.js initialization error:', error)
  }
})
</script>
