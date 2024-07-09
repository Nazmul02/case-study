<template>
    <div v-if="isAuthenticated">
        <Dashboard />
    </div>
    <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Welcome to Our Banking System</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Please login or register to access your account.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <div class="sm:flex sm:justify-center sm:space-x-4 py-4">
                <router-link to="/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                </router-link>
                <router-link to="/register" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'
import Dashboard from './Dashboard.vue'

export default {
    components: {
        Dashboard
    },
    setup() {
        const store = useStore()
        const isAuthenticated = computed(() => store.getters.isAuthenticated)

        return {
            isAuthenticated
        }
    }
}
</script>
