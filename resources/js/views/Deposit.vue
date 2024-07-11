<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-3xl font-bold text-gray-900">Deposit Funds</h1>
            <div class="mt-5 pt-5 md:mt-0 md:col-span-2 ">
                <form @submit.prevent="submitDeposit">
                    <div class="">
                        <div class="w-full">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input v-model="amount" type="number" name="amount" id="amount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md h-8" required placeholder="0.00" step="0.01" min="0.01">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="my-5 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Deposit
                        </button>
                    </div>


                </form>
            </div>
        </div>
         </div>
</template>

<script>
import {onMounted, ref} from 'vue'
import axios from 'axios'
import { useToast } from "vue-toastification";
import { useStore } from 'vuex'
import {useRouter} from "vue-router";


export default {
    setup() {
        const store = useStore()
        const router = useRouter()
        const amount = ref('')
        const toast = useToast();

        const submitDeposit = async () => {
            try {
                const response = await axios.post('/api/deposit', { amount: amount.value })
                toast.success(response.data.message)
                amount.value = ''
            } catch (error) {
                if (error.response && error.response.data && error.response.data.message) {
                    toast.error( error.response.data.message )
                } else {
                    toast.error('An error occurred while processing your deposit.' )
                }
            }
        }
        const fetchUser = async () => {
            try {
                const response = await axios.get('/api/user')
            } catch (error) {
                if(error.response.data.message === 'Unauthenticated.'){
                    router.push('/login')
                }
            }
        }
        onMounted(fetchUser)

        return {
            amount,
            submitDeposit
        }
    }
}
</script>
