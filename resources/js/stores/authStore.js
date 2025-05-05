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

                this.user = null;
                this.token = null;

                localStorage.removeItem('token');

                delete api.defaults.headers.common['Authorization'];

                const router = useRouter();
                router.push('/login');
            } catch(error) {
                alert('Logout failed:', error);
                throw new Error('Logout failed, please try again.');
            }
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
    },
});