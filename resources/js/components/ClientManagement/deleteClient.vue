<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Client</h5>
          <button type="button" class="close" @click="$emit('close')">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <p>Are you sure you want to delete this client <strong>{{ clientData.client_name }}</strong>?</p>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
          <button class="btn btn-danger" @click="$emit('confirm')">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    clientData: {
      type: Object,
      required: true
    }
  },
  methods: {
    handleOutsideClick(event) {
      if (event.target === this.$el) {
        this.$emit('close');
      }
    }
  }
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

.modal-dialog {
  background-color: #f8f9fa;
  border-radius: 8px;
  width: 400px;
  max-width: 90%;
  box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.modal-header {
  background-color: #334155;
  color: white;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
}

.modal-body {
  padding: 1.25rem;
}

.modal-footer {
  padding: 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  background-color: #f1f1f1;
}

.btn {
  padding: 0.5rem 1.25rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
}

.btn-secondary {
  background-color: #64748b;
  border: none;
  color: white;
}

.btn-secondary:hover {
  background-color: #4b5563;
}

.btn-danger {
  background-color: #e11d48;
  border: none;
  color: white;
}

.btn-danger:hover {
  background-color: #be123c;
}

.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: white;
  cursor: pointer;
}
</style>
