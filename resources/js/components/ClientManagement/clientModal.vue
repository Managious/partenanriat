<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5>{{ isEditMode ? 'Edit Client' : 'Add Client' }}</h5>
          <button class="close" @click="$emit('close')">&times;</button>
        </div>
        <form @submit.prevent="submitClient">
          <div class="modal-body">
            <div class="mb-3">
              <label>Name</label>
              <input type="text" v-model="form.client_name" class="form-control" required />
              <div v-if="errors.client_name" class="text-danger"><small>{{ errors.client_name[0] }}</small></div>
            </div>

            <div class="mb-3">
              <label>City</label>
              <input type="text" v-model="form.client_city" class="form-control" />
            </div>

            <div class="mb-3">
              <label>Zone</label>
              <input type="text" v-model="form.client_zone" class="form-control" />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
            <button class="btn btn-secondary" type="button" @click="$emit('close')">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isEditMode: {
      type: Boolean,
      default: false
    },
    clientData: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      form: {
        client_name: '',
        client_city: '',
        client_zone: ''
      },
      errors: {}
    };
  },
  watch: {
    clientData: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = { ...newVal };
        }
      }
    }
  },
  methods: {
    async submitClient() {
      const url = this.isEditMode
        ? `/api/clients/${this.clientData.client_id}`
        : '/api/clients';
      const method = this.isEditMode ? 'put' : 'post';
      try {
        await axios[method](url, this.form);
        this.$emit('refresh');
        this.$emit('close');
      } catch (error) {
        console.error('Submit error:', error);
      }
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  animation: modal-appear 0.3s ease-out;
}

.modal-header {
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h5 {
  margin: 0;
  font-size: 1.25rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6c757d;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  padding: 15px 20px;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

@keyframes modal-appear {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive adjustments */
@media (max-width: 576px) {
  .modal-container {
    width: 95%;
  }
}
</style>