<template>
  <MicroservicesPage />
</template>

<script setup>
import { MicroservicesPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>