<script setup>
import { relativeDate } from "@/utilities/date";
import LikeDislike from "@/Components/LikeDislike.vue";
import { useReaction } from "@/Composables/useReaction";

const props = defineProps(["comment"]);

const emit = defineEmits(["edit", "delete"]);

const {
  like,
  unlike,
  dislike,
  undislike,
  toggleLikeToDislike,
  toggleDislikeToLike,
} = useReaction();
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
            @like="like('comment', comment.id)"
            @unlike="unlike('comment', comment.id)"
            @dislike="dislike('comment', comment.id)"
            @undislike="undislike('comment', comment.id)"
            @toggleLikeToDislike="toggleLikeToDislike('comment', comment.id)"
            @toggleDislikeToLike="toggleDislikeToLike('comment', comment.id)"
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
