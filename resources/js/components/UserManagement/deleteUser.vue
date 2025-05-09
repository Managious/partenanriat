<template>
  <div class="modal-backdrop">
    <div class="modal-content">
      <h5 class="modal-title">Confirm Deletion</h5>
      <p>Are you sure you want to delete user <strong>{{ userData.name }}</strong>?</p>
      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-secondary me-2" @click="$emit('close')">Cancel</button>
        <button type="button" class="btn btn-danger" @click="$emit('confirm')">Delete</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userData: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  padding: 1.5rem;
  border-radius: 8px;
  width: 400px;
  max-width: 90%;
}
</style>
