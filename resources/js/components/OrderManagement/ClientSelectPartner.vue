<template>
  <div class="mb-3">
    <label for="client-id" class="form-label">Client ID</label>
    <input
      id="client-id"
      type="number"
      class="form-control"
      v-model.number="clientId"
      placeholder="Enter client's ID"
      @change="onChange"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['clientSelected'])
const clientId = ref(null)

function onChange() {
  if (clientId.value && Number.isInteger(clientId.value) && clientId.value > 0) {
    emit('clientSelected', clientId.value)
  }
}
</script>
