import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import Deposit from '../views/Deposit.vue'
import Transfer from '../views/Transfer.vue'
import Transactions from '../views/Transactions.vue'
import store from '../store'

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login , meta: {requiresAuth: false}},
    { path: '/register', component: Register },
    {path: '/dashboard', component: Dashboard, meta: { requiresAuth: true }},
    { path: '/deposit', component: Deposit, meta: { requiresAuth: true } },
    { path: '/transfer', component: Transfer, meta: { requiresAuth: true } },
    { path: '/transactions', component: Transactions, meta: { requiresAuth: true } },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.isAuthenticated) {
            next('/login')
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router
