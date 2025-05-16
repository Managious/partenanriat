<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <form class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title">{{ isEditMode ? 'Edit Client' : 'Create Client' }}</h5>
          <button type="button" class="close" @click="close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" v-model="formData.name" class="form-control" required />
            <div v-if="errors.name" class="text-danger">
              <small>{{ errors.name[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" v-model="formData.city" class="form-control" required />
            <div v-if="errors.city" class="text-danger">
              <small>{{ errors.city[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="zone">Zone</label>
            <input type="text" id="zone" v-model="formData.zone" class="form-control" required />
            <div v-if="errors.zone" class="text-danger">
              <small>{{ errors.zone[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="type">Type</label>
            <input type="text" id="type" v-model="formData.type" class="form-control" required />
            <div v-if="errors.type" class="text-danger">
              <small>{{ errors.type[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" v-model="formData.address" class="form-control" required />
            <div v-if="errors.address" class="text-danger">
              <small>{{ errors.address[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" v-model="formData.email" class="form-control" required />
            <div v-if="errors.email" class="text-danger">
              <small>{{ errors.email[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" v-model="formData.phone" class="form-control" required />
            <div v-if="errors.phone" class="text-danger">
              <small>{{ errors.phone[0] }}</small>
            </div>
          </div>
          <div class="form-group">
            <label for="discount">Discount</label>
            <input type="number" id="discount" v-model="formData.discount" class="form-control" required />
            <div v-if="errors.discount" class="text-danger">
              <small>{{ errors.discount[0] }}</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="isSubmitting" @click="submitForm" >
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ isEditMode ? 'Update' : 'Create' }}
          </button>
          <button type="button" class="btn btn-secondary" @click="closeModal" :disabled="isSubmitting">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import api from '@/axios';
export default {
    props: {
        isEditMode: Boolean,
        clientData: Object,
    },

    data() {
        return {
            formData: {
                name: '',
                city: '',
                zone: '',
                type: '',
                address: '',
                email: '',
                phone: '',
                discount: 0
            },
            errors: {},
            isSubmitting: false,
        };
    },
    watch: {
        clientData: {
            immediate: true,
            handler(newValue) {
                if (this.isEditMode && newValue) {
                    this.formData = { ...newValue };
                }
            },
        },
    },
    methods: {
        validateForm() {
          let isValid = true;
          this.errors = {};
          
          // Required fields check
          const requiredFields = ['name', 'city', 'zone', 'type', 'address', 'email', 'phone'];
          requiredFields.forEach(field => {
            if (!this.formData[field]?.trim()) {
              this.errors[field] = ['This field is required'];
              isValid = false;
            }
          });

          // Email format validation
          if (this.formData.email && !/^\S+@\S+\.\S+$/.test(this.formData.email)) {
            this.errors.email = ['Please enter a valid email address'];
            isValid = false;
          }

          return isValid;
        },
        async submitForm() {
          if (!this.validateForm()) return;

          this.isSubmitting = true;
          this.errors = {};

          try {
            // Correct endpoint - only one /api prefix
            const endpoint = this.isEditMode 
              ? `/clients/${this.clientData.id}`
              : '/clients';

            const method = this.isEditMode ? 'put' : 'post';
            
            const response = await api({
              method,
              url: endpoint,
              data: this.formData,
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
              }
            });
            
            this.$emit('refresh');
            this.closeModal();
          } catch (error) {
            if (error.response?.status === 422) {
              this.errors = error.response.data.errors || {};
            } else {
              console.error('API error:', error);
              alert(error.response?.data?.message || 'An error occurred');
            }
          } finally {
            this.isSubmitting = false;
          }
        },
        closeModal() {
            this.$emit('close');
        },
        handleOutsideClick(event) {
            if (event.target === this.$el) {
                this.closeModal();
            }
        },
    },
}
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



