<script setup>
import Container from "@/Components/Container.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { formatDistance, parseISO } from "date-fns";

const props = defineProps(["post"]);

const formattedDate = computed(() =>
  formatDistance(parseISO(props.post.created_at), new Date())
);
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
    </Container>
  </AppLayout>
</template>
