<template>
  <CalculationCasesPage />
</template>

<script setup>
import { CalculationCasesPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>