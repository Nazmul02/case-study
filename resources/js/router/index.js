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
    { path: '/login', component: Login },
    { path: '/register', component: Register, meta: {guestOnly : true} },
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
    const isAuthenticated = store.getters.isAuthenticated
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next('/login')
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.guestOnly)) {
        if (isAuthenticated) {
            next('/dashboard')
        } else {
            next()
        }
    } else {
        next()
    }

})

export default router
