<script setup>
import { nextTick, ref, watchEffect } from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useConfirm } from "@/Composables/useConfirm";

const { state, confirm, cancel } = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
  if (state.show) {
    await nextTick();

    cancelButtonRef.value?.$el.focus();
  }
});
</script>

<template>
  <ConfirmationModal :show="state.show">
    <template #title>{{ state.title }}</template>

    <template #content>{{ state.message }}</template>

    <template #footer>
      <SecondaryButton ref="cancelButtonRef" @click="cancel">
        Cancel
      </SecondaryButton>
      <PrimaryButton @click="confirm" class="ml-4">Confirm</PrimaryButton>
    </template>
  </ConfirmationModal>
</template>
