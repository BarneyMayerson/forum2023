<script setup>
import Container from "@/Components/Container.vue";
import Comment from "@/Components/Comment.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { relativeDate } from "@/utilities/date";
import Pagination from "@/Components/Pagination.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router, useForm, Head, Link } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useConfirm } from "@/Composables/useConfirm";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import {
  HandThumbUpIcon,
  HandThumbDownIcon,
} from "@heroicons/vue/20/solid/index";

const props = defineProps(["post", "comments"]);

console.log(props.comments.data.length);

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

const { confirmation } = useConfirm();

const updateComment = async () => {
  if (!(await confirmation("Do you actually want to update this comment?"))) {
    commentTextAreaRef.value?.focus();

    return;
  }

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

const deleteComment = async (commentId) => {
  if (!(await confirmation("Are you sure you want to delete this comment?"))) {
    return;
  }

  router.delete(
    route("comments.destroy", {
      comment: commentId,
      page:
        props.comments.data.length > 1
          ? props.comments.meta.current_page
          : Math.max(props.comments.meta.current_page - 1, 1),
    }),
    {
      preserveScroll: true,
    },
  );
};

const showPagination = computed(() => props.comments.meta.last_page > 1);
</script>

<template>
  <AppLayout :title="post.title">
    <Head>
      <link rel="canonical" :href="post.routes.show" />
    </Head>
    <Container>
      <Pill :href="route('posts.index', { topic: post.topic.slug })">
        {{ post.topic.name }}
      </Pill>
      <PageHeading class="mt-2">{{ post.title }}</PageHeading>
      <span class="block mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ formattedDate }} by {{ post.user.name }}
      </span>
      <div class="mt-4">
        <span class="text-pink-500 font-bold">
          {{ post.likes_count }} likes
        </span>
        <div v-if="$page.props.auth.user" class="mt-2">
          <Link
            v-if="post.can.like"
            :href="route('likes.store', ['post', post.id])"
            method="post"
            class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full"
          >
            <HandThumbUpIcon class="size-4" />
          </Link>

          <Link
            v-else
            :href="route('likes.destroy', ['post', post.id])"
            method="delete"
            class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full"
          >
            <HandThumbDownIcon class="size-4" />
          </Link>
        </div>
      </div>
      <article
        class="mt-6 prose prose-sky dark:prose-invert max-w-none"
        v-html="post.html"
      />
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
            <MarkdownEditor
              v-model="commentForm.body"
              ref="commentTextAreaRef"
              id="body"
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
        <ul class="divide-y dark:divide-gray-600 mt-4">
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
          <Pagination
            v-if="showPagination"
            :meta="comments.meta"
            :only="['comments']"
          />
        </ul>
      </div>
    </Container>
  </AppLayout>
</template>
