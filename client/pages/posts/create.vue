<template>
    <div class="my-auto">
        <main class="container mx-auto">
            <form @submit.prevent="submit" class="bg-red-800 rounded w-3/4 mx-auto px-6 py-6 space-y-6">
                <div class="flex flex-col">
                    <label class="text-lg text-white font-semibold capitalize">Title</label>
                    <input type="text" class="border rounded px-2 py-2" v-model="form.title" placeholder="Title">
                    <span class="text-red-300 italic" v-if="errors.title">{{ errors.title[0] }}</span>
                </div>
                <div class="flex flex-col">
                    <label class="text-lg text-white font-semibold capitalize">Body</label>
                    <textarea cols="30" rows="10" class="border rounded px-2 py-2" v-model="form.body" placeholder="Body"></textarea>
                    <span class="text-red-300 italic" v-if="errors.body">{{ errors.body[0] }}</span>
                </div>
                <div class="flex justify-between">
                    <NuxtLink to="/posts" class="bg-red-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-red-800">Cancel</NuxtLink>
                    <button type="submit" class="bg-blue-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-blue-800">Submit</button>
                </div>
            </form>
        </main>
    </div>
</template>

<script>
    export default {
        layout: 'appLayout',
        middleware: ['auth', 'verified'],
        data:() => ({
            form: {
                title: '',
                body: '',
            },
            errors: []
        }),
        methods: {
            async submit() {
                try {
                    await this.$axios.$post('/api/posts', this.form)
                    this.form.title == ''
                    this.form.body == ''
                    this.$router.replace({ name: 'posts'})
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
