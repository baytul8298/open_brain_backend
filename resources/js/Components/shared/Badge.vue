<script setup lang="ts">
import { FwbBadge } from 'flowbite-vue'
import { AlertTriangle, Check, Info, X } from 'lucide-vue-next'

interface Props {
  variant?: 'success' | 'error' | 'warning' | 'info' | 'default'
  icon?: boolean
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  icon: false,
  size: 'md',
})

const flowbiteTypeMap = {
  success: 'green',
  error: 'red',
  warning: 'yellow',
  info: 'indigo',
  default: 'dark',
}

const flowbiteSizeMap = {
  sm: 'xs',
  md: 'sm',
  lg: 'sm',
}

const iconComponents = {
  success: Check,
  error: X,
  warning: AlertTriangle,
  info: Info,
  default: null,
}

const iconSize = {
  sm: 12,
  md: 14,
  lg: 16,
}
</script>

<template>
  <fwb-badge 
    :type="flowbiteTypeMap[variant]" 
    :size="flowbiteSizeMap[size]"
    class="inline-flex items-center gap-1"
  >
    <component
      v-if="icon && iconComponents[variant]"
      :is="iconComponents[variant]"
      :size="iconSize[size]"
    />
    <slot />
  </fwb-badge>
</template>