<template>
  <AppLayout>
    <Head title="Edit Expense" />

    <div class="py-6">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-bold text-gray-900">Edit Expense</h1>
              <Link
                :href="route('expenses.index')"
                class="text-gray-600 hover:text-gray-800"
              >
                ← Back to Expenses
              </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="amount" class="block text-sm font-medium text-gray-800">
                  Amount *
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-600 sm:text-sm">$</span>
                  </div>
                  <input
                    id="amount"
                    type="number"
                    step="0.01"
                    min="0"
                    v-model="form.amount"
                    class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="0.00"
                    required
                  />
                </div>
                <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                  {{ form.errors.amount }}
                </div>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-800">
                  Description *
                </label>
                <div class="mt-1">
                  <input
                    id="description"
                    type="text"
                    v-model="form.description"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Grab ride to office, Starbucks coffee"
                    required
                  />
                </div>
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                  {{ form.errors.description }}
                </div>
              </div>

              <div>
                <label for="category" class="block text-sm font-medium text-gray-800">
                  Category *
                </label>
                <div class="mt-1">
                  <select
                    id="category"
                    v-model="form.category"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                  >
                    <option value="Transport">Transport</option>
                    <option value="Food & Drink">Food & Drink</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Health">Health</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div v-if="form.errors.category" class="mt-1 text-sm text-red-600">
                  {{ form.errors.category }}
                </div>
                <div class="mt-1 flex items-center space-x-2">
                  <button
                    type="button"
                    @click="autoCategory"
                    class="text-xs text-indigo-600 hover:text-indigo-800"
                  >
                    Auto-categorize
                  </button>
                  <span class="text-xs text-gray-500">|</span>
                  <span class="text-xs text-gray-600">
                    Current: 
                    <span
                      class="inline-flex px-1 py-0.5 text-xs font-semibold rounded"
                      :class="getCategoryColor(form.category)"
                    >
                      {{ form.category }}
                    </span>
                  </span>
                </div>
              </div>

              <div>
                <label for="date" class="block text-sm font-medium text-gray-800">
                  Date *
                </label>
                <div class="mt-1">
                  <input
                    id="date"
                    type="date"
                    v-model="form.date"
                    :max="today"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                  />
                </div>
                <div v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                  {{ form.errors.date }}
                </div>
              </div>

              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('expenses.index')"
                  class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                >
                  <span v-if="form.processing">Updating...</span>
                  <span v-else>Update Expense</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Expense History -->
        <div class="mt-6 bg-gray-100 border border-gray-300 rounded-md p-4">
          <h3 class="text-sm font-medium text-gray-900 mb-2">Expense Details</h3>
          <div class="text-xs text-gray-700 space-y-1">
            <div>Created: {{ formatDateTime(expense.created_at) }}</div>
            <div v-if="expense.updated_at !== expense.created_at">
              Last Updated: {{ formatDateTime(expense.updated_at) }}
            </div>
            <div>ID: {{ expense.id }}</div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layout/AppLayout.vue' 
import { useRoute } from '@/composables/useRoute'

const { route } = useRoute()


interface Expense {
  id: number
  amount: string
  description: string
  category: string
  date: string
  created_at: string
  updated_at: string
}

interface Props {
  expense: Expense
}

const props = defineProps<Props>()

const form = useForm({
  amount: props.expense.amount,
  description: props.expense.description,
  category: props.expense.category,
  date: props.expense.date,
})

const today = new Date().toISOString().split('T')[0]

const getCategoryColor = (category: string): string => {
  const colors: { [key: string]: string } = {
    'Transport': 'bg-blue-200 text-blue-900',
    'Food & Drink': 'bg-green-200 text-green-900',
    'Shopping': 'bg-purple-200 text-purple-900',
    'Entertainment': 'bg-yellow-200 text-yellow-900',
    'Health': 'bg-red-200 text-red-900',
    'Others': 'bg-gray-200 text-gray-900',
  }
  return colors[category] || 'bg-gray-200 text-gray-900'
}

const autoCategory = () => {
  const description = form.description.toLowerCase()
  
  // Transport categories
  if (description.includes('grab') || 
      description.includes('gojek') || 
      description.includes('uber') || 
      description.includes('taxi') ||
      description.includes('fuel') ||
      description.includes('gas station')) {
    form.category = 'Transport'
    return
  }

  // Food & Drink categories
  if (description.includes('starbucks') || 
      description.includes('mcdonald') || 
      description.includes('kfc') || 
      description.includes('restaurant') ||
      description.includes('coffee') ||
      description.includes('food') ||
      description.includes('lunch') ||
      description.includes('dinner') ||
      description.includes('breakfast')) {
    form.category = 'Food & Drink'
    return
  }

  // Shopping categories
  if (description.includes('shopee') || 
      description.includes('tokopedia') || 
      description.includes('mall') || 
      description.includes('store') ||
      description.includes('shopping')) {
    form.category = 'Shopping'
    return
  }

  // Entertainment categories
  if (description.includes('netflix') || 
      description.includes('spotify') || 
      description.includes('cinema') || 
      description.includes('movie') ||
      description.includes('game')) {
    form.category = 'Entertainment'
    return
  }

  // Health categories
  if (description.includes('hospital') || 
      description.includes('doctor') || 
      description.includes('pharmacy') || 
      description.includes('medicine')) {
    form.category = 'Health'
    return
  }

  form.category = 'Others'
}

const formatDateTime = (dateTime: string): string => {
  return new Date(dateTime).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const submit = () => {
  form.put(route('expenses.update', { id: props.expense.id }), {
    onSuccess: () => {
      // Redirect handled by the controller
    },
  })
}
</script>