<script setup>
const props = defineProps(['visible', 'form', 'isEditing'])
const emit = defineEmits(['close', 'submit'])

const close = () => {
  emit('close')
}
const onSubmit = () => {
  emit('submit')
}
</script>

<template>
  <div v-if="visible" class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <form class="modal-content" @submit.prevent="onSubmit">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEditing ? 'Edit Client' : 'Add New Client' }}</h5>
          <button type="button" class="close" @click="close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input v-model="form.client_name" required class="form-control mb-2" placeholder="Name" />
          <input v-model="form.client_email" class="form-control mb-2" placeholder="Email" />
          <input v-model="form.client_city" class="form-control mb-2" placeholder="City" />
          <input v-model="form.client_zone" class="form-control mb-2" placeholder="Zone" />
          <input v-model="form.client_type" class="form-control mb-2" placeholder="Type" />
          <input v-model="form.client_address" class="form-control mb-2" placeholder="Address" />
          <input v-model="form.client_phone" class="form-control mb-2" placeholder="Phone" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
          <button type="submit" class="btn btn-primary">
            {{ isEditing ? 'Update' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-dialog {
    background-color: #f1f5f9;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.modal-header {
    padding: 1rem 1.5rem;
    background-color: #334155;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #f1f5f9;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #f1f5f9;
}

.modal-body {
    padding: 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #4ade80;
    border: none;
    color: #f1f5f9;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #22c55e;
}

.btn-secondary {
    background-color: #64748b;
    border: none;
    color: #f1f5f9;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

</style>



