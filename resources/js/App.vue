<template>
    <div class="container">
        <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">
            <div class="navbar-nav" v-if="isLoggedIn"></div>
            <router-link to="/admin" v-if="isLoggedIn" class="navbar-brand"><img src="/logo.jpg" style="height:70px;width:100px;"/></router-link>
            <router-link to="/" v-if="!isLoggedIn" class="navbar-brand"><img src="/logo.jpg" style="height:70px;width:100px;"/></router-link>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation"></button>
            <div class="navbar-nav" v-if="isLoggedIn">
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
            </div>
            <!-- <div class="navbar-nav" v-else>
                <router-link to="/" class="nav-item nav-link">Home</router-link>
                <router-link to="/login" class="nav-item nav-link">Login</router-link>
                <router-link to="/register" class="nav-item nav-link">Register</router-link>
            </div> -->
        </nav>
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        name: "App",
        data() {
            return {
                isLoggedIn: false,
            }
        },
        created() {
            if (window.Laravel.isLoggedin) {
                this.isLoggedIn = true
            }
        },
        methods: {
            logout(e) {
                e.preventDefault()
                this.$axios.post('/api/logout')
                .then(response => {
                    if(response.data.success) {
                        this.$router.push({ name: 'login'})
                    } else {
                        console.log(response);
                    }
                })
                .catch((err) => {
                    console.error(err.response.data.message);
                });
            }
        },
    }
</script>