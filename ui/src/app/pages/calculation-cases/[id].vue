<template>
    <CalculationCasePage />
</template>

<script setup>
import { CalculationCasePage } from "~/src/pages/calculation-case-page"

definePageMeta({
    middleware: ["auth"],
});
</script>