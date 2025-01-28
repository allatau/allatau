<template>
  <NuxtLayout name="auth">
    <AuthPage />
  </NuxtLayout>
</template>

<script setup>
import { AuthPage } from "~/src/pages"

definePageMeta({
  layout: false,
  middleware: ['auth'],
});

</script>