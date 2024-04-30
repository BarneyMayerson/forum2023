<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/vue3";
import { relativeDate } from "@/utilities/date";

defineProps(["posts"]);

const formattedDate = (post) => relativeDate(post.created_at);
</script>

<template>
  <AppLayout title="Posts">
    <Container>
      <ul class="divide-y dark:divide-gray-700">
        <li
          v-for="post in posts.data"
          :key="post.id"
          class="flex justify-between items-baseline flex-col md:flex-row mb-2 md:mb-0"
        >
          <Link :href="post.routes.show" class="block group px-2 py-3">
            <span
              class="font-semibold text-lg group-hover:text-blue-700 dark:group-hover:text-blue-200"
              >{{ post.title }}</span
            >
            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ formattedDate(post) }} ago by {{ post.user.name }}
            </span>
          </Link>
          <Link
            :href="route('posts.index', { topic: post.topic.slug })"
            class="text-xs text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white transition-colors rounded-xl py-0.5 px-2"
            >{{ post.topic.name }}</Link
          >
        </li>
      </ul>

      <Pagination :meta="posts.meta" />
    </Container>
  </AppLayout>
</template>
