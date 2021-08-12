<template>

    <div class="flex flex-col justify-center items-center mx-auto w-3/4 min-h-screen">
        <div class="bg-gray-800">
            <div>
                <div class="bg-gray-700 py-8 px-8 rounded">

                    <div class="flex flex-col items-center space-y-4">
                        <h3 class="text-white text-lg font-semibold">
                            Two Factor Authentication:
                        </h3>
                        <p class="text-white">Add additional security to your account using two factor authentication.</p>
                        <div>
                            <h3 class="text-green-400 font-weight-bold" v-if="twoFactorEnabled">
                                You have enabled two factor authentication.
                            </h3>

                            <h3 class="text-green-400 font-weight-bold" v-else>
                                You have not enabled two factor authentication.
                            </h3>
                        </div>
                        <div class="mt-3">
                            <p class="text-white">
                            When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="flex justify-between space-x-6 mt-4 w-3/4" v-if="twoFactorEnabled">
            <div class="flex-1" v-if="qrCode">
                <div class="mt-3">
                    <p>
                    Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                    </p>
                </div>
                <div v-html="qrCode"></div>
            </div>
            <div class="flex-1" v-if="recCodes.length > 0">
                <div class="mt-3">
                    <p>
                        Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                    </p>
                </div>
                <div class="w-75 bg-gray-200 rounded p-3">
                    <div v-for="(code, index) in recCodes" :key="index">
                        {{ code }}
                    </div>
                </div>
            </div>
        </div>
        <template v-if="!twoFactorEnabled">
            <form @submit.prevent="enable">
                <button type="submit" class="bg-green-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight mt-4 hover:bg-green-800">enable</button>
            </form>
        </template>
        <template v-else>
            <div class="flex justify-center space-x-6 mt-4 w-3/4">
                <button type="button" class="bg-green-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight mt-4 hover:bg-green-800" v-if="recCodes.length > 0" @click.prevent="regenerateRecoveryCodes">Regenerate Recovery Codes</button>
                <button type="button" class="bg-green-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight mt-4 hover:bg-green-800" v-if="recCodes.length === 0" @click.prevent="showRecoveryCodes">Show recovery codes</button>
                <form @submit.prevent="disable">
                    <button type="submit" class="bg-red-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight mt-4 hover:bg-red-800">Disable</button>
                </form>
            </div>
        </template>

        <div class="flex justify-center items-center w-full min-h-screen absolute" :class="openConfirm ? 'bg-black bg-opacity-50' : ''" v-if="openConfirm">
            <div class="bg-blue-700 py-8 px-8 rounded w-2/3 h-1/4">

            <span class="flex justify-center text-red-400 italic mb-4" v-if="errors.password">{{ errors.password[0] }}</span>
                <form @submit.prevent="confirmPassword" class="flex flex-col h-full justify-between">
                    <div class="flex flex-col" v-if="message">
                        <label class="text-gray-50">{{ message }}</label>
                        <input type="password" v-model="form.password" class="mt-4 px-2 py-1 rounded" placeholder="Please confirm your password">
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" class="bg-red-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-red-800" @click.prevent="openConfirm = !openConfirm">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-green-800">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        layout: 'appLayout',
        data:()=> ({
            openConfirm: false,
            isEnabled: false,
            isConfirmed: false,
            user: {},
            qrCode: '',
            recCodes: [],
            form: {
                password: ''
            },
            errors: [],
            message: '',
        }),
        computed: {
            twoFactorEnabled() {
                return this.isEnabled || this.user.two_factor_secret
            }
        },
        mounted() {
            this.confirmStatus()
            this.getUser()
        },
        methods: {
            async enable() {
                try {
                    await this.$axios.$post('/api/user/two-factor-authentication')
                    this.isEnabled = true
                    this.showQrCode()
                    this.showRecoveryCodes()
                } catch (error) {
                    if(error.response.data.message) {
                        this.message = error.response.data.message
                        this.openConfirm = true
                    }
                }
            },
            async disable() {
                try {
                    await this.$axios.$delete('/api/user/two-factor-authentication')
                    this.isEnabled = false
                } catch (error) {
                    if(error.response.data.message) {
                        this.message = error.response.data.message
                        this.openConfirm = true
                    }
                }
            },
            async confirmPassword() {
                try {
                    await this.$axios.$post('/api/user/confirm-password', this.form)
                    if (this.isEnabled && this.recCodes.length === 0) {
                        this.showRecoveryCodes()
                    } else if(this.isEnabled == false) {
                        this.enable()
                    } else {
                        this.disable()
                    }
                    this.openConfirm = false
                } catch (error) {
                    this.errors =  error.response.data.errors
                }
            },
            async confirmStatus() {
                try {
                    await this.$axios.$get('/api/user/confirmed-password-status')
                        .then((resp) => {
                            this.isConfirmed = resp.confirmed
                        })
                } catch (error) {
                    console.log(error)
                }
            },
            async getUser() {
                try {
                    await this.$axios.$get('/api/user')
                        .then((resp) => {
                            if(resp.two_factor_secret) {
                                this.isEnabled = true
                            }
                        })
                } catch (error) {
                    console.log(error)
                }
            },
            async showQrCode() {
                try {
                    await this.$axios.$get('/api/user/two-factor-qr-code')
                        .then((resp) => {
                            this.qrCode = resp.svg
                        })
                } catch (error) {
                    console.log(error)
                }
            },
            async showRecoveryCodes() {
                try {
                    await this.$axios.$get('/api/user/two-factor-recovery-codes')
                        .then((resp) => {
                            this.recCodes = resp
                        })
                } catch (error) {
                    if(error.response.data.message) {
                        this.message = error.response.data.message
                        this.openConfirm = true
                    }
                }
            },
            async regenerateRecoveryCodes() {
                try {
                    await this.$axios.$post('/api/user/two-factor-recovery-codes')
                        .then(() => {
                            this.showRecoveryCodes()
                        })
                } catch (err) {
                    console.log(err);
                }
            },
        }
    }
</script>
