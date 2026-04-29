<script setup lang="ts">
defineProps<{ label: string }>()
</script>

<template>
  <div class="sl-root">{{ label }}</div>
</template>

<style scoped>
.sl-root {
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--auth-muted, #8a7f72);
  margin: 24px 0 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
}

.sl-root::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--auth-border, #e0d8cc);
}
</style>
