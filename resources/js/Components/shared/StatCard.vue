<script setup lang="ts">
import type { Component } from 'vue'

interface Props {
  title: string
  value: string | number
  icon?: Component
  iconColor?: string
  iconBg?: string
}

const props = withDefaults(defineProps<Props>(), {
  iconColor: 'text-orange-500',
  iconBg: 'bg-orange-100',
})
</script>

<template>
  <div class="bg-white rounded-lg p-6 border border-gray-200 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <p class="text-sm text-gray-600 font-medium">{{ title }}</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ value }}</p>
      </div>
      <div
        v-if="icon"
        :class="['w-12 h-12 rounded-lg flex items-center justify-center', iconBg]"
      >
        <component :is="icon" :class="['w-6 h-6', iconColor]" />
      </div>
    </div>
  </div>
</template>
