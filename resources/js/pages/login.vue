<template>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-white-50 mb-5">Please enter your login and password!</p>
                                <form  @submit.prevent="handleLogin">
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <label class="form-label" for="login">Email or Username</label>
                                        <input type="text" id="login" v-model="login" class="form-control form-control-lg" required />
                                    </div>
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" v-model="password" class="form-control form-control-lg" required />
                                    </div>

                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit" :disabled="isLoading">
                                        {{ isLoading ? 'Logging in...' : 'Login' }}
                                    </button>

                                    <p v-if="error" class="error-message">{{ error }}</p>
                                </form>
                                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref }            from 'vue'
import { useRouter }      from 'vue-router'
import { useAuthStore }   from '../stores/authStore'

const login     = ref('')
const password  = ref('')
const isLoading = ref(false)
const error     = ref('')

const authStore = useAuthStore()
const router    = useRouter()

const handleLogin = async () => {
  error.value     = ''
  isLoading.value = true

  const success = await authStore.login({
    login:    login.value,
    password: password.value
  })

  isLoading.value = false

  if (success) {
    router.push({ name: 'Home' })
  } else {
    if (authStore.errors.login) {
      error.value = authStore.errors.login[0]
    }
    else if (authStore.errors.password) {
      error.value = authStore.errors.password[0]
    }
    else {
      error.value = 'Login failed. Please check your credentials.'
    }
  }
}
</script>

<style scoped>
.gradient-custom {
/* fallback for old browsers */
background: #6a11cb;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, lightgray, whitesmoke)
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>