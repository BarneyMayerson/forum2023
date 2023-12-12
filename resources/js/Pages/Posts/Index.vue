<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";

defineProps(["posts"]);
</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y dark:divide-gray-700">
                <li v-for="post in posts" :key="post.id" class="px-2 py-3">
                    <span class="font-semibold text-lg">{{ post.title }}</span>
                </li>
            </ul>
        </Container>
    </AppLayout>
</template>
