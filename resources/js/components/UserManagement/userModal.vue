<template>
  <div class="modal-backdrop">
    <div class="modal-content">
      <h5 class="modal-title">{{ isEditMode ? 'Edit User' : 'Create User' }}</h5>
      <form @submit.prevent="submitForm">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" v-model="form.name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label>Username</label>
          <input type="text" v-model="form.username" class="form-control" required />
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" v-model="form.email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select v-model="form.role_id" class="form-control" required>
            <option value="">Select a role</option>
            <option v-for="role in filteredRoles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
        </div>
        <div class="mb-3" v-if="!isEditMode">
          <label>Password</label>
          <input type="password" v-model="form.password" class="form-control" required />
        </div>
        <div class="mb-3" v-if="isEditMode">
          <label>Active</label>
          <select v-model="form.is_active" class="form-control" required>
            <option :value="true">Active</option>
            <option :value="false">Inactive</option>
          </select>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" @click="$emit('close')">Cancel</button>
          <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
        </div>
      </form>
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
    userData: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      form: {
        name: '',
        username: '',
        email: '',
        role_id: '',
        password: '',
        is_active: true,
      },
      roles: [], // Liste complète des rôles
    };
  },
  computed: {
    filteredRoles() {
      // On enlève le rôle Admin (par son name)
      return this.roles.filter(role => role.name.toLowerCase() !== 'admin');
    }
  },
  mounted() {
    this.fetchRoles();
    if (this.isEditMode && this.userData) {
      this.form = {
        name: this.userData.name,
        username: this.userData.username,
        email: this.userData.email,
        role_id: this.userData.role_id,
        password: '',
        is_active: this.userData.is_active,
      };
    }
  },
  methods: {
    async fetchRoles() {
      try {
        const response = await axios.get('/api/roles/all');
        this.roles = response.data; // ou response.data.data selon ton API
      } catch (error) {
        console.error('Error fetching roles:', error);
      }
    },
    async submitForm() {
      try {
        if (this.isEditMode) {
          await axios.put(`/api/users/${this.userData.id}`, this.form);
        } else {
          await axios.post('/api/users', this.form);
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
