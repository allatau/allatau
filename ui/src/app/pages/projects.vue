<template>
  <ProjectsPage />
</template>

<script setup>
import { ProjectsPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>