<template>
    <div class="container mx-auto flex flex-col justify-center items-center min-h-screen">
        <div class="bg-gray-700 py-8 px-8 rounded w-2/3 xl:w-1/2">
            <template v-if="message">
                <span class="flex justify-center text-blue-400 italic">{{ message }}</span>
            </template>
            <template v-else>
                <div class="text-white mb-2">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </div>

                <form @submit.prevent="submit">
                    <div class="flex flex-col mt-4">
                        <label class="text-gray-50">Email</label>
                        <input type="email" v-model="form.email" class="px-2 py-1 rounded" placeholder="Email">
                    </div>
                    <span class="flex justify-center text-red-400 italic mt-4" v-if="errors.email">{{ errors.email[0] }}</span>
                    <div class="flex flex-col mt-4">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-gray-50 uppercase tracking-wider hover:bg-gray-300 hover:text-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-blue active:bg-gray-300 transition ease-in-out duration-150">Submit</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            form: {
                email: ''
            },
            errors: [],
            message: ''
        }),
        methods: {
            async submit() {
                try {
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$axios.$post('/api/forgot-password', this.form)
                    .then((resp) => {
                        this.message = resp.message
                    })
                } catch (err) {
                    this.errors = err.response.data.errors
                }
            }
        }
    }
</script>
