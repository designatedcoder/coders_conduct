<template>
    <div class="flex flex-col font-primary min-h-screen">
        <header class="bg-red-800 text-white py-4">
            <nav class="container flex justify-between mx-auto w-3/4">
                <div>Coder's Conduct</div>
                <div class="space-x-5">
                    <template v-if="this.$auth.loggedIn">
                        <span>{{ this.$auth.user.name }}</span>
                        <NuxtLink to="/dashboard">DASHBOARD</NuxtLink>
                        <NuxtLink to="#">POSTS</NuxtLink>
                        <NuxtLink to="#">SETTINGS</NuxtLink>
                        <a href="#" @click.prevent="logout">LOGOUT</a>
                    </template>
                    <template v-else>
                        <NuxtLink to="/auth/register">REGISTER</NuxtLink>
                        <NuxtLink to="/auth/login">LOGIN</NuxtLink>
                    </template>
                </div>
            </nav>
        </header>
        <Nuxt />
    </div>
</template>

<script>
    export default {
        methods: {
            logout() {
                this.$auth.logout()
            }
        }
    }
</script>