<script setup lang="ts">
interface Option {
  value: string
  label: string
  sublabel?: string
  small?: boolean // shrink font for longer labels
}

interface Props {
  modelValue: string
  options: Option[]
  columns?: number
}

const props = withDefaults(defineProps<Props>(), {
  columns: 6,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()
</script>

<template>
  <div
    class="gs-grid"
    :style="{ gridTemplateColumns: `repeat(${columns}, 1fr)` }"
  >
    <button
      v-for="opt in options"
      :key="opt.value"
      type="button"
      class="gs-btn"
      :class="{
        'gs-btn--selected': modelValue === opt.value,
        'gs-btn--small': opt.small,
      }"
      @click="emit('update:modelValue', opt.value)"
    >
      {{ opt.label }}
      <span v-if="opt.sublabel" class="gs-btn__sub">{{ opt.sublabel }}</span>
    </button>
  </div>
</template>

<style scoped>
.gs-grid {
  display: grid;
  gap: 8px;
  margin-bottom: 4px;
}

.gs-btn {
  padding: 10px 6px;
  border-radius: 10px;
  cursor: pointer;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
  font-size: 0.8rem;
  font-weight: 500;
  border: 1.5px solid var(--auth-border, #e0d8cc);
  color: var(--auth-muted, #8a7f72);
  background: var(--auth-warm, #f0e8d6);
  text-align: center;
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 2px;
  line-height: 1.2;
}

.gs-btn--small { font-size: 0.7rem; }

.gs-btn__sub {
  display: block;
  font-size: 0.6rem;
  color: var(--auth-muted, #8a7f72);
}

.gs-btn:hover {
  border-color: var(--auth-gold-light, #e8b96a);
  color: var(--auth-gold, #c9893c);
  background: rgba(201, 137, 60, 0.06);
}

.gs-btn--selected {
  border-color: var(--auth-gold, #c9893c);
  background: rgba(201, 137, 60, 0.12);
  color: var(--auth-ink, #0e0b07);
  font-weight: 600;
}

.gs-btn--selected .gs-btn__sub {
  color: var(--auth-gold, #c9893c);
}
</style>
