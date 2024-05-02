<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  href: {
    required: true,
  },
  filled: {
    default: false,
  },
});
</script>

<template>
  <Link
    :href
    class="whitespace-nowrap text-xs text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white transition-colors rounded-xl py-0.5 px-2"
    :class="{ 'bg-blue-500 text-white': filled }"
  >
    <slot />
  </Link>
</template>
