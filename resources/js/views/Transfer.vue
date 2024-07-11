<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Transfer</h1>
            <p class="mt-1 max-w-2xl text-xl text-gray-500">Current balance: ${{ balance }}</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form @submit.prevent="transfer" class="space-y-6 sm:px-6 lg:px-8">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 pt-5">Amount</label>
                    <div class="mt-1">
                        <input v-model="transferAmount" type="number" step="0.01" required name="amount" id="amount" class="h-9 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label for="recipient" class="block text-sm font-medium text-gray-700">Recipient Account ID</label>
                    <div class="mt-1">
                        <input v-model="recipientId" type="number" name="recipient" required id="recipient" class="h-9 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Recipient Account ID">
                    </div>
                </div>
                <div>
                    <button type="submit" class="mb-5 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
import { useToast } from "vue-toastification";
import { useStore } from 'vuex'
import {useRouter} from "vue-router";


export default {
    setup() {
        const store = useStore()
        const router = useRouter()
        const balance = ref(0)
        const transferAmount = ref('')
        const recipientId = ref('')

        const toast = useToast();


        const fetchBalance = async () => {
            try {
                const response = await axios.get('/api/account/balance')
                balance.value = response.data.balance
            } catch (error) {
                if(error.response.data.message === 'Unauthenticated.'){
                    router.push('/login')
                }

                console.error('Failed to fetch balance', error)
            }
        }

        const transfer = async () => {
            try {
                const response =  await axios.post('/api/transfer', {
                    amount: transferAmount.value,
                    to_account_id: recipientId.value
                })

                toast.success(response.data.message)

                transferAmount.value = ''
                recipientId.value = ''
                await fetchBalance()
            } catch (error) {
                if (error.response && error.response.data && error.response.data.message) {
                    toast.error( error.response.data.message )
                    console.log(error.response.data.message === 'Unauthenticated.')
                } else {
                    toast.error('Transfer failed', error)
                }
            }
        }

        fetchBalance()

        return { balance, transferAmount, recipientId, transfer }
    }
}
</script>
