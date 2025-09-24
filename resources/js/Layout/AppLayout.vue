<template>
  <div class="min-h-screen bg-app-bg text-app-text font-sans">
    <!-- Header / Navbar -->
    <header class="bg-app-primary text-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Expense Tracker</h1>
        <nav>
          <slot name="nav"></slot>
        </nav>
      </div>
    </header>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-app-primary text-white mt-auto py-4 text-center text-sm">
      &copy; 2025 Expense Tracker
    </footer>
  </div>
</template>

<script setup lang="ts">
// Add any layout logic here if needed
</script>

<style>
/* Tailwind v4 semantic tokens */
:root {
  --color-app-bg: #f9fafb; /* light gray background */
  --color-app-text: #111827; /* dark text */
  --color-app-primary: #6366f1; /* indigo 500 */
}

/* Utilities using semantic tokens */
.bg-app-bg { background-color: var(--color-app-bg); }
.text-app-text { color: var(--color-app-text); }
.bg-app-primary { background-color: var(--color-app-primary); }
</style>
