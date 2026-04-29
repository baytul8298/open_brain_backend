<script setup lang="ts">
defineProps<{ for?: string; value?: string }>();
</script>

<template>
  <label :for="(($props as any).for)" class="block text-sm font-medium text-gray-700 mb-1">
    <slot>{{ value }}</slot>
  </label>
</template>
