<template>
    <div id="main">
        <nav-bar></nav-bar>
        <div class="container mt-5">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import NavBar from "./Components/NavBar";

export default {
    components: {
        NavBar
    },
    data() {
        return {}
    },
    methods: {}
}
</script>
<style lang="sass" scoped>

</style>
