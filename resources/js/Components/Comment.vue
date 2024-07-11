<script setup>
import { router } from "@inertiajs/vue3";
import { relativeDate } from "@/utilities/date";
import LikeDislike from "@/Components/LikeDislike.vue";

const props = defineProps(["comment"]);

const emit = defineEmits(["edit", "delete"]);

const createReaction = (value) => {
  router.post(
    route("reactions.store", {
      type: "comment",
      id: props.comment.id,
      is_like: value,
    }),
    null,
    {
      preserveScroll: true,
    },
  );
};

const deleteReaction = (value) => {
  router.delete(
    route("reactions.destroy", {
      type: "comment",
      id: props.comment.id,
      is_like: value,
    }),
    {
      preserveScroll: true,
    },
  );
};

const toggleReaction = (value) => {
  router.patch(
    route("reactions.update", {
      type: "comment",
      id: props.comment.id,
      is_like: value,
    }),
    null,
    {
      preserveScroll: true,
    },
  );
};
</script>

<template>
  <div class="sm:flex">
    <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
      <img
        :src="comment.user.profile_photo_url"
        class="h-10 w-10 rounded-full object-cover object-center"
        alt="photo"
      />
    </div>
    <div class="flex-1">
      <div
        class="prose dark:prose-invert max-w-none"
        v-html="comment.html"
      ></div>
      <div class="flex justify-between items-start">
        <div>
          <div class="mt-1 text-xs">
            <span class="text-gray-600 dark:text-gray-400">
              By {{ comment.user.name }}
            </span>
            <span class="text-gray-500">
              &nbsp;{{ relativeDate(comment.created_at) }}
            </span>
          </div>
          <LikeDislike
            sizeMini
            :likesCount="comment.likes_count"
            :dislikesCount="comment.dislikes_count"
            :reaction="comment.reaction"
            @createReaction="createReaction"
            @deleteReaction="deleteReaction"
            @toggleReaction="toggleReaction"
          />
        </div>

        <div class="flex justify-end space-x-3 empty:hidden">
          <form
            v-if="comment.can?.update"
            @submit.prevent="$emit('edit', comment.id)"
          >
            <button class="font-mono text-xs hover:font-bold">Edit</button>
          </form>
          <form
            v-if="comment.can?.delete"
            @submit.prevent="$emit('delete', comment.id)"
          >
            <button class="font-mono text-xs hover:font-bold text-red-500">
              Delete
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
