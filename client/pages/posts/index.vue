<template>
    <div>
        <main class="container mx-auto my-6 space-y-6 w-3/4">
            <div class="flex justify-between">
                <NuxtLink to="/posts/create" class="bg-blue-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-blue-800">Create Post</NuxtLink>
                <span class="text-red-500 italic" v-if="errors.auth">{{ errors.auth }}</span>
            </div>

            <div v-for="(post, index) in posts" :key="index" class="flex justify-between items-center border px-3 py-3">
                <div class="w-3/4">
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold capitalize">{{ post.title }}</span>
                        <div>
                            <span class="font-semibold italic"> Posted by {{ post.user.name }}: </span>
                            <span>{{ post.created_at }}</span>
                        </div>
                    </div>
                    <p>{{ post.body }}</p>
                </div>

                <div class="flex justify-end w-1/4">
                    <NuxtLink :to="`/posts/${post.id}/edit`" class="bg-green-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-green-800">edit</NuxtLink>
                    <form @submit.prevent="deletePost(post.id)">
                        <button type="submit" class="bg-red-500 text-white border rounded uppercase ml-3 px-2 py-1 tracking-wider leading-tight hover:bg-red-800">delete</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    export default {
        layout: 'appLayout',
        middleware: ['auth', 'verified'],
        data:() => ({
            posts: [],
            errors: [],
        }),
        mounted() {
            this.getPosts()
        },
        methods: {
            async getPosts() {
                try {
                    await this.$axios.$get('/api/posts')
                        .then((resp) => {
                            this.posts = resp.data
                        })
                } catch (error) {
                    console.log(error);
                }
            },
            async deletePost(id) {
                try {
                    await this.$axios.$delete('/api/posts/'+id)
                    this.getPosts()
                    this.errors = []
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
