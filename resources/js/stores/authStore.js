import { defineStore } from 'pinia';
import api               from '@/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    errors: {}
  }),
  getters: {
    isAuthenticated: (s) => !!s.user
  },
  actions: {
    async login(form) {
      this.errors = {};
      try {
        await api.get('/sanctum/csrf-cookie');
        const { data } = await api.post('/login', form);
        this.user = data.user;
        return true;                        
      } catch (e) {
        if (e.response?.status === 422) {
          this.errors = e.response.data.errors || { login: [e.response.data.message] };
        }
        return false;                       
      }
    },

    async fetchUser() {
      try {
        const { data } = await api.get('/user');
        this.user = data.user;
      } catch (e) {
        if (e.response?.status === 401) {
          this.clearAuth();
        }
      }
    },

    async logout() {
      try { await api.post('/logout'); } catch {}
      this.clearAuth();
    },

    clearAuth() {
      this.user   = null;
      this.errors = {};
    }
  }
});
