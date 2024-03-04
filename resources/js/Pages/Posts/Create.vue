<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import axios from "axios";
import { isInProduction } from "@/utilities/environment";

const form = useForm({
  title: "",
  body: "",
});

const createPost = () => form.post(route("posts.store"));

const autofill = async () => {
  if (isInProduction()) {
    return;
  }

  const response = await axios.get("/local/post-content");

  form.title = response.data.title;
  form.body = response.data.body;
};
</script>

<template>
  <AppLayout title="Create a Post">
    <Container>
      <h1 class="text-2xl font-bold">Create a Post</h1>

      <form @submit.prevent="createPost" class="mt-3 space-y-3">
        <div class="">
          <InputLabel for="title" class="sr-only">Title</InputLabel>
          <TextInput
            v-model="form.title"
            id="title"
            class="w-full"
            placeholder="Give it a create title..."
          />
          <InputError :message="form.errors.title" class="mt-1" />
        </div>
        <div class="">
          <InputLabel for="body" class="sr-only">Body</InputLabel>

          <MarkdownEditor v-model="form.body" class="my-6">
            <template #toolbar="{ editor }">
              <li v-if="!isInProduction()">
                <button
                  @click="autofill"
                  type="button"
                  class="px-3 py-2"
                  title="Autofill"
                >
                  <i class="ri-article-line"></i>
                </button>
              </li>
            </template>
          </MarkdownEditor>

          <TextArea v-model="form.body" id="body" rows="20" />
          <InputError :message="form.errors.body" class="mt-1" />
        </div>
        <div class="">
          <PrimaryButton type="submit">Create Post</PrimaryButton>
        </div>
      </form>
    </Container>
  </AppLayout>
</template>
