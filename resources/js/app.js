import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import api from '@/axios';
import { useAuthStore } from '@/stores/authStore'; 

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

const auth = useAuthStore()
api.interceptors.response.use(
  res => res,
  err => {
    if (err.response?.status === 401) {
      auth.user = null
    }
    return Promise.reject(err)
  }
)

router.isReady().then(async () => {
  await auth.fetchUser()
  app.mount('#app')
})