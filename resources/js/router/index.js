
import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';
import Products from '../components/Products';
import EditProduct from '../components/EditProduct';
import AddProduct from '../components/AddProduct';
import ShowProduct from '../components/ShowProduct';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'products',
        path: '/novalager',
        component: Products
    },
    {
        name: 'addproduct',
        path: '/novalager/add',
        component: AddProduct
    },
    {
        name: 'editproduct',
        path: '/novalager/edit/:id',
        component: EditProduct
    },
    {
        name: 'showproduct',
        path: '/novalager/show/:id',
        component: ShowProduct
    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
