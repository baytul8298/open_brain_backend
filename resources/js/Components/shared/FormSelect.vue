<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  label: string
  modelValue: string | number
  options: Array<{ value: string | number; label: string }>
  placeholder?: string
  required?: boolean
  error?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select an option',
  required: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
}>()

const hasValue = computed(() => {
  return props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined
})

function handleChange(event: Event) {
  const target = event.target as HTMLSelectElement
  emit('update:modelValue', target.value)
}
</script>

<template>
  <div class="relative">
    <select
      :value="modelValue"
      @change="handleChange"
      :required="required"
      class="w-full px-3 py-2.5 pt-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-orange-500 transition-colors appearance-none cursor-pointer bg-white peer"
      :class="{ 'border-red-500 focus:border-red-500': error, 'text-gray-400': !hasValue }"
    >
      <option value="" disabled selected>{{ placeholder }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <label
      class="absolute left-3 -top-2.5 bg-white px-1 text-sm font-medium transition-all"
      :class="hasValue ? 'text-orange-500' : 'text-gray-600'"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <!-- Dropdown Arrow -->
    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>
    <p v-if="error" class="mt-1 text-sm text-red-600">
      {{ error }}
    </p>
  </div>
</template>
