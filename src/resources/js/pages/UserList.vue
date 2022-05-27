<template>
    <div class="user-list">
        <router-link v-if="isAdmin" :to="{name: 'userCreate'}" class="btn">
            Create
        </router-link>
        <div class="flex table-title">
            <div class="number-column">§</div>
            <div>Email</div>
            <div>First name</div>
            <div>Last name</div>
            <div>Active</div>
        </div>
        <router-link :to="{name: 'profile', params: {'user_id' : user.id}}" v-for="(user, index) in users"
                     class="flex row-item">
            <div class="number-column">{{ index + 1 }}</div>
            <div>{{ user.email }}</div>
            <div>{{ user.customer.first_name }}</div>
            <div>{{ user.customer.last_name }}</div>
            <div v-if="user.active">Active</div>
            <div v-else>Disabled</div>
        </router-link>
    </div>
</template>

<script>

export default {
    name: "UserList",
    data() {
        return {
            users: [],
            token: this.$store.getters.StateToken,
            current_user: this.$store.getters.StateUser,
        }
    },
    created() {
        this.getUserList()
    },
    computed: {
        isAdmin() {
            return this.current_user.customer.role == 1;
        },
    },
    methods: {
        getUserList() {
            axios.get('api/user/list', {params: {token: this.token}})
                .then((response) => {
                    this.users = response.data.data
                })
                .catch(error => {
                    console.log('error', error)
                })
        },
    }
}
</script>

<style lang="sass" scoped>
.flex
    div
        width: 100%
        border: 1px solid #6b7280
        padding: 4px 8px

    .number-column
        width: 25px

.table-title
    div
        font-weight: bold

.row-item
    cursor: pointer

.btn
    padding: 4px 8px
    color: #fff
    background-color: #b7821a
    margin-bottom: 8px
</style>
