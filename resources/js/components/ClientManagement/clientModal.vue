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
    isEditMode: Boolean,
    clientData: Object
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
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert("Something went wrong");
        }
      }
    },
    handleOutsideClick(event) {
      if (event.target === this.$el) {
        this.$emit('close');
      }
    }
  }
};
</script>
