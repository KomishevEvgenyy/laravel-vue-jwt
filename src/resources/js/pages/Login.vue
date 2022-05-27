<template>
    <div class="row justify-content-center">
        <div class="col-sm-4 mt-5">
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" v-model="form.email"/>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" v-model="form.password"/>
                </div>
                <div class="error" v-if="showError">Wrong email or password</div>

                <button type="submit" class="btn btn-dark mt-2">Login</button>
            </form>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex"

export default {
    name: "Login",
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            showError: false
        }
    },
    methods: {
        ...mapActions(["LogIn"]),

        async login() {
            const User = new FormData()
            User.append('email', this.form.email)
            User.append('password', this.form.password)
            try {
                await this.LogIn(User)
                await this.$router.push({name: 'userList'})
                this.showError = false
            } catch (e) {
                this.showError = true
            }
        }
    }
}
</script>

<style lang="sass" scoped>
.error
    margin-top: 4px
    color: #d70f0f
</style>
