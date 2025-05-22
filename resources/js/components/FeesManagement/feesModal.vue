<template>
  <div class="modal-backdrop">
    <div class="modal-content">
      <h5 class="modal-title">{{ isEditMode ? 'Edit Fee' : 'Create Fee' }}</h5>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label>Name</label>
          <input v-model="form.name" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Type</label>
          <select v-model="form.type" class="form-control" required>
            <option value="amount">Amount</option>
            <option value="percentage">Percentage</option>
          </select>
        </div>
        <div class="mb-3">
            <label>Default Value</label>
            <input 
                v-model.number="form.default_value" 
                type="number" 
                class="form-control" 
                min="0"
                oninput="this.value = Math.abs(this.value)"
                required
            >
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-secondary me-2" type="button" @click="$emit('close')">Cancel</button>
          <button class="btn btn-success" type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isEditMode: Boolean,
    feeData: Object,
  },
  data() {
    return {
      form: {
        name: '',
        type: 'amount',
        default_value: 0,
      },
    };
  },
  mounted() {
    if (this.isEditMode && this.feeData) {
      this.form = { ...this.feeData };
    }
  },
  methods: {
    async submit() {
      try {
        if (this.isEditMode) {
          await axios.put(`/api/fees/${this.feeData.id}`, this.form);
        } else {
          await axios.post('/api/fees', this.form);
        }
        this.$emit('refresh');
        this.$emit('close');
      } catch (err) {
        console.error("Submit failed:", err);
      }
    }
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
