<template>
  <AppLayout>
    <Head title="Edit Expense" />

    <div class="max-w-2xl mx-auto">
      <div class="card">
        <div class="card-header">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Edit Expense</h1>
            <Link
              :href="route('expenses.index')"
              class="text-gray-600 hover:text-gray-900 text-sm font-medium"
            >
              ← Back to Expenses
            </Link>
          </div>
        </div>

        <div class="card-body">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Amount -->
            <div>
              <label for="amount" class="block text-sm font-medium text-gray-700">
                Amount *
              </label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input
                  id="amount"
                  type="number"
                  step="0.01"
                  min="0"
                  v-model="form.amount"
                  class="form-input pl-7"
                  placeholder="0.00"
                  required
                />
              </div>
              <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                {{ form.errors.amount }}
              </div>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">
                Description *
              </label>
              <div class="mt-1">
                <input
                  id="description"
                  type="text"
                  v-model="form.description"
                  class="form-input"
                  placeholder="e.g., Grab ride to office, Starbucks coffee"
                  required
                />
              </div>
              <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
              </div>
            </div>

            <!-- Category -->
            <div>
              <label for="category" class="block text-sm font-medium text-gray-700">
                Category *
              </label>
              <div class="mt-1">
                <select id="category" v-model="form.category" class="form-select" required>
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
                  class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                >
                  Auto-categorize
                </button>
                <span class="text-xs text-gray-400">|</span>
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

            <!-- Date -->
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700">
                Date *
              </label>
              <div class="mt-1">
                <input
                  id="date"
                  type="date"
                  v-model="form.date"
                  :max="today"
                  class="form-input"
                  required
                />
              </div>
              <div v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                {{ form.errors.date }}
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('expenses.index')" class="btn btn-secondary">
                Cancel
              </Link>
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <span v-if="form.processing">Updating...</span>
                <span v-else>Update Expense</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Expense History -->
      <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Expense Details</h3>
        <div class="text-xs text-gray-600 space-y-1">
          <div>Created: {{ formatDateTime(expense.created_at) }}</div>
          <div v-if="expense.updated_at !== expense.created_at">
            Last Updated: {{ formatDateTime(expense.updated_at) }}
          </div>
          <div>ID: {{ expense.id }}</div>
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

const autoCategory = () => {
  const description = form.description.toLowerCase()
  
  if (description.includes('grab') || description.includes('gojek') || 
      description.includes('uber') || description.includes('taxi') ||
      description.includes('fuel') || description.includes('gas station')) {
    form.category = 'Transport'
    return
  }

  if (description.includes('starbucks') || description.includes('mcdonald') || 
      description.includes('kfc') || description.includes('restaurant') ||
      description.includes('coffee') || description.includes('food') ||
      description.includes('lunch') || description.includes('dinner') ||
      description.includes('breakfast')) {
    form.category = 'Food & Drink'
    return
  }

  if (description.includes('shopee') || description.includes('tokopedia') || 
      description.includes('mall') || description.includes('store') ||
      description.includes('shopping')) {
    form.category = 'Shopping'
    return
  }

  if (description.includes('netflix') || description.includes('spotify') || 
      description.includes('cinema') || description.includes('movie') ||
      description.includes('game')) {
    form.category = 'Entertainment'
    return
  }

  if (description.includes('hospital') || description.includes('doctor') || 
      description.includes('pharmacy') || description.includes('medicine')) {
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
  form.put(route('expenses.update', { id: props.expense.id }))
}
</script>