<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Transfer</h1>
            <p class="mt-1 max-w-2xl text-xl text-gray-500">Current balance: ${{ balance }}</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form @submit.prevent="transfer" class="space-y-6 sm:px-6 lg:px-8">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <div class="mt-1">
                        <input v-model="transferAmount" type="number" step="0.01" name="amount" id="amount" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label for="recipient" class="block text-sm font-medium text-gray-700">Recipient Account ID</label>
                    <div class="mt-1">
                        <input v-model="recipientId" type="number" name="recipient" id="recipient" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Recipient Account ID">
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'

export default {
    setup() {
        const balance = ref(0)
        const transferAmount = ref('')
        const recipientId = ref('')

        const fetchBalance = async () => {
            try {
                const response = await axios.get('/api/account/balance')
                balance.value = response.data.balance
            } catch (error) {
                console.error('Failed to fetch balance', error)
            }
        }

        const transfer = async () => {
            try {
                await axios.post('/api/transfer', {
                    amount: transferAmount.value,
                    to_account_id: recipientId.value
                })
                transferAmount.value = ''
                recipientId.value = ''
                await fetchBalance()
            } catch (error) {
                console.error('Transfer failed', error)
            }
        }

        fetchBalance()

        return { balance, transferAmount, recipientId, transfer }
    }
}
</script>
