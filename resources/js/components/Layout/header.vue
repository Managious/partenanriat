<template>
  <header class="navbar navbar-light bg-light shadow-sm sticky-top px-3 header-bar">
    <div class="d-flex justify-content-between w-100 align-items-center">
      <button class="btn d-md-none" @click="$emit('toggleSidebar')">
        <i class="fas fa-bars"></i>
      </button>
      <div>
        <span class="fw-bold">Welcome</span>
      </div>

      <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
          <li><a class="dropdown-item" @click="goToProfile">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" @click="handleLogout">Logout</a></li>
        </ul>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref }           from 'vue'
import { useRouter }     from 'vue-router'
import { useAuthStore }  from '@/stores/authStore'

const router       = useRouter()
const authStore    = useAuthStore()
const isLoggingOut = ref(false)

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
  
<style scoped>
.header-bar {
  height: 56px;
  width: 100%;
}
</style>
    