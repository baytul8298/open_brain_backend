<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  modelValue: boolean | string[] | number[]
  value?: string | number
  label?: string
  disabled?: boolean
  indeterminate?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  indeterminate: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean | string[] | number[]]
}>()

const isChecked = computed(() => {
  if (Array.isArray(props.modelValue)) {
    return props.value !== undefined && props.modelValue.includes(props.value)
  }
  return props.modelValue === true
})

function handleChange(event: Event) {
  const target = event.target as HTMLInputElement

  if (Array.isArray(props.modelValue) && props.value !== undefined) {
    const newValue = [...props.modelValue]
    if (target.checked) {
      newValue.push(props.value)
    } else {
      const index = newValue.indexOf(props.value)
      if (index > -1) {
        newValue.splice(index, 1)
      }
    }
    emit('update:modelValue', newValue)
  } else {
    emit('update:modelValue', target.checked)
  }
}
</script>

<template>
  <label
    class="inline-flex items-center gap-2 cursor-pointer"
    :class="{ 'opacity-60 cursor-not-allowed': disabled }"
  >
    <input
      type="checkbox"
      :checked="isChecked"
      :value="value"
      :disabled="disabled"
      :indeterminate="indeterminate"
      @change="handleChange"
      class="checkbox-base"
    />
    <span v-if="label" class="text-sm text-gray-700 select-none">
      {{ label }}
    </span>
  </label>
</template>

<style scoped>
/* Custom checkbox styling for indeterminate state */
input[type="checkbox"]:indeterminate {
  background-color: var(--color-primary);
  border-color: var(--color-primary);
}

input[type="checkbox"]:indeterminate::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 2px;
  background-color: white;
}
</style>
