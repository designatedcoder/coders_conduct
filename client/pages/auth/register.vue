<template>
    <div class="container mx-auto flex flex-col justify-center items-center min-h-screen">
        <div class="bg-gray-700 py-8 px-8 rounded w-2/3 xl:w-1/2">
            <h1 class="text-white text-3xl text-center tracking-wider uppercase underline italic">Register</h1>
            <form @submit.prevent="submit">
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Name</label>
                    <input type="text" v-model="form.name" class="px-2 py-1 rounded" placeholder="Name" autofocus>
                    <span class="text-red-400 italic" v-if="errors.name">{{ errors.name[0] }}</span>
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Email</label>
                    <input type="email" v-model="form.email" class="px-2 py-1 rounded" placeholder="Email">
                    <span class="text-red-400 italic" v-if="errors.email">{{ errors.email[0] }}</span>
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Password</label>
                    <input type="password" v-model="form.password" class="px-2 py-1 rounded" placeholder="Password">
                    <span class="text-red-400 italic" v-if="errors.password">{{ errors.password[0] }}</span>
                </div>
                <div class="flex flex-col mt-4">
                    <label class="text-gray-50">Confirm your password</label>
                    <input type="password" v-model="form.password_confirmation" class="px-2 py-1 rounded" placeholder="Password Confirmation">
                </div>
                <div class="flex flex-col mt-4">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-gray-50 uppercase tracking-wider hover:bg-gray-300 hover:text-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-blue active:bg-gray-300 transition ease-in-out duration-150">Submit</button>
                </div>
                <div class="flex justify-center mt-4">
                    <NuxtLink to="/auth/login" class="text-white hover:text-blue-300">Already have an account?</NuxtLink>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: []
        }),
        methods: {
            async submit() {
                try {
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$axios.$post('/api/register', this.form)
                    await this.$auth.loginWith('laravelSanctum', {data: this.form})
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
