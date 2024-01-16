<script setup>
import { relativeDate } from "@/utilities/date";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps(["comment"]);

const deleteComment = () =>
  router.delete(route("comments.destroy", props.comment.id), {
    preserveScroll: true,
  });

const canDoAction = computed(
  () => props.comment.user.id === usePage().props.auth.user?.id,
);
</script>

<template>
  <div class="sm:flex">
    <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
      <img
        :src="comment.user.profile_photo_url"
        class="h-10 w-10 rounded-full"
        alt="photo"
      />
    </div>
    <div>
      <p class="mt-1 break-all">{{ comment.body }}</p>
      <div class="mt-1 text-xs">
        <span class="text-gray-600 dark:text-gray-400">
          By {{ comment.user.name }}
        </span>
        <span class="text-gray-500 dark:text-gray-600">
          &nbsp;{{ relativeDate(comment.created_at) }} ago
        </span>

        <div v-if="canDoAction" class="mt-2">
          <form @submit.prevent="deleteComment">
            <button class="rounded-lg border px-3 py-1 text-sm">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
