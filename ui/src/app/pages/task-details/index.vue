<template>
  <TaskDetailsPage />
</template>

<script setup>
import { TaskDetailsPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>