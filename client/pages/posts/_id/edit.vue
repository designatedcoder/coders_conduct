<template>
	<div class="my-auto">
		<main class="container mx-auto">
			<form @submit.prevent="updatePost" class="bg-red-800 rounded w-3/4 mx-auto px-6 py-6 space-y-6">
                <span class="flex justify-center text-red-300 italic" v-if="errors.auth">{{ errors.auth }}</span>
				<div class="flex flex-col">
					<label class="text-lg text-white font-semibold capitalize">Title</label>
					<input type="text" class="border rounded px-2 py-2" v-model="post.title" placeholder="Title" />
                    <span class="text-red-300 italic" v-if="errors.title">{{ errors.title[0] }}</span>
				</div>
				<div class="flex flex-col">
					<label class="text-lg text-white font-semibold capitalize">Body</label>
					<textarea cols="30" rows="10" class="border rounded px-2 py-2" v-model="post.body" placeholder="Body"></textarea>
                    <span class="text-red-300 italic" v-if="errors.body">{{ errors.body[0] }}</span>
				</div>
				<div class="flex justify-between">
					<NuxtLink to="/posts" class=" bg-red-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-red-800">
						Cancel
					</NuxtLink>
					<button type="submit" class=" bg-blue-500 text-white border rounded uppercase px-2 py-1 tracking-wider leading-tight hover:bg-blue-800">
						Submit
					</button>
				</div>
			</form>
		</main>
	</div>
</template>

<script>
    export default {
        layout: 'appLayout',
        middleware: ['auth', 'verified'],
        data: () => ({
            post: {},
            errors: []
        }),
        mounted() {
            this.getPost()
        },
        methods: {
            async getPost() {
                try {
                    await this.$axios.$get('/api/posts/'+this.$route.params.id)
                        .then((resp) => {
                            this.post = resp.data
                        })
                } catch (error) {
                    console.log(error);
                }
            },
            async updatePost() {
                try {
                    await this.$axios.$patch('/api/posts/'+this.$route.params.id, this.post)
                    this.$router.replace({ name: 'posts'})
                } catch (error) {
                    this.errors = error.response.data.errors
                }
            }
        }
    }
</script>
