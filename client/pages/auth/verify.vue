<template>
    <div class="container mx-auto flex flex-col justify-center items-center min-h-screen">
        <div class="bg-gray-700 py-8 px-8 rounded w-2/3 xl:w-1/2">
            <template v-if="message">
                <span class="flex justify-center text-blue-400 italic">{{ message }}</span>
            </template>
            <template v-else>
                <div class="mb-3 small text-white">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </div>
                <div class="text-green-500" role="alert">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
                <form @submit.prevent="resend">
                    <div class="flex flex-col mt-4">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-gray-50 uppercase tracking-wider hover:bg-gray-300 hover:text-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-blue active:bg-gray-300 transition ease-in-out duration-150">Resend Verification Email</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data:() => ({
            form: {},
            message: '',
            errors: [],
        }),
        methods: {
            async resend() {
                try {
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$axios.$post('/api/email/verification-notification')
                        .then((resp) => {
                            this.message = resp.data.message
                        })
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
