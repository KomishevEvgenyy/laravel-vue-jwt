<template>
    <div class="row justify-content-center">
        <div class="col-sm-4 mt-5">
            <form @submit.prevent="register">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" v-model="form.name"/>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" v-model="form.email"/>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" v-model="form.password"/>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Password</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           v-model="form.password_confirmation"/>
                </div>
                <div class="error" v-if="showError">Wrong data</div>
                <button type="submit" class="btn btn-dark mt-2">Register</button>
            </form>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex"

export default {
    name: "Register",
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            showError: false
        }
    },
    methods: {
        ...mapActions(["Register"]),

        async register() {
            try {
                await this.Register(this.form);
                // await this.$router.push({name: 'login'})
                this.showError = false
            } catch (e) {
                console.log(e)
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
