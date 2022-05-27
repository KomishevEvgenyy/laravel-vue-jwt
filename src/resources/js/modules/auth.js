import axios from 'axios';

const state = {
    user: null,
    token: null
};
const getters = {
    isAuthenticated: state => !!state.token,
    StateUser: state => state.user,
    StateToken: state => state.token
};
const actions = {
    async Register({dispatch}, form) {
        let response = await axios.post('api/register', form)
    },
    async LogIn({commit, dispatch}, User) {
        let response = await axios.post('api/login', User)
        commit('setToken', response.data.token)
        await dispatch('getUser', response.data.token)

    },
    async LogOut({commit}) {
        commit('LogOut')
    },
    async getUser({commit}, token) {
        let response = await axios.get('api/profile', {params: {'token': token}})
        commit('setUser', response.data.data)
    }

};
const mutations = {
    setUser(state, user) {
        state.user = user
    },
    setToken(state, token) {
        state.token = token
    },
    LogOut(state) {
        state.user = null
        state.token = null
    }
};
export default {
    state,
    getters,
    actions,
    mutations
};
