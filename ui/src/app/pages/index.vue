<template>
  <TasksPage />
</template>

<script setup>
import { TasksPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>