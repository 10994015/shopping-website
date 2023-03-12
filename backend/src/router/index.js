
import Dashboard from "../views/dashboard.vue"
import Login from "../views/login.vue"
import {createRouter, createWebHistory} from "vue-router"
const routes = [
    {
        path:'/',
        name:'dashboard',
        component:Dashboard,
    },
    {
        path:'/login',
        name:'login',
        component:Login,
    },
   
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;