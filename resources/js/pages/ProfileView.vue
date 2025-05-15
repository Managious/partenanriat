<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h5 class="mb-0">Profile Information</h5>
          </div>
          <div class="card-body">
            <!-- Loading state -->
            <div v-if="loadingUser" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p>Loading profile data...</p>
            </div>

            <!-- Profile data when loaded -->
            <div v-else>
              <!-- Success/error messages -->
              <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>

              <!-- Profile information -->
              <div class="mb-4">
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input type="text" class="form-control" :value="user.name" disabled>
                </div>
                <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control" :value="user.username" disabled>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" :value="user.email" disabled>
                </div>
              </div>

              <!-- Password change form -->
              <div class="border-top pt-4">
                <h5 class="mb-3">Change Password</h5>
                <form @submit.prevent="changePassword">
                  <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" v-model="password.current" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" v-model="password.new" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" v-model="password.confirm" required>
                  </div>
                  <button type="submit" class="btn btn-primary" :disabled="loadingPassword">
                    <span v-if="loadingPassword" class="spinner-border spinner-border-sm"></span>
                    Update Password
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/axios.js';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

// User data
const user = ref({
  name: '',
  username: '',
  email: ''
});

// Password form
const password = ref({
  current: '',
  new: '',
  confirm: ''
});

// UI states
const loadingUser = ref(true);
const loadingPassword = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Fetch user data
onMounted(async () => {
  try {
    // Option 1: Use data from authStore if available
    if (authStore.user) {
      user.value = {
        name: authStore.user.name,
        username: authStore.user.username,
        email: authStore.user.email
      };
    }
    
    // Option 2: Always fetch fresh data from API
    const response = await api.get('/user');
    user.value = response.data;
    
  } catch (error) {
    console.error('Failed to load user:', error);
    errorMessage.value = 'Failed to load profile data';
  } finally {
    loadingUser.value = false;
  }
});

// Change password
const changePassword = async () => {
  if (password.value.new !== password.value.confirm) {
    errorMessage.value = 'New passwords do not match';
    return;
  }

  loadingPassword.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await api.post('/change-password', {
      current_password: password.value.current,
      new_password: password.value.new,
      new_password_confirmation: password.value.confirm
    });

    successMessage.value = 'Password updated successfully!';
    password.value = { current: '', new: '', confirm: '' };
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 
                        'An error occurred while changing password';
  } finally {
    loadingPassword.value = false;
  }
};
</script>

<style scoped>
.card {
  border-radius: 0.5rem;
}
.alert {
  margin-bottom: 1rem;
}
</style>