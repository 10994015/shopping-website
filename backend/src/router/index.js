import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue"
import Products from "../views/Products.vue"
import AddProduct from "../views/AddProduct.vue"
import Orders from "../views/Orders.vue"
import Login from "../views/Login.vue"
import RequestPassword from "../views/RequestPassword.vue"
import ResetPassword from "../views/ResetPassword.vue"
import NotFound from "../views/NotFound.vue"
import AppLayout from "../components/AppLayout.vue"
import store from "../store";
const routes = [
    {
        path:'/',
        name:'app',
        component:AppLayout,
        meta:{
            requiresAuth:true,
        },
        children:[
            {
                path:'',
                name:'app.dashboard',
                component: Dashboard,
            },
            {
                path:'products',
                name:'app.products',
                component: Products,
            },
            {
                path:'add-product/:id',
                name:'app.addProduct',
                component: AddProduct,
            },
            {
                path:'orders',
                name:'app.orders',
                component: Orders,
            },
        ]
    },
    {
        path:'/login',
        name:'login',
        component: Login,
        meta:{
            requiresGuest:true,
        },
    },
    {
        path:'/request-password',
        name:'requestPassword',
        component: RequestPassword,
        meta:{
            requiresGuest:true,
        },
    },
    {
        path:'/reset-password/:token',
        name:'resetPassword',
        component: ResetPassword,
        meta:{
            requiresGuest:true,
        },
    },
    {
        path: '/:catchAll(.*)',
        name:'notfound',
        component:NotFound,
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next)=>{
    if(to.meta.requiresAuth && !store.state.user.token){
        next({name:'login'})
    }else if(to.meta.requiresGuest && store.state.user.token){
        next({name:'app.dashboard'});
    }else{
        next();
    }
})

export default router;