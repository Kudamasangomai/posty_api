import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("./Pages/HomeRoute.vue"),
    },
    {
        path: "/about",
        component: () => import("./Pages/AboutRoute.vue"),
    },

    ,
    {
        path: "/posts",
        component: () => import("./Pages/Posts.vue"),
    },

    {
        path: "/posts/:post_id",
        component: () => import("./Pages/Post.vue"),
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
