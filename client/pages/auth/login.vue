<template>
    <div class="container mx-auto flex flex-col justify-center items-center min-h-screen">
        <div class="bg-gray-700 py-8 px-8 rounded w-2/3 xl:w-1/2">

            <template v-if="verification">
                <div class="text-white mb-3">
                    <template v-if="!recovery">
                    Please confirm access to your account by entering the authentication code provided by your authenticator application.
                    </template>

                    <template v-else>
                    Please confirm access to your account by entering one of your emergency recovery codes.
                    </template>
                </div>

                <span class="flex justify-center text-red-400 italic" v-if="errors.code">
                    {{ errors.code[0] }}
                </span>
                <form @submit.prevent="verify">

                    <div class="flex flex-col mt-4" v-if="!recovery">
                        <label class="text-gray-50">QR Code</label>
                        <input ref="code" id="code" type="text" inputmode="numeric" v-model="recForm.code" autofocus autocomplete="one-time-code" class="px-2 py-1 rounded">
                    </div>

                    <div class="flex flex-col mt-4" v-else>
                        <label class="text-gray-50">Recovery Code</label>
                        <input ref="recovery_code" id="recovery_code" type="text" v-model="recForm.recovery_code" autocomplete="one-time-code" class="px-2 py-1 rounded">
                    </div>

                    <div class="flex flex-col justify-center mt-4">
                        <button type="button" class="text-white border rounded uppercase tracking-wider leading-tight my-4 px-4 py-2" :class="!recovery ? 'bg-green-500 hover:bg-green-800' : 'bg-blue-500 hover:bg-blue-800'" @click.prevent="toggleRecovery">
                            <template v-if="!recovery">
                                Use a recovery code
                            </template>

                            <template v-else>
                                Use an authentication code
                            </template>
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-gray-50 uppercase tracking-wider hover:bg-gray-300 hover:text-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-blue active:bg-gray-300 transition ease-in-out duration-150">Login</button>
                    </div>
                </form>
            </template>

            <template v-else>
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
            </template>

        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            recovery: false,
            verification: false,
            form: {
                email: '',
                password: '',
            },
            recForm: {
                code: '',
                recovery_code: '',
            },
            errors: []
        }),
        methods: {
            toggleRecovery() {
                this.recovery ^= true
                this.$nextTick(() => {
                    if (this.recovery) {
                        this.$refs.recovery_code.focus()
                        this.recForm.code = ''
                    } else {
                        this.$refs.code.focus()
                        this.recForm.recovery_code = ''
                    }
                })
            },
            async verify() {
                try {
                    await this.$axios.$post('/api/two-factor-challenge', this.recForm)
                    this.submit()
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            },
            async submit() {
                try {
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$axios.$post('/api/login', this.form)
                        .then((resp) => {
                            if (!resp.two_factor) {
                                this.$auth.loginWith('laravelSanctum', {data: this.form})
                            } else {
                                this.verification = true
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors
                        })
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
