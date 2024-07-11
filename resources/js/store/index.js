import { createStore } from 'vuex'
import axios from 'axios'
import router from '../router'

export default createStore({
    state: {
        user: null,
        token: localStorage.getItem('token') || null
    },
    mutations: {
        setUser(state, user) {
            state.user = user
        },
        setToken(state, token) {
            state.token = token
            localStorage.setItem('token', token)
        },
        logout(state) {
            state.user = null
            state.token = null
            localStorage.removeItem('token')
        }
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await axios.post('/api/login', credentials)
                commit('setToken', response.data.access_token)
                await this.dispatch('fetchUser')
                router.go('/dashboard')
            } catch (error) {
                console.error('Login failed', error)
            }
        },
        async register({ commit }, userData) {
            try {
                const response = await axios.post('/api/register', userData)
                commit('setToken', response.data.access_token)
                this.dispatch('fetchUser')
                router.push('/dashboard')
            } catch (error) {
                console.error('Registration failed', error)
            }
        },
        async logout({ commit }) {
            try {
                await axios.post('/api/logout')
                commit('logout')
                router.push('/login')
            } catch (error) {
                console.error('Logout failed', error)
            }
        },
        async fetchUser({ commit }) {
            try {
                const response = await axios.get('/api/user')
                commit('setUser', response.data)
            } catch (error) {
                console.error('Fetching user failed', error)
            }
        }
    },
    getters: {
        isAuthenticated: state => !!state.token,
        user: state => state.user
    }
})
