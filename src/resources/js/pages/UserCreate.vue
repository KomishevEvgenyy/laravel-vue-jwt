<template>
    <div class="form-columns">
        <div class="form-group">
            <div class="form-label">Name</div>
            <input v-model="user.name" class="form-control" placeholder="Name"/>
            <div v-if="isErrorName" class="form-error" v-html="errorNameText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Email</div>
            <input v-model="user.email" class="form-control" placeholder="Email"/>
            <div v-if="isErrorEmail" class="form-error" v-html="errorEmailText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">First name</div>
            <input v-model="user.first_name" class="form-control" placeholder="First name"/>
            <div v-if="isErrorFirstName" class="form-error" v-html="errorFirstNameText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Last name</div>
            <input v-model="user.last_name" class="form-control" placeholder="Last name"/>
            <div v-if="isErrorLastName" class="form-error" v-html="errorLastNameText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Password</div>
            <input v-model="user.password" type="password" class="form-control" placeholder="Password"/>
            <div v-if="isErrorPassword" class="form-error" v-html="errorPasswordText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Confirm password</div>
            <input v-model="user.password_confirmation" type="password" class="form-control"
                   placeholder="Confirm password"/>
        </div>

        <button class="btn" type="button" @click.prevent="createUser">
            Create
        </button>
        <div v-if="showMessage" class="msg" :class="{error: errorMessage, success: successMessage}">{{ message }}</div>
    </div>
</template>

<script>
export default {
    name: "UserCreate",
    data() {
        return {
            user: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                first_name: '',
                last_name: '',
            },
            token: this.$store.getters.StateToken,
            showMessage: false,
            message: '',
            successMessage: false,
            errorMessage: false,
            error: {},
        }
    },
    computed: {
        isErrorFirstName: function () {
            return typeof this.error.first_name !== 'undefined';
        },
        errorFirstNameText: function () {
            return this.error.first_name.join(', <br>');
        },
        isErrorLastName: function () {
            return typeof this.error.last_name !== 'undefined';
        },
        errorLastNameText: function () {
            return this.error.last_name.join(', <br>');
        },
        isErrorEmail: function () {
            return typeof this.error.email !== 'undefined';
        },
        errorEmailText: function () {
            return this.error.email.join(', <br>');
        },
        isErrorName: function () {
            return typeof this.error.name !== 'undefined';
        },
        errorNameText: function () {
            return this.error.name.join(', <br>');
        },
        isErrorPassword: function () {
            return typeof this.error.password !== 'undefined';
        },
        errorPasswordText: function () {
            return this.error.password.join(', <br>');
        },
    },
    methods: {
        createUser() {
            this.error = {}
            this.showMessage = false
            axios.post('/api/user/create', {
                'first_name': this.user.first_name,
                'last_name': this.user.last_name,
                'email': this.user.email,
                'token': this.token,
                'name': this.user.name,
                'password': this.user.password,
                'password_confirmation': this.user.password_confirmation
            }).then(response => {
                if (typeof response.data.errors != 'undefined') {
                    this.error = response.data.errors
                } else if (typeof response.data.success != 'undefined') {
                    this.message = response.data.message
                    this.successMessage = true
                    this.errorMessage = false
                    this.showMessage = true
                } else if (typeof response.data.error != 'undefined') {
                    this.message = response.data.message
                    this.successMessage = false
                    this.errorMessage = true
                    this.showMessage = true
                }
            }).catch(error => {
                console.log(error)
            })
        }
    }
}
</script>

<style lang="sass" scoped>
.btn
    padding: 4px 8px
    color: #fff
    background-color: #b7821a
    margin-top: 8px

.msg
    margin-top: 16px

.error, .form-error
    color: #c71717

.success
    color: #10d310
</style>
