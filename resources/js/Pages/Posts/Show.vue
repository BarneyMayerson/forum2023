<script setup>
import Container from "@/Components/Container.vue";
import Comment from "@/Components/Comment.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { relativeDate } from "@/utilities/date";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps(["post", "comments"]);

const formattedDate = computed(() => relativeDate(props.post.created_at));
</script>

<template>
  <AppLayout :title="post.title">
    <Container>
      <h1 class="text-2xl font-bold">{{ post.title }}</h1>
      <span class="block mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ formattedDate }} ago by {{ post.user.name }}
      </span>
      <article class="mt-8">
        <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
      </article>
      <div class="mt-10">
        <h2 class="text-xl font-bold">Comments</h2>
        <ul class="divide-y mt-4">
          <li
            v-for="comment in comments.data"
            :key="comment.id"
            class="px-2 py-4"
          >
            <Comment :comment="comment" />
          </li>
          <Pagination :meta="comments.meta" />
        </ul>
      </div>
    </Container>
  </AppLayout>
</template>
