<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Account Balance</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${{ balance }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total Deposits</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${{ totalDeposits }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total Transfers</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${{ totalTransfers }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Transaction</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ lastTransaction ? `${lastTransaction.type}: $${lastTransaction.amount}` : 'No transactions yet' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
    setup() {
        const balance = ref(0)
        const totalDeposits = ref(0)
        const totalTransfers = ref(0)
        const lastTransaction = ref(null)

        const fetchDashboardData = async () => {
            try {
                const response = await axios.get('/api/dashboard')
                balance.value = response.data.balance
                totalDeposits.value = response.data.totalDeposits
                totalTransfers.value = response.data.totalTransfers
                lastTransaction.value = response.data.lastTransaction
            } catch (error) {
                console.error('Failed to fetch dashboard data', error)
            }
        }

        onMounted(fetchDashboardData)

        return {
            balance,
            totalDeposits,
            totalTransfers,
            lastTransaction
        }
    }
}
</script>
