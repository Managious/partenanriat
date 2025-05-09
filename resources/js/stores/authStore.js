import { defineStore } from "pinia";
import api from "@/axios";
import { useRouter } from "vue-router";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
        permissions: [],
    }),

    actions: {
        async login(credentials) {
            try {
                const response = await api.post('/auth/login', credentials);

                this.token = response.data.token;
                this.user = response.data.user;
                this.permissions = response.data.user.permissions;

                localStorage.setItem('token', this.token);
                localStorage.setItem('permissions', JSON.stringify(this.permissions));

                api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                
            }catch (error) {
                alert('Login failed:', error);
                throw new Error('Login failed, please try again.');
            }
        },

        async logout() {
            try {
                await api.post('/auth/logout');
            } catch (error) {
                console.error('Logout request failed, but continuing with cleanup');
            }
        
            this.user = null;
            this.token = null;
            this.permissions = [];
        
            localStorage.removeItem('token');
            localStorage.removeItem('permissions');
        
            delete api.defaults.headers.common['Authorization'];
        },
        
        checkAuthentication() {
            const token = localStorage.getItem('token');
            const permissions = localStorage.getItem('permissions');

            if(token) {
                this.token = token;
                api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
            if (permissions) {
                this.permissions = JSON.parse(permissions);
            }
        },
        // In authStore.js actions:
        async fetchUser() {
            try {
                    const response = await api.get('/auth/user');
                    this.user = response.data;
                    return response.data;
                } catch (error) {
                    console.error('Failed to fetch user:', error);
                    throw error;
            }
  }
    },
});