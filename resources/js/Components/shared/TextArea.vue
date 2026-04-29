<script setup lang="ts">
import { computed, ref } from 'vue'

interface Props {
  label: string
  modelValue: string
  placeholder?: string
  required?: boolean
  error?: string
  disabled?: boolean
  rows?: number
  maxLength?: number
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '',
  required: false,
  disabled: false,
  rows: 4,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const isFocused = ref(false)
const touched = ref(false)

const hasValue = computed(() => {
  return props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined
})

const isActive = computed(() => {
  return isFocused.value || hasValue.value
})

function handleInput(event: Event) {
  const target = event.target as HTMLTextAreaElement
  emit('update:modelValue', target.value)
}

function handleFocus() {
  touched.value = true
  isFocused.value = true
}

function handleBlur() {
  isFocused.value = false
}
</script>

<template>
  <div class="relative">
    <textarea
      :value="modelValue"
      @input="handleInput"
      @focus="handleFocus"
      @blur="handleBlur"
      :placeholder="isActive ? placeholder : ''"
      :required="required"
      :disabled="disabled"
      :rows="rows"
      :maxlength="maxLength"
      class="w-full px-4 py-3 border rounded-md transition-all duration-200 outline-none bg-white peer resize-none"
      :class="{
        'border-orange-500 border-2': touched && isFocused && !error,
        'border-red-500 border-2': error,
        'border-gray-300': (!touched || !isFocused) && !error,
        'opacity-60 cursor-not-allowed': disabled
      }"
    />
    <label
      class="absolute left-4 transition-all duration-200 pointer-events-none bg-white px-1"
      :class="{
        '-top-2.5 text-xs font-medium': isActive,
        'top-3 text-base': !isActive,
        'text-red-500': error,
        'text-orange-500': touched && isFocused && !error,
        'text-gray-500': (!touched || !isFocused) && !error
      }"
    >
      {{ label }}
      <span v-if="required" class="text-red-500 ml-0.5">*</span>
    </label>
    
    <div v-if="maxLength" class="flex justify-between items-center mt-1">
      <p v-if="error" class="text-sm text-red-500">
        {{ error }}
      </p>
      <p class="text-xs text-gray-500 ml-auto">
        {{ modelValue.length }}/{{ maxLength }}
      </p>
    </div>
    <p v-else-if="error" class="mt-1 text-sm text-red-500">
      {{ error }}
    </p>
  </div>
</template>