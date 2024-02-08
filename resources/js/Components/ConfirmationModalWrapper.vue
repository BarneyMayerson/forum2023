<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useConfirm } from "@/Composables/useConfirm";

const { state, confirm, cancel } = useConfirm();
</script>

<template>
  <ConfirmationModal :show="state.show">
    <template #title>{{ state.title }}</template>

    <template #content>{{ state.message }}</template>

    <template #footer>
      <SecondaryButton @click="cancel">Cancel</SecondaryButton>
      <PrimaryButton @click="confirm" class="ml-4">Confirm</PrimaryButton>
    </template>
  </ConfirmationModal>
</template>
