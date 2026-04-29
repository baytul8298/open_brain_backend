<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ChevronDown, ChevronRight } from 'lucide-vue-next'
import type { NavigationItem } from '@/config/navigation'

interface Props {
  item: NavigationItem
  active?: boolean
}

const props = defineProps<Props>()

const expanded = ref(false)

// Check if any child is currently active
const hasActiveChild = computed(() => {
  if (!props.item.children) return false
  return props.item.children.some(child => window.location.pathname === child.href)
})

// Auto-expand if a child is active
watch(hasActiveChild, (isActive) => {
  if (isActive) {
    expanded.value = true
  }
}, { immediate: true })

const toggleExpanded = () => {
  if (props.item.children) {
    expanded.value = !expanded.value
  }
}

const isChildActive = (child: NavigationItem) => {
  return window.location.pathname === child.href
}
</script>

<template>
  <div class="relative">
    <!-- Menu Item -->
    <component
      :is="item.href && !item.children ? Link : 'button'"
      :href="item.href"
      @click="toggleExpanded"
      :class="[
        'w-full flex items-center justify-between px-4 py-3 text-sm font-medium transition-colors rounded-lg mx-2',
        active || hasActiveChild
          ? 'text-orange-500 bg-orange-100/50'
          : expanded && item.children
          ? 'text-gray-700 bg-gray-100'
          : 'text-gray-700 hover:bg-gray-50 hover:text-orange-500',
      ]"
    >
      <div class="flex items-center gap-3">
        <component :is="item.icon" v-if="item.icon" class="w-5 h-5" />
        <span>{{ item.name }}</span>
      </div>

      <!-- Chevron for expandable items -->
      <ChevronDown
        v-if="item.children"
        :class="[
          'w-4 h-4 transition-transform',
          expanded ? 'rotate-180' : '',
          hasActiveChild ? 'text-orange-500' : 'text-gray-500'
        ]"
      />
    </component>

    <!-- Submenu -->
    <div
      v-if="item.children && expanded"
      class="mt-1 mb-2"
    >
      <Link
        v-for="child in item.children"
        :key="child.name"
        :href="child.href || '#'"
        :class="[
          'flex items-center gap-3 px-4 py-2.5 pl-12 text-sm transition-colors',
          isChildActive(child)
            ? 'text-gray-800 font-normal'
            : 'text-gray-500 hover:text-gray-700',
        ]"
      >
        <!-- Dot indicator -->
        <span
          :class="[
            'w-1.5 h-1.5 rounded-full flex-shrink-0',
            isChildActive(child) ? 'bg-orange-500' : 'bg-gray-400',
          ]"
        ></span>
        <span>{{ child.name }}</span>
      </Link>
    </div>
  </div>
</template>
