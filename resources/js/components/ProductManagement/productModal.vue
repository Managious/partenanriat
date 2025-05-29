<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEditMode ? 'Edit Product' : 'Create Product' }}</h5>
          <button type="button" class="close" @click="closeModal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="form-group" v-for="(label, key) in productFields" :key="key">
              <label :for="key">{{ label }}</label>
              <input :id="key" v-model="formData[key]" class="form-control" :type="key.includes('price') || key.includes('stock') || key.includes('cmup') ? 'number' : 'text'" :required="true" />
              <div v-if="errors[key]" class="text-danger">
                <small>{{ errors[key][0] }}</small>
              </div>
            </div>

            <div class="form-group">
              <label for="product_supplier">Supplier</label>
              <select id="product_supplier" v-model="formData.product_supplier" class="form-control" required>
                <option disabled value="">Please select</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                  {{ supplier.supplier_name }}
                </option>
              </select>
              <div v-if="errors.product_supplier" class="text-danger">
                <small>{{ errors.product_supplier[0] }}</small>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
              <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
            </div>
          </form>

          <div class="fees-section mt-4">
            <h6>Available Fees</h6>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Default Value</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fee in fees" :key="fee.id">
                  <td>{{ fee.name }}</td>
                  <td>{{ fee.type }}</td>
                  <td>{{ fee.default_value }} <span v-if="fee.type === 'percentage'">%</span></td>
                </tr>
                <tr v-if="fees.length === 0">
                  <td colspan="3" class="text-center">No fees available</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isEditMode: Boolean,
    productData: Object,
  },
  data() {
    return {
      formData: {
        product_name: '',
        product_brand: '',
        product_category: '',
        product_subcategory: '',
        product_supplier: '',
        product_cmup: '',
        product_price: '',
        product_stock_1: '',
      },
      errors: {},
      suppliers: [],
      fees: [],
      productFields: {
        product_name: 'Product Name',
        product_brand: 'Brand',
        product_category: 'Category',
        product_subcategory: 'Subcategory',
        product_cmup: 'Cmup',
        product_price: 'Price',
        product_stock_1: 'Stock',
      },
    };
  },
  watch: {
    productData: {
      immediate: true,
      handler(newVal) {
        if (this.isEditMode && newVal) {
          this.formData = { ...newVal };
        }
      },
    },
  },
  mounted() {
    this.fetchSuppliers();
    this.fetchFees();
  },
  methods: {
    async fetchSuppliers() {
      try {
        const token = localStorage.getItem('token');
        const res = await axios.get('/api/suppliers/all', {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.suppliers = res.data;
      } catch (e) {
        console.error("Failed to fetch suppliers:", e);
      }
    },
    async fetchFees() {
      try {
        const token = localStorage.getItem('token');
        const res = await axios.get('/api/fees/all', {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.fees = res.data;
      } catch (e) {
        console.error("Failed to fetch fees:", e);
      }
    },
    async submitForm() {
      const method = this.isEditMode ? 'put' : 'post';
      const url = this.isEditMode ? `/api/products/${this.formData.id}` : '/api/products';
      try {
        await axios[method](url, this.formData);
        this.$emit('refresh');
        this.closeModal();
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Error saving product');
          console.error(error);
        }
      }
    },
    closeModal() {
      this.$emit('close');
    },
    handleOutsideClick(e) {
      if (e.target === this.$el) this.closeModal();
    },
  },
};
</script>




<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
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
.fees-section {
  border-top: 1px solid #eee;
  padding-top: 1rem;
}
.fees-section h6 {
  font-weight: bold;
  margin-bottom: 1rem;
}
.table {
  margin-bottom: 0;
}
.table th {
  background-color: #f8f9fa;
}
</style>
