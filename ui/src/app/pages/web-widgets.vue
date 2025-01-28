<template>
  <WebWidgetsPage />
</template>

<script setup>
import { WebWidgetsPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>