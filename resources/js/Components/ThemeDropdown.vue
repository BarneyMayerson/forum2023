<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import SunSolidIcon from "@/Components/Icons/SunSolidIcon.vue";
import MoonSolidIcon from "@/Components/Icons/MoonSolidIcon.vue";
import DesktopComputerSolidIcon from "@/Components/Icons/DesktopComputerSolidIcon.vue";

defineProps({
  align: {
    type: String,
    default: "right",
  },
  width: {
    type: String,
    default: "32",
  },
});

const theme = ref("system");
const themes = ["light", "dark"];

const matcher = window.matchMedia("(prefers-color-scheme: dark)");
const listener = () => {
  if (theme.value === "system") {
    applySystemTheme();
  }
};

onMounted(() => {
  if (themes.includes(localStorage.appTheme)) {
    theme.value = localStorage.appTheme;
  }

  matcher.addEventListener("change", listener);

  listener();
});

onBeforeUnmount(() => {
  matcher.removeEventListener("change", listener);
});

watch(theme, (themeValue) => {
  if (themeValue === "light") {
    localStorage.appTheme = "light";
    document.documentElement.classList.remove("dark");
  }

  if (themeValue === "dark") {
    localStorage.appTheme = "dark";
    document.documentElement.classList.add("dark");
  }

  if (themeValue === "system") {
    localStorage.removeItem("appTheme");
    applySystemTheme();
  }
});

const toggleLightTheme = () => {
  theme.value = "light";
};

const toggleDarkTheme = () => {
  theme.value = "dark";
};

const toggleSystemTheme = () => {
  theme.value = "system";
};

const applySystemTheme = () => {
  if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    document.documentElement.classList.add("dark");
  } else {
    document.documentElement.classList.remove("dark");
  }
};
</script>

<template>
  <Dropdown :align="align" :width="width">
    <template #trigger>
      <button
        class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out p-2"
      >
        <SunSolidIcon v-if="theme === 'light'" />
        <MoonSolidIcon v-if="theme === 'dark'" />
        <DesktopComputerSolidIcon v-if="theme === 'system'" />
      </button>
    </template>

    <template #content>
      <DropdownLink @click.prevent="toggleLightTheme" as="button">
        <div class="flex items-center">
          <SunSolidIcon />
          <span class="ml-4">Light</span>
        </div>
      </DropdownLink>
      <DropdownLink @click.prevent="toggleDarkTheme" as="button">
        <div class="flex items-center">
          <MoonSolidIcon />
          <span class="ml-4">Dark</span>
        </div>
      </DropdownLink>
      <DropdownLink @click.prevent="toggleSystemTheme" as="button">
        <div class="flex items-center">
          <DesktopComputerSolidIcon />
          <span class="ml-4">System</span>
        </div>
      </DropdownLink>
    </template>
  </Dropdown>
</template>
