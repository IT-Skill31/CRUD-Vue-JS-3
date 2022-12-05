<template>
    <div>
        <h1>Bievenue</h1> <h3>{{ name }}</h3> 
    </div>
    <div>
        <a class="btn btn-success" type="button" @click="this.$router.push('/novalager')">Listes des produits</a>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    data() {
        return {
            name: null,
        }
    },
    created() {
        if (window.Laravel.user) {
            this.name = window.Laravel.user.name
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>