<template>
  <div class="w-full max-w-md mx-auto">
    <canvas ref="chartRef"></canvas>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import {
  Chart,
  PieController,
  ArcElement,
  Tooltip,
  Legend,
  ChartData,
  ChartOptions
} from 'chart.js'

// ✅ Register PieController and required elements/plugins
Chart.register(PieController, ArcElement, Tooltip, Legend)

interface Props {
  chartData: ChartData<'pie'>
  chartOptions?: ChartOptions<'pie'>
}

const props = defineProps<Props>()
const chartRef = ref<HTMLCanvasElement | null>(null)
let chartInstance: Chart<'pie'> | null = null

const renderChart = () => {
  if (!chartRef.value) return

  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(chartRef.value, {
    type: 'pie',
    data: props.chartData,
    options: props.chartOptions || {
      responsive: true,
      plugins: { legend: { position: 'right' } },
    },
  })
}

// Watch for data changes and re-render
watch(
  () => props.chartData,
  () => {
    renderChart()
  },
  { deep: true }
)

onMounted(() => {
  renderChart()
})

onBeforeUnmount(() => {
  chartInstance?.destroy()
})
</script>
