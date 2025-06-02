<template>
  <div class="card shadow-sm text-center h-100 border-0" style="background: #fff;">
    <div class="card-body d-flex flex-column justify-content-center align-items-center">
      <div class="mb-2 rounded-circle d-flex align-items-center justify-content-center" :style="{ background: bgColor, width: '48px', height: '48px' }">
        <i :class="icon + ' fa-lg'" :style="{ color: iconColor }"></i>
      </div>
      <h6 class="card-title mt-2 text-secondary">{{ title }}</h6>
      <h3 class="fw-bold mb-0" :style="{ color: valueColor }">{{ value }}</h3>
      <small v-if="subtitle" class="text-muted mt-1">{{ subtitle }}</small>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  iconColor: String,
  bgColor: String,
  valueColor: String,
  subtitle: String
})
</script>
