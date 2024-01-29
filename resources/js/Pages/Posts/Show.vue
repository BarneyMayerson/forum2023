<script setup>
import Container from "@/Components/Container.vue";
import Comment from "@/Components/Comment.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { relativeDate } from "@/utilities/date";
import Pagination from "@/Components/Pagination.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { router } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps(["post", "comments"]);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
  body: "",
});

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);
const commentBeingEdited = computed(() =>
  props.comments.data.find(
    (comment) => comment.id === commentIdBeingEdited.value,
  ),
);

const editComment = (commentId) => {
  commentIdBeingEdited.value = commentId;
  commentForm.body = commentBeingEdited.value?.body;
  commentTextAreaRef.value?.focus();
};

const cancelEditComment = () => {
  commentIdBeingEdited.value = null;
  commentForm.reset();
};

const addComment = () =>
  commentForm.post(route("posts.comments.store", props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
  });

const updateComment = () => {
  commentForm.put(
    route("comments.update", {
      comment: commentIdBeingEdited.value,
      page: props.comments.meta.current_page,
    }),
    {
      preserveScroll: true,
      onSuccess: cancelEditComment,
    },
  );
};

const deleteComment = (commentId) => {
  router.delete(
    route("comments.destroy", {
      comment: commentId,
      page: props.comments.meta.current_page,
    }),
    {
      preserveScroll: true,
    },
  );
};
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
        <form
          v-if="$page.props.auth.user"
          @submit.prevent="
            () => (commentIdBeingEdited ? updateComment() : addComment())
          "
          class="mt-3"
        >
          <div>
            <InputLabel for="body" class="sr-only">Comment</InputLabel>
            <TextArea
              v-model="commentForm.body"
              ref="commentTextAreaRef"
              id="body"
              rows="4"
              placeholder="Enter comment here..."
            />
            <InputError :message="commentForm.errors.body" class="mt-1" />
          </div>
          <PrimaryButton
            type="submit"
            class="mt-3"
            :disabled="commentForm.processing"
            v-text="commentIdBeingEdited ? 'Update comment' : 'Add comment'"
          >
          </PrimaryButton>
          <SecondaryButton
            v-if="commentIdBeingEdited"
            @click="cancelEditComment"
            class="ml-2"
          >
            Cancel
          </SecondaryButton>
        </form>
        <ul class="divide-y mt-4">
          <li
            v-for="comment in comments.data"
            :key="comment.id"
            class="px-2 py-4"
          >
            <Comment
              :comment="comment"
              @delete="deleteComment"
              @edit="editComment"
            />
          </li>
          <Pagination :meta="comments.meta" :only="['comments']" />
        </ul>
      </div>
    </Container>
  </AppLayout>
</template>
