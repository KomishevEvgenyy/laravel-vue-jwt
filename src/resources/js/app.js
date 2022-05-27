import {next} from "lodash/seq";

require('./bootstrap');

import {createApp} from "vue";
import {createRouter, createWebHashHistory} from "vue-router";
import axios from 'axios'
import VueAxios from 'vue-axios'
import Vuex from 'vuex'

import Root from './App.vue'
import routes from "./routes";
import store from "./store";

axios.defaults.withCredentials = true

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    mode: 'history',
    base: process.env.APP_URL
})
const app = createApp(Root);

app.config.productionTip = true
app.use(router)
app.use(VueAxios, axios)
app.use(Vuex)
app.use(store)

app.mount('#app')

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (store.getters.isAuthenticated) {
            next()
            return
        }
        next('/login')
    } else {
        next()
    }
})
router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.guest)) {
        if (store.getters.isAuthenticated) {
            next({name: 'userList'})
            return
        }
        next()
    } else {
        next()
    }

})
axios.interceptors.response.use(undefined, function (error) {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry) {

            originalRequest._retry = true;
            store.dispatch('LogOut')
            return router.push({name: 'login'})
        }
    }
})

