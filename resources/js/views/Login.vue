<template>
    <div v-if="isAuthenticated">
        <Dashboard />
    </div>
    <div v-else>
        <Form @submit="login" v-slot="{ errors }">
            <div>
                <Field name="email" type="email" v-model="email" :rules="emailRules" />
                <span v-if="errors.email">{{ errors.email }}</span>
            </div>
            <div>
                <Field name="password" type="password" v-model="password" :rules="passwordRules" />
                <span v-if="errors.password">{{ errors.password }}</span>
            </div>
            <button type="submit">Login</button>
        </Form>
    </div>

</template>



<script>
import {computed, ref} from 'vue'
import { useStore } from 'vuex'
import {useRoute, useRouter} from 'vue-router'
import { useToast } from "vue-toastification";
import Dashboard from './Dashboard.vue'

import { Form, Field } from 'vee-validate';
import { required, email } from '@vee-validate/rules';


export default {
    components: { Form, Field,Dashboard },
    setup() {
        const toast = useToast();
        const store = useStore()
        const router = useRouter()
        const email = ref('')
        const password = ref('')
        const emailRules = { required, email };
        const passwordRules = { required, min: 8 };
        const route = useRoute()

        const login = async () => {
            try {
                await store.dispatch('login', { email: email.value, password: password.value })
                router.push('/dashboard')
                toast.success("Logged in successfully")

            } catch (error) {
                console.error('Login failed', error)
                toast.error("Login failed. Please check your credentials.")
            }

        }

        const isAuthenticated = computed(() => store.getters.isAuthenticated)


        return { email, password, login,isAuthenticated }
    }
}
</script>
