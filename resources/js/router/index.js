import { createRouter, createWebHistory } from "vue-router";
import BaseComponent from "../components/BaseComponent.vue";

const routes = [
    {
        path: "/test-vue",
        component: BaseComponent,
        name: "BaseComponent",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
