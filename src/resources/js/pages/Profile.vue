<template>
    <div class="form-columns">
        <div class="form-group">
            <div class="form-label">First name</div>
            <input v-model="user.customer.first_name" :disabled="disabled" class="form-control"
                   placeholder="First name"/>
            <div v-if="isErrorFirstName" class="form-error" v-html="errorFirstNameText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Last name</div>
            <input v-model="user.customer.last_name" :disabled="disabled" class="form-control" placeholder="Last name"/>
            <div v-if="isErrorLastName" class="form-error" v-html="errorLastNameText"></div>
        </div>
        <div class="form-group">
            <div class="form-label">Email</div>
            <input v-model="user.email" :disabled="disabled" class="form-control" placeholder="Email"/>
            <div v-if="isErrorEmail" class="form-error" v-html="errorEmailText"></div>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input v-model="user.active" :disabled="disabled" name="active" type="checkbox">
                </label>
            </div>
        </div>
        <button v-if="!disabled" class="btn" type="button" @click.prevent="updateUser">
            Update
        </button>
        <div v-if="showMessage" class="msg" :class="{error: errorMessage, success: successMessage}">{{ message }}</div>
    </div>

</template>

<script>
export default {
    name: "Profile",
    data() {
        return {
            user: [],
            user_id: this.$route.params.user_id,
            token: this.$store.getters.StateToken,
            current_user: this.$store.getters.StateUser,
            disabled: false,
            showMessage: false,
            message: '',
            successMessage: false,
            errorMessage: false,
            error: {},
        }
    },
    created() {
        this.getUser()
        this.isUser()
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
    },
    methods: {
        getUser() {
            axios.get('api/user/show', {
                params: {
                    'token': this.token,
                    'user_id': this.user_id
                }
            }).then(response => {
                this.user = response.data.data
            }).catch(error => {
                console.log(error)
            })
        },
        isUser() {
            this.disabled = this.current_user.customer.role == 2;
        },
        updateUser() {
            this.error = {}
            this.showMessage = false
            axios.post('/api/user/update', {
                'first_name': this.user.customer.first_name,
                'last_name': this.user.customer.last_name,
                'email': this.user.email,
                'active': this.user.active,
                'token': this.token,
                'id': this.user.id
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
