<template>
  <div class="modal-overlay">
    <div class="modal-container">
      <div class="modal-header">
        <h5>{{ isEditMode ? 'Edit Client' : 'Create Client' }}</h5>
        <button class="close-btn" @click="$emit('close')">&times;</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitForm">
        <div class="mb-3">
          <label>Client Name</label>
          <input type="text" v-model="form.client_name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label>City</label>
          <input type="text" v-model="form.client_city" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Zone</label>
          <input type="text" v-model="form.client_zone" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Type</label>
          <input type="text" v-model="form.client_type" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Address</label>
          <input type="text" v-model="form.client_address" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" v-model="form.client_email" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Phone</label>
          <input type="text" v-model="form.client_phone" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Discount</label>
          <input type="number" v-model="form.client_discount" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Partenaire</label>
          <select v-model="form.partenaire_id" class="form-control" required>
            <option value="">Select a partenaire</option>
            <option v-for="partenaire in partenaires" :key="partenaire.id" :value="partenaire.id">
              {{ partenaire.name }}
            </option>
          </select>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" @click="$emit('close')">Cancel</button>
          <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
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
        client_zone: '',
        client_type: '',
        client_address: '',
        client_email: '',
        client_phone: '',
        client_discount: 0,
        partenaire_id: '',
      },
      partenaires: [],
    };
  },
  mounted() {
    this.fetchPartenaires();
    if (this.isEditMode && this.clientData) {
      this.form = {
        ...this.clientData
      };
    }
  },
  methods: {
    async fetchPartenaires() {
      try {
        const response = await axios.get('/api/users/partenaires');
        this.partenaires = response.data;
      } catch (error) {
        console.error('Error fetching partenaires:', error);
      }
    },
    async submitForm() {
      try {
        if (this.isEditMode) {
          await axios.put(`/api/clients/${this.clientData.id}`, this.form);
        } else {
          await axios.post('/api/clients', this.form);
        }
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