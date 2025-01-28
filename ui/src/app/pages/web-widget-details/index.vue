<template>
  <WebWidgetDetailsPage />
</template>

<script setup>
import { WebWidgetDetailsPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>