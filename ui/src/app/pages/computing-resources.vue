<template>
  <ComputingResourcesPage />
</template>

<script setup>
import { ComputingResourcesPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>