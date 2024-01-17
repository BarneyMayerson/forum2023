<script setup>
import { relativeDate } from "@/utilities/date";

const props = defineProps(["comment"]);

const emit = defineEmits(["delete"]);
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

        <div v-if="comment.can?.delete" class="mt-2">
          <form @submit.prevent="$emit('delete', comment.id)">
            <button class="rounded-lg border px-3 py-1 text-sm">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
