<template>
  <div class="card shadow-sm h-100 p-3">
    <h6 class="mb-3 text-center">{{ title }}</h6>
    <Doughnut :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import { Chart, ArcElement, Tooltip, Legend } from 'chart.js'

Chart.register(ArcElement, Tooltip, Legend)

const props = defineProps({
  title: String,
  labels: Array,      // ['Label 1', 'Label 2', ...]
  values: Array,      // [value1, value2, ...]
  colors: Array       // ['#color1', '#color2', ...]
})

const chartData = {
  labels: props.labels,
  datasets: [
    {
      data: props.values,
      backgroundColor: props.colors,
      borderWidth: 0
    }
  ]
}

const chartOptions = {
  cutout: '70%',   // thickness of donut
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}
</script>
