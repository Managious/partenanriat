<template>
  <div class="modal-backdrop">
    <div class="modal-content">
      <h5 class="modal-title">Delete Fee</h5>
      <p>Are you sure you want to delete <strong>{{ feeData.name }}</strong>?</p>
      <div class="d-flex justify-content-end">
        <button class="btn btn-danger me-2" @click="$emit('confirm')">Delete</button>
        <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    feeData: Object
  }
};
</script>

<style scoped>
.modal-backdrop {
  position: fixed; top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
}
.modal-content {
  background: white; padding: 20px;
  border-radius: 8px; width: 400px;
}
</style>
