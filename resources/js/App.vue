<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link to="/" class="text-xl font-bold text-indigo-600">BankApp</router-link>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <router-link
                                v-for="navItem in navItems"
                                :key="navItem.to"
                                :to="navItem.to"
                                :class="[
                  isActive(navItem.to)
                    ? 'border-indigo-500 text-gray-900'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                  'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
                ]"
                            >
                                {{ navItem.text }}
                            </router-link>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <button v-if="isAuthenticated" @click="logout" class="text-gray-500 hover:text-gray-700">Logout</button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

export default {
    setup() {
        const store = useStore()
        const router = useRouter()
        const route = useRoute()

        const isAuthenticated = computed(() => store.getters.isAuthenticated)

        const logout = async () => {
            await store.dispatch('logout')
            router.push('/login')
        }

        const navItems = computed(() => {
            if (isAuthenticated.value) {
                return [
                    { to: '/dashboard', text: 'Dashboard' },
                    { to: '/deposit', text: 'Deposit' },
                    { to: '/transfer', text: 'Transfer' },
                    { to: '/transactions', text: 'Transactions' }
                ]
            } else {
                return [
                    { to: '/', text: 'Home' },
                    { to: '/login', text: 'Login' },
                    { to: '/register', text: 'Register' }
                ]
            }
        })

        const isActive = (path) => {
            return route.path === path
        }

        return {
            isAuthenticated,
            logout,
            navItems,
            isActive
        }
    }
}
</script>
