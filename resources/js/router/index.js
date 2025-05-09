import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from "@/stores/authStore";

import login from "../pages/login.vue";
import home from "../pages/home.vue";
import role from "../pages/role.vue";
import permission from "../pages/permission.vue";
import product from "../pages/product.vue";
import supplier from "../pages/supplier.vue";
import courrier from "../pages/courrier.vue";
import client from "../pages/client.vue";
import ProfileView from '../pages/ProfileView.vue';
import user from "../pages/user.vue";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: login
    },
    {
        path: '/',
        name: 'Home',
        component: home, 
        meta: { requiresAuth: true },
    },
    {
        path: '/roles',
        name: 'RoleManagement',
        component: role,
        meta: { requiresAuth: true },
    },
    {
        path: '/permissions',
        name: 'PermissionManagement',
        component: permission,
        meta: { requiresAuth: true },
    },
    {
        path: '/products',
        name: 'ProductManagement',
        component: product,
        meta: { requiresAuth: true },
    },
    {
        path: '/suppliers',
        name: 'SupplierManagement',
        component: supplier,
        meta: { requiresAuth: true },
    },
    {
        path: '/courriers',
        name: 'CourrierManagement',
        component: courrier,
        meta: { requiresAuth: true },
    },
    {
        path: '/clients',
        name: 'ClientManagement', 
        component: client,
        meta: { requiresAuth: true }, 
    },
    {
        path: '/users',
        name: 'UserManagement', 
        component: user,
        meta: { requiresAuth: true }, 
    },
    {
        path: '/profile',
        name: 'profile',
        component: ProfileView,
        meta: { requiresAuth: true }
      },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
  
    authStore.checkAuthentication();
  
    const isAuthenticated = !!authStore.token;
  
    if (to.meta.requiresAuth && !isAuthenticated) {
      next('/login');
    } else if (to.path === '/login' && isAuthenticated) {
      next('/');
    } else {
      next();
    }
});  

export default router;
