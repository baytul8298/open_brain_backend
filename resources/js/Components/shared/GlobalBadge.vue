<script setup lang="ts">
interface Props {
  color?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'pink' | 'gray' | 'orange' | 'cyan' | 'teal'
  size?: 'xs' | 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  color: 'blue',
  size: 'sm',
})

const colorClasses = {
  blue: 'bg-blue-50 text-blue-600 border-blue-200',
  green: 'bg-green-50 text-green-600 border-green-200',
  red: 'bg-red-50 text-red-600 border-red-200',
  yellow: 'bg-yellow-50 text-yellow-600 border-yellow-200',
  purple: 'bg-purple-50 text-purple-600 border-purple-200',
  pink: 'bg-pink-50 text-pink-600 border-pink-200',
  gray: 'bg-gray-50 text-gray-600 border-gray-200',
  orange: 'bg-orange-50 text-orange-600 border-orange-200',
  cyan: 'bg-cyan-50 text-cyan-600 border-cyan-200',
  teal: 'bg-teal-50 text-teal-600 border-teal-200',
}

const sizeClasses = {
  xs: 'px-2 py-1 text-xs gap-1.5',
  sm: 'px-3 py-1.5 text-sm gap-2',
  md: 'px-3 py-2 text-sm gap-2',
  lg: 'px-4 py-2.5 text-base gap-2.5',
}
</script>

<template>
  <span
    class="inline-flex items-center font-medium border rounded"
    :class="[colorClasses[color], sizeClasses[size]]"
  >
    <slot name="icon" />
    <slot />
  </span>
</template>