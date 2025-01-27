<template>
  <ProjectDetailsPage />
</template>

<script setup>
import { ProjectDetailsPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>