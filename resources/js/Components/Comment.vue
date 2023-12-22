<script setup>
import { relativeDate } from "@/utilities/date";

defineProps(["comment"]);
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
      <p class="mt-1">{{ comment.body }}</p>
      <div class="mt-1 text-xs">
        <span class="text-gray-600 dark:text-gray-400">
          By {{ comment.user.name }}
        </span>
        <span class="text-gray-500 dark:text-gray-600">
          &nbsp;{{ relativeDate(comment.created_at) }} ago
        </span>
      </div>
    </div>
  </div>
</template>
