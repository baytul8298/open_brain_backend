<script setup lang="ts">
defineProps<{ disabled?: boolean }>();
</script>

<template>
  <button
    v-bind="$attrs"
    :disabled="disabled"
    class="btn-base btn-primary inline-flex items-center justify-center px-4 py-2 text-base"
    :class="{ 'opacity-60 cursor-not-allowed': disabled }"
  >
    <slot />
  </button>
</template>
