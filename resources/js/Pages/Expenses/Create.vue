<template>
  <AppLayout>
    <Head title="Add New Expense" />

    <div class="py-6">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-bold text-foreground">Add New Expense</h1>
              <Link
                :href="route('expenses.index')"
                class="text-muted hover:text-foreground"
              >
                ← Back to Expenses
              </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Amount -->
              <div>
                <label for="amount" class="block text-sm font-medium text-foreground">
                  Amount *
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-muted sm:text-sm">$</span>
                  </div>
                  <input
                    id="amount"
                    type="number"
                    step="0.01"
                    min="0"
                    v-model="form.amount"
                    class="block w-full pl-7 pr-12 border rounded-md focus:ring focus:ring-primary focus:border-primary"
                    placeholder="0.00"
                    required
                  />
                </div>
                <div v-if="form.errors.amount" class="mt-1 text-sm text-error">
                  {{ form.errors.amount }}
                </div>
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-foreground">
                  Description *
                </label>
                <div class="mt-1">
                  <input
                    id="description"
                    type="text"
                    v-model="form.description"
                    class="block w-full border rounded-md shadow-sm focus:ring focus:ring-primary focus:border-primary"
                    placeholder="e.g., Grab ride to office, Starbucks coffee"
                    required
                  />
                </div>
                <div v-if="form.errors.description" class="mt-1 text-sm text-error">
                  {{ form.errors.description }}
                </div>
                <p class="mt-1 text-xs text-muted">
                  Categories are automatically assigned based on keywords in the description.
                </p>
              </div>

              <!-- Date -->
              <div>
                <label for="date" class="block text-sm font-medium text-foreground">
                  Date *
                </label>
                <div class="mt-1">
                  <input
                    id="date"
                    type="date"
                    v-model="form.date"
                    :max="today"
                    class="block w-full border rounded-md shadow-sm focus:ring focus:ring-primary focus:border-primary"
                    required
                  />
                </div>
                <div v-if="form.errors.date" class="mt-1 text-sm text-error">
                  {{ form.errors.date }}
                </div>
              </div>

              <!-- Predicted Category -->
              <div v-if="predictedCategory" class="bg-muted/20 rounded-md p-4">
                <h4 class="text-sm font-medium text-foreground mb-2">Predicted Category</h4>
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getCategoryColor(predictedCategory)"
                >
                  {{ predictedCategory }}
                </span>
                <p class="mt-1 text-xs text-muted">
                  This category will be automatically assigned based on your description.
                </p>
              </div>

              <!-- Actions -->
              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('expenses.index')"
                  class="bg-surface py-2 px-4 border rounded-md shadow-sm text-sm font-medium text-muted hover:bg-muted/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-primary py-2 px-4 rounded-md shadow-sm text-sm font-medium text-primary-foreground hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50"
                >
                  <span v-if="form.processing">Adding...</span>
                  <span v-else>Add Expense</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Category Rules -->
        <div class="mt-6 bg-accent/20 border rounded-md p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <InformationCircleIcon class="h-5 w-5 text-accent" />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-accent">
                Auto-categorization Rules
              </h3>
              <div class="mt-2 text-sm text-foreground">
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

  if (description.includes('grab') || description.includes('gojek') || description.includes('uber') || description.includes('taxi') || description.includes('fuel')) {
    return 'Transport'
  }
  if (description.includes('starbucks') || description.includes('mcdonald') || description.includes('kfc') || description.includes('restaurant') || description.includes('coffee')) {
    return 'Food & Drink'
  }
  if (description.includes('shopee') || description.includes('tokopedia') || description.includes('mall') || description.includes('store')) {
    return 'Shopping'
  }
  if (description.includes('netflix') || description.includes('spotify') || description.includes('cinema') || description.includes('movie')) {
    return 'Entertainment'
  }
  if (description.includes('hospital') || description.includes('doctor') || description.includes('pharmacy') || description.includes('medicine')) {
    return 'Health'
  }
  return 'Others'
})

const getCategoryColor = (category: string): string => {
  const colors: Record<string, string> = {
    Transport: 'bg-blue-200 text-blue-900',
    'Food & Drink': 'bg-green-200 text-green-900',
    Shopping: 'bg-purple-200 text-purple-900',
    Entertainment: 'bg-yellow-200 text-yellow-900',
    Health: 'bg-red-200 text-red-900',
    Others: 'bg-muted text-foreground',
  }
  return colors[category] || 'bg-muted text-foreground'
}

const submit = () => {
  form.post(route('expenses.store'))
}
</script>
