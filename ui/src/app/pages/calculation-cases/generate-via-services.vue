<template>
  <CalculationCaseGenerationViaServicesPage />
</template>

<script setup>
import { CalculationCaseGenerationViaServicesPage } from "~/src/pages"

definePageMeta({
  middleware: ["auth"],
});
</script>