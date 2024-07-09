import { createStore } from 'vuex'
import axios from 'axios'

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
            const response = await axios.post('/api/login', credentials)
            commit('setToken', response.data.access_token)
        },
        async register({ commit }, userData) {
            const response = await axios.post('/api/register', userData)
            commit('setToken', response.data.access_token)
        },
        async logout({ commit }) {
            await axios.post('/api/logout')
            commit('logout')
        }
    },
    getters: {
        isAuthenticated: state => !!state.token
    }
})
