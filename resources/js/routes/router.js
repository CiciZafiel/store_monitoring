import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'main',
        component: () => import('../contents/layout/main.vue'),
        redirect: '/dashboard',
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                meta: {},
                component: () => import('../contents/pages/dashboard/index.vue'),
            },
        ],
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;