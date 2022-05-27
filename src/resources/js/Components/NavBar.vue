<template>
    <header id="header">
        <div class="header">
            <template v-if="isLoggedIn">
                <div class="left-block">
                    <router-link :to="{name: 'userList'}">Laravel</router-link>
                </div>
            </template>
            <template v-else>
                <div class="left-block">
                    <router-link :to="{name: 'home'}">Laravel</router-link>
                </div>
            </template>
            <div class="right-block">
                <template v-if="isLoggedIn">
                    <a @click="logout">LogOut</a>
                </template>
                <template v-else>
                    <router-link style="margin-right: 16px" :to="{name: 'login'}">Login</router-link>
                    <router-link :to="{name: 'register'}">Register</router-link>
                </template>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "NavBar",
    data() {
        return {
            user: this.$store.getters.StateUser,
        }
    },
    computed: {
        isLoggedIn: function () {
            return this.$store.getters.isAuthenticated
        }
    },
    methods: {
        async logout() {
            await this.$store.dispatch('LogOut')
            await this.$router.push({name: 'home'})
        },
    }
}
</script>

<style lang="sass" scoped>
.header, .right-block
    display: flex
    justify-content: space-between
    align-items: center

.header
    padding: 20px
    border-bottom: 1px solid #a0aec0

.left-block
    cursor: pointer
    padding: 4px 8px
    a
        cursor: pointer

.right-block
    a
        padding: 4px 8px
        background: #67696c
        border-radius: 4px
        color: #fff
        cursor: pointer
</style>
