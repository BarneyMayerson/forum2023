<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import { Link } from "@inertiajs/vue3";
import { relativeDate } from "@/utilities/date";

defineProps(["posts", "topics", "selectedTopic"]);

const formattedDate = (post) => relativeDate(post.created_at);
</script>

<template>
  <AppLayout title="Posts">
    <Container>
      <div class="mb-4">
        <PageHeading
          v-text="selectedTopic ? selectedTopic.name : 'All Posts'"
        />
        <p v-if="selectedTopic" class="italic text-sm">
          {{ selectedTopic.description }}
        </p>

        <menu
          class="flex items-center space-x-1 mt-2 overflow-x-auto pb-2 pt-1"
        >
          <li>
            <Pill :href="route('posts.index')" :filled="!selectedTopic">
              All Posts
            </Pill>
          </li>
          <li v-for="topic in topics" :key="topic.id">
            <Pill
              :href="route('posts.index', { topic: topic.slug })"
              :filled="topic.id === selectedTopic?.id"
            >
              {{ topic.name }}
            </Pill>
          </li>
        </menu>
      </div>
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
          <Pill :href="route('posts.index', { topic: post.topic.slug })">
            {{ post.topic.name }}
          </Pill>
        </li>
      </ul>

      <Pagination :meta="posts.meta" />
    </Container>
  </AppLayout>
</template>
