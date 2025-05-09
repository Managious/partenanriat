import axios from 'axios';
import { forceLogout  } from '@/composables/auth';

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
});

api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

api.interceptors.response.use(
    response => response,
    async error => {
        if (error.response?.status === 401) {
            await forceLogout();
        }

        return Promise.reject(error);
    }
);

export default api;
