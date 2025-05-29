<template>
  <header class="navbar navbar-light bg-light shadow-sm sticky-top px-3 header-bar">
    <div class="d-flex justify-content-between w-100 align-items-center">
      <button class="btn d-md-none" @click="$emit('toggleSidebar')">
        <i class="fas fa-bars"></i>
      </button>

      <div>
        <span class="fw-bold">
          Welcome
          <span v-if="user && user.role === 'Admin'">Admin</span>
          <span v-else-if="user && user.role === 'partner'">Partner</span>
          <span v-else-if="user && user.role === 'Manager'">Manager</span>
          <span v-else>User</span>
        </span>
      </div>

      <div class="dropdown">
        <button
          class="btn dropdown-toggle"
          type="button"
          id="userMenu"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fas fa-user-circle"></i>
        </button>
        <ul
          class="dropdown-menu dropdown-menu-end"
          aria-labelledby="userMenu"
        >
          <li><a class="dropdown-item" @click="goToProfile">Profile</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-danger" @click="handleLogout">Logout</a></li>
        </ul>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()
const isLoggingOut = ref(false)

const user = computed(() => authStore.user)

const handleLogout = async () => {
  isLoggingOut.value = true
  await authStore.logout()
  isLoggingOut.value = false
  router.push({ name: 'Login' })
}

const goToProfile = () => {
  router.push({ name: 'Profile' })
}
</script>
