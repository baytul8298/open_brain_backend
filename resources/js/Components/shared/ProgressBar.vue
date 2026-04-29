<script setup lang="ts">
interface Props {
  show?: boolean
  color?: 'primary' | 'success' | 'warning' | 'error'
  height?: string
}

withDefaults(defineProps<Props>(), {
  show: false,
  color: 'primary',
  height: '4px'
})

const colorClasses = {
  primary: 'bg-primary',
  success: 'bg-green-500',
  warning: 'bg-yellow-500',
  error: 'bg-red-500'
}
</script>

<template>
  <Transition
    enter-active-class="transition-opacity duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-300"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      class="w-full overflow-hidden bg-gray-200"
      :style="{ height }"
    >
      <div
        class="h-full progress-bar-animation"
        :class="colorClasses[color]"
      ></div>
    </div>
  </Transition>
</template>

<style scoped>
.progress-bar-animation {
  animation: progress 1.5s ease-in-out infinite;
  transform-origin: left;
}

@keyframes progress {
  0% {
    transform: scaleX(0);
    transform-origin: left;
  }
  50% {
    transform: scaleX(0.6);
    transform-origin: left;
  }
  100% {
    transform: scaleX(1);
    transform-origin: left;
  }
}
</style>
