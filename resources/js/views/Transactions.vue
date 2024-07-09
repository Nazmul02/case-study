<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Recent Transactions</h1>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="transaction in transactions.data" :key="transaction.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ transaction.amount }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="loadPage(transactions.current_page - 1)" :disabled="!transactions.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button @click="loadPage(transactions.current_page + 1)" :disabled="!transactions.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ transactions.from }}</span>
                        to
                        <span class="font-medium">{{ transactions.to }}</span>
                        of
                        <span class="font-medium">{{ transactions.total }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button v-for="page in transactions.last_page" :key="page" @click="loadPage(page)" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', page === transactions.current_page ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50']">
                            {{ page }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
    setup() {
        const transactions = ref({})

        const loadTransactions = async (page = 1) => {
            try {
                const response = await axios.get(`/api/transactions?page=${page}`)
                transactions.value = response.data
            } catch (error) {
                console.error('Failed to fetch transactions', error)
            }
        }

        const loadPage = (page) => {
            loadTransactions(page)
        }

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString()
        }

        onMounted(() => {
            loadTransactions()
        })

        return {
            transactions,
            loadPage,
            formatDate
        }
    }
}
</script>
