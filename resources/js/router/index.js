import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Product from '../pages/Product';
import Login from '../pages/Login';
import Books from '../components/Books';
import EditBook from '../components/EditBook';
import AddBook from '../components/AddBook';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'product',
        path: '/product/:id',
        component: Product
    },
    {
        name: 'login',
        path: '/admin/login',
        component: Login
    },
    {
        name: 'books',
        path: '/admin',
        component: Books,
        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'addbook',
        path: '/admin/books/add',
        component: AddBook,
        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'editbook',
        path: '/admin/books/edit/:id',
        component: EditBook,
        meta: {
            requiresAuth: true
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    // Check for protected route
    if (requiresAuth && !window.Laravel.isLoggedin) next('/admin/login')
    else next();
});

export default router;