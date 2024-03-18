<script setup>
import { EditorContent, useEditor } from "@tiptap/vue-3";
import { StarterKit } from "@tiptap/starter-kit";
import { Link } from "@tiptap/extension-link";
import { watch } from "vue";
import { Markdown } from "tiptap-markdown";
import { Placeholder } from "@tiptap/extension-placeholder";
import "remixicon/fonts/remixicon.css";

const props = defineProps({
  modelValue: "",
  tall: Boolean,
  placeholder: null,
});

const emit = defineEmits(["update:modelValue"]);

const editor = useEditor({
  extensions: [
    StarterKit.configure({
      heading: {
        levels: [2, 3, 4],
      },
      code: false,
      codeBlock: false,
    }),
    Link,
    Markdown,
    Placeholder.configure({
      placeholder: props.placeholder,
    }),
  ],

  editorProps: {
    attributes: {
      class: `bg-white dark:bg-gray-900 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 prose dark:prose-invert max-w-none rounded-b-md focus:outline-none focus:ring-2 focus:ring-indigo-600 dark:focus:ring-indigo-600 py-1.5 px-3 ${props.tall ? "min-h-[512px]" : "min-h-[192px]"}`,
    },
  },

  onUpdate: () =>
    emit("update:modelValue", editor.value?.storage.markdown.getMarkdown()),
});

defineExpose({ focus: () => editor.value.commands.focus() });

watch(
  () => props.modelValue,
  (value) => {
    if (value === editor.value?.storage.markdown.getMarkdown()) {
      return;
    }

    editor.value?.commands.setContent(value);
  },
  { immediate: true },
);

const promptUserForHref = () => {
  if (editor.value?.isActive("link")) {
    return editor.value?.chain().unsetLink().run();
  }

  const href = prompt("Where do you want to link to?");

  if (!href) {
    return editor.value?.chain().focus().run();
  }

  return editor.value?.chain().focus().setLink({ href }).run();
};
</script>

<template>
  <div
    v-if="editor"
    class="bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-md shadow-sm border-0 ring-1 ring-inset ring-gray-300 dark:ring-gray-700"
  >
    <menu class="flex divide-x divide-gray-300 dark:divide-gray-700">
      <li>
        <button
          @click="() => editor.chain().focus().toggleBold().run()"
          type="button"
          class="px-3 py-2 rounded-tl-md"
          :class="[
            editor.isActive('bold')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Bold"
        >
          <i class="ri-bold"></i>
        </button>
      </li>
      <li>
        <button
          @click="() => editor.chain().focus().toggleItalic().run()"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('italic')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Italic"
        >
          <i class="ri-italic"></i>
        </button>
      </li>
      <li>
        <button
          @click="() => editor.chain().focus().toggleStrike().run()"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('strike')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Strikethrough"
        >
          <i class="ri-strikethrough"></i>
        </button>
      </li>
      <li>
        <button
          @click="() => editor.chain().focus().toggleBlockquote().run()"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('blockquote')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Blockquote"
        >
          <i class="ri-double-quotes-l"></i>
        </button>
      </li>
      <li>
        <button
          @click="() => editor.chain().focus().toggleBulletList().run()"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('bulletList')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Bullet list"
        >
          <i class="ri-list-unordered"></i>
        </button>
      </li>
      <li>
        <button
          @click="() => editor.chain().focus().toggleOrderedList().run()"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('orderedList')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Numeric list"
        >
          <i class="ri-list-ordered"></i>
        </button>
      </li>
      <li>
        <button
          @click="promptUserForHref"
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('link')
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Add link"
        >
          <i class="ri-link"></i>
        </button>
      </li>
      <li>
        <button
          @click="
            () => editor.chain().focus().toggleHeading({ level: 2 }).run()
          "
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('heading', { level: 2 })
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Heading 1"
        >
          <i class="ri-h-1"></i>
        </button>
      </li>
      <li>
        <button
          @click="
            () => editor.chain().focus().toggleHeading({ level: 3 }).run()
          "
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('heading', { level: 3 })
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Heading 2"
        >
          <i class="ri-h-2"></i>
        </button>
      </li>
      <li>
        <button
          @click="
            () => editor.chain().focus().toggleHeading({ level: 4 }).run()
          "
          type="button"
          class="px-3 py-2"
          :class="[
            editor.isActive('heading', { level: 4 })
              ? 'bg-indigo-500'
              : 'hover:bg-gray-200 dark:hover:bg-gray-600',
          ]"
          title="Heading 3"
        >
          <i class="ri-h-3"></i>
        </button>
      </li>

      <slot name="toolbar" :editor="editor" />
    </menu>
    <EditorContent :editor="editor" />
  </div>
</template>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
  @apply text-gray-400 float-left h-0 pointer-events-none;
  content: attr(data-placeholder);
}
</style>
