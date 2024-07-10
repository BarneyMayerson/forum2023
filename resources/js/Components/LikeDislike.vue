<script setup>
import { ref } from "vue";

const props = defineProps({
  likesCount: {
    type: String,
    required: true,
  },
  dislikesCount: {
    type: String,
    required: true,
  },
  reaction: {
    type: Object,
    required: true,
  },
  sizeMini: {
    type: Boolean,
    default: false,
  },
});

const iconSizeClass = props.sizeMini ? "size-3" : "size-5";

const emit = defineEmits([
  "toggleReaction",
  "createReaction",
  "deleteReaction",
]);

const hasReaction = ref(props.reaction.exists);
const isLiked = ref(props.reaction.is_like);

const handleExistReaction = (isLikePushed) => {
  if (isLiked.value === true && isLikePushed) {
    hasReaction.value = false;
    emit("deleteReaction", 1);

    return;
  }

  if (isLiked.value === false && isLikePushed) {
    isLiked.value = true;

    emit("toggleReaction", 1);
    return;
  }

  if (isLiked.value === true && !isLikePushed) {
    isLiked.value = false;
    emit("toggleReaction", 0);

    return;
  }

  if (isLiked.value === false && !isLikePushed) {
    hasReaction.value = false;
    emit("deleteReaction", 0);

    return;
  }
};

const createLike = () => {
  hasReaction.value = true;
  isLiked.value = true;
  emit("createReaction", 1);
};

const createDislike = () => {
  hasReaction.value = true;
  isLiked.value = false;
  emit("createReaction", 0);
};
</script>

<template>
  <div
    v-if="hasReaction"
    class="inline-flex"
    :class="sizeMini ? 'space-x-6' : 'space-x-8'"
  >
    <div class="flex items-center">
      <button
        :title="isLiked ? 'Unlike' : 'I like this'"
        @click="handleExistReaction(true)"
      >
        <svg
          :class="`${iconSizeClass} hover:text-blue-500`"
          :fill="isLiked ? 'currentColor' : 'none'"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
          />
        </svg>
      </button>
      <span class="ml-3" :class="sizeMini ? 'text-xs' : 'text-sm'">
        {{ likesCount }}
      </span>
    </div>
    <span v-if="!sizeMini" class="border-l-2" aria-hidden></span>
    <div class="flex items-center">
      <button
        :title="isLiked ? 'I dislike this' : 'Undislike'"
        @click="handleExistReaction(false)"
      >
        <svg
          :class="`${iconSizeClass} hover:text-blue-500`"
          :fill="!isLiked ? 'currentColor' : 'none'"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"
          />
        </svg>
      </button>
      <span class="ml-3" :class="sizeMini ? 'text-xs' : 'text-sm'">
        {{ dislikesCount }}
      </span>
    </div>
  </div>

  <div v-else class="inline-flex" :class="sizeMini ? 'space-x-6' : 'space-x-8'">
    <div class="flex items-center">
      <button title="I like this" @click="createLike">
        <svg
          :class="`${iconSizeClass} hover:text-blue-500`"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
          />
        </svg>
      </button>
      <span class="ml-3" :class="sizeMini ? 'text-xs' : 'text-sm'">
        {{ likesCount }}
      </span>
    </div>
    <span v-if="!sizeMini" class="border-l-2" aria-hidden></span>
    <div class="flex items-center">
      <button title="I dislike this" @click="createDislike">
        <svg
          :class="`${iconSizeClass} hover:text-blue-500`"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"
          />
        </svg>
      </button>
      <span class="ml-3" :class="sizeMini ? 'text-xs' : 'text-sm'">
        {{ dislikesCount }}
      </span>
    </div>
  </div>
</template>
