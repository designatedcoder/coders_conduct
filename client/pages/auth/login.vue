<template>
    <div class="container mx-auto flex flex-col justify-center items-center min-h-screen">
        <div class="bg-gray-700 py-8 px-8 rounded w-2/3 xl:w-1/2">

            <span class="text-red-400 italic" v-if="errors.email">{{ errors.email[0] }}</span>
            <h1 class="text-white text-3xl text-center tracking-wider uppercase underline italic">Login</h1>
            <form @submit.prevent="submit">
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Email</label>
                    <input type="email" v-model="form.email" class="px-2 py-1 rounded" placeholder="Email">
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Password</label>
                    <input type="password" v-model="form.password" class="px-2 py-1 rounded" placeholder="Password">
                </div>
                <div class="flex flex-col mt-4">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-gray-50 uppercase tracking-wider hover:bg-gray-300 hover:text-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-blue active:bg-gray-300 transition ease-in-out duration-150">Submit</button>
                </div>
                <div class="flex justify-around mt-4">
                    <NuxtLink to="/auth/forgotPassword" class="text-white hover:text-blue-300">Forgot Password?</NuxtLink>
                    <NuxtLink to="/auth/register" class="text-white hover:text-blue-300">Need an account?</NuxtLink>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            form: {
                email: '',
                password: '',
            },
            errors: []
        }),
        methods: {
            async submit() {
                try {
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$auth.loginWith('laravelSanctum', {data: this.form})
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
