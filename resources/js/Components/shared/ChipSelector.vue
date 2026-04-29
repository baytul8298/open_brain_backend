<script setup lang="ts">
import type { Component } from 'vue'

interface Option {
  value: string
  label: string
  icon?: Component | string
}

interface Props {
  modelValue: string[]
  options: Option[]
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:modelValue': [value: string[]]
}>()

function toggle(value: string) {
  const next = [...props.modelValue]
  const idx = next.indexOf(value)
  if (idx === -1) next.push(value)
  else next.splice(idx, 1)
  emit('update:modelValue', next)
}
</script>

<template>
  <div class="cs-grid">
    <button
      v-for="opt in options"
      :key="opt.value"
      type="button"
      class="cs-chip"
      :class="{ 'cs-chip--selected': modelValue.includes(opt.value) }"
      @click="toggle(opt.value)"
    >
      <i v-if="opt.icon && typeof opt.icon === 'string'" :class="opt.icon" class="cs-chip__icon"></i>
      <component v-else-if="opt.icon" :is="opt.icon" class="cs-chip__icon" />
      {{ opt.label }}
    </button>
  </div>
</template>

<style scoped>
.cs-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 4px;
}

.cs-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 13px;
  border-radius: 40px;
  cursor: pointer;
  font-size: 0.78rem;
  font-weight: 500;
  border: 1.5px solid var(--auth-border, #e0d8cc);
  color: var(--auth-muted, #8a7f72);
  background: var(--auth-warm, #f0e8d6);
  transition: all 0.2s;
  user-select: none;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
}

.cs-chip__icon {
  width: 12px;
  height: 12px;
  flex-shrink: 0;
}

.cs-chip:hover {
  border-color: var(--auth-gold-light, #e8b96a);
  color: var(--auth-gold, #c9893c);
  background: rgba(201, 137, 60, 0.06);
}

.cs-chip--selected {
  border-color: var(--auth-gold, #c9893c);
  color: var(--auth-ink, #0e0b07);
  background: rgba(201, 137, 60, 0.12);
  font-weight: 600;
}

.cs-chip--selected .cs-chip__icon {
  color: var(--auth-gold, #c9893c);
}
</style>
