<template>
    <div class="flex flex-col justify-between min-h-screen">
        <div class="flex-1 flex flex-col justify-center items-start space-y-4 ml-10">
            <h1 class="text-white text-3xl">
                Welcome to Coder's Conduct
            </h1>
            <div class="flex space-x-12">
                <NuxtLink to="/auth/register" class="text-white text-2xl hover:text-blue-500">
                    REGISTER
                </NuxtLink>
                <NuxtLink to="/auth/login" class="text-white text-2xl hover:text-blue-500">
                    LOGIN
                </NuxtLink>
            </div>
        </div>
        <div class="flex-1 flex items-end mb-10 ml-10">
            <p class="text-white">
                Photo by <a href="https://unsplash.com/@romulusprince?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" class="text-blue-500 hover:text-white">Wajih Ghali</a> on <a href="https://unsplash.com/@romulusprince?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" class="text-blue-500 hover:text-white">Unsplash</a>
            </p>
        </div>
    </div>
</template>
