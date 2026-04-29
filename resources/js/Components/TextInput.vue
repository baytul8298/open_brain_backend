<script setup lang="ts">
import { ref, onMounted } from 'vue';

defineProps<{
  modelValue?: string;
  type?: string;
  required?: boolean;
  autofocus?: boolean;
  autocomplete?: string;
}>();

defineEmits<{ 'update:modelValue': [value: string] }>();

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
  if (input.value?.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
  <input
    ref="input"
    v-bind="$attrs"
    :type="type ?? 'text'"
    :value="modelValue"
    :required="required"
    :autocomplete="autocomplete"
    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
    @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
  />
</template>
