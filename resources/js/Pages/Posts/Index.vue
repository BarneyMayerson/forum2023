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
  <AppLayout>
    <Container>
      <ul class="divide-y dark:divide-gray-700">
        <li v-for="post in posts.data" :key="post.id">
          <Link
            :href="route('posts.show', post.id)"
            class="block group px-2 py-3"
          >
            <span
              class="font-semibold text-lg group-hover:text-blue-700 dark:group-hover:text-blue-200"
              >{{ post.title }}</span
            >
            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ formattedDate(post) }} ago by {{ post.user.name }}
            </span>
          </Link>
        </li>
      </ul>

      <Pagination :meta="posts.meta" />
    </Container>
  </AppLayout>
</template>
