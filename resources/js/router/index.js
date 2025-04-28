import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from "@/stores/authStore";

import login from "../pages/login.vue";
import role from "../pages/role.vue";
import permission from "../pages/permission.vue";
import product from "../pages/product.vue";
import supplier from "../pages/supplier.vue";
import courrier from "../pages/courrier.vue";
import client from "../pages/client.vue";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: login
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

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    


    if(to.meta.requiresAuth && !authStore.token) {
        next('/login');
    } else if(to.path === '/login' && authStore.token) {
        next('/');
    } else {
        next();
    }
});

export default router;
