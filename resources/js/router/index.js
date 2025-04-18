import { createRouter, createWebHistory } from 'vue-router';
import role from "../pages/role.vue";

const routes = [
    {
        path: '/roles',
        name: 'RoleManagement',
        component: role,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
