import { createRouter, createWebHistory } from 'vue-router';
import role from "../pages/role.vue";
import product from "../pages/product.vue";
import supplier from "../pages/supplier.vue";
import courrier from "../pages/courrier.vue";

const routes = [
    {
        path: '/roles',
        name: 'RoleManagement',
        component: role,
    },
    {
        path: '/products',
        name: 'ProductManagement',
        component: product,
    },
    {
        path: '/suppliers',
        name: 'SupplierManagement',
        component: supplier,
    },
    {
        path: '/courrier',
        name: 'CourrierManagement',
        component: courrier,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
