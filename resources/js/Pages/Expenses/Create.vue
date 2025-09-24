<template>
  <AppLayout>
    <Head title="Add New Expense" />

    <div class="max-w-2xl mx-auto">
      <div class="card">
        <div class="card-header">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Add New Expense</h1>
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
              <p class="mt-1 text-xs text-gray-500">
                Categories are automatically assigned based on keywords in the description.
              </p>
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

            <!-- Predicted Category -->
            <div v-if="predictedCategory" class="bg-indigo-50 border border-indigo-200 rounded-md p-4">
              <h4 class="text-sm font-medium text-indigo-900 mb-2">Predicted Category</h4>
              <span
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                :class="getCategoryColor(predictedCategory)"
              >
                {{ predictedCategory }}
              </span>
              <p class="mt-1 text-xs text-indigo-700">
                This category will be automatically assigned based on your description.
              </p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('expenses.index')" class="btn btn-secondary">
                Cancel
              </Link>
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <span v-if="form.processing">Adding...</span>
                <span v-else>Add Expense</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Category Rules -->
      <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <InformationCircleIcon class="h-5 w-5 text-blue-600" />
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-900">
              Auto-categorization Rules
            </h3>
            <div class="mt-2 text-sm text-blue-800">
              <ul class="list-disc pl-5 space-y-1">
                <li><strong>Transport:</strong> Grab, Gojek, Uber, Taxi, Fuel</li>
                <li><strong>Food & Drink:</strong> Starbucks, McDonald's, KFC, Restaurant, Coffee</li>
                <li><strong>Shopping:</strong> Shopee, Tokopedia, Mall, Store</li>
                <li><strong>Entertainment:</strong> Netflix, Spotify, Cinema, Movie</li>
                <li><strong>Health:</strong> Hospital, Doctor, Pharmacy, Medicine</li>
                <li><strong>Others:</strong> Everything else</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layout/AppLayout.vue'
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import { useRoute } from '@/composables/useRoute'

const { route } = useRoute()

const form = useForm({
  amount: '',
  description: '',
  date: new Date().toISOString().split('T')[0],
})

const today = new Date().toISOString().split('T')[0]

const predictedCategory = computed(() => {
  if (!form.description) return null
  const description = form.description.toLowerCase()

  if (description.includes('grab') || description.includes('gojek') || 
      description.includes('uber') || description.includes('taxi') || 
      description.includes('fuel')) {
    return 'Transport'
  }
  if (description.includes('starbucks') || description.includes('mcdonald') || 
      description.includes('kfc') || description.includes('restaurant') || 
      description.includes('coffee')) {
    return 'Food & Drink'
  }
  if (description.includes('shopee') || description.includes('tokopedia') || 
      description.includes('mall') || description.includes('store')) {
    return 'Shopping'
  }
  if (description.includes('netflix') || description.includes('spotify') || 
      description.includes('cinema') || description.includes('movie')) {
    return 'Entertainment'
  }
  if (description.includes('hospital') || description.includes('doctor') || 
      description.includes('pharmacy') || description.includes('medicine')) {
    return 'Health'
  }
  return 'Others'
})

const getCategoryColor = (category: string): string => {
  const colors: Record<string, string> = {
    Transport: 'bg-blue-100 text-blue-800',
    'Food & Drink': 'bg-green-100 text-green-800',
    Shopping: 'bg-purple-100 text-purple-800',
    Entertainment: 'bg-yellow-100 text-yellow-800',
    Health: 'bg-red-100 text-red-800',
    Others: 'bg-gray-100 text-gray-800',
  }
  return colors[category] || 'bg-gray-100 text-gray-800'
}

const submit = () => {
  form.post(route('expenses.store'))
}
</script>