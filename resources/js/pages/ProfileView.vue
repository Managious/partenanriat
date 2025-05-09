<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h5 class="mb-0">Profile Information</h5>
          </div>
          <div class="card-body">
            <!-- Affichage des messages de succès/erreur -->
            <div v-if="successMessage" class="alert alert-success">
              {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="alert alert-danger">
              {{ errorMessage }}
            </div>

            <!-- Informations du profil -->
            <div class="mb-4">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" v-model="user.name" disabled>
              </div>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" v-model="user.username" disabled>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" v-model="user.email" disabled>
              </div>
            </div>

            <!-- Formulaire de changement de mot de passe -->
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
                <button type="submit" class="btn btn-primary" :disabled="loading">
                  <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                  Update Password
                </button>
              </form>
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
const user = ref({
  name: '',
  username: '',
  email: ''
});

const password = ref({
  current: '',
  new: '',
  confirm: ''
});

const successMessage = ref('');
const errorMessage = ref('');
const loading = ref(false);

// Récupérer les infos de l'utilisateur
onMounted(async () => {
  try {
    // Replace: const response = await axios.get('/api/user');
    const response = await api.get('/user');  // Note: removed '/api' prefix
    user.value = response.data;
    console.log('User data loaded:', user.value);
  } catch (error) {
    console.error('Failed to load user:', error.response?.data || error.message);
  }
});

// Changer le mot de passe
const changePassword = async () => {
  if (password.value.new !== password.value.confirm) {
    errorMessage.value = 'New passwords do not match';
    return;
  }

  loading.value = true;
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
    if (error.response && error.response.data.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = 'An error occurred while changing password';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.card {
  border-radius: 0.5rem;
}
</style>