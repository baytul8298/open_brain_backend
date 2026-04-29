<script setup lang="ts">
import { useNotificationsStore } from '@/stores/notifications'

const store = useNotificationsStore()

const bgMap: Record<string, string> = {
  success: '#1a7a48',
  error:   '#b83232',
  warning: '#b86b1a',
  info:    '#1a5c9e',
}

const iconMap: Record<string, string> = {
  success: '✓',
  error:   '✕',
  warning: '⚠',
  info:    'ℹ',
}
</script>

<template>
  <div class="toast-wrap">
    <TransitionGroup
      enter-active-class="toast-enter-active"
      enter-from-class="toast-enter-from"
      enter-to-class="toast-enter-to"
      leave-active-class="toast-leave-active"
      leave-from-class="toast-leave-from"
      leave-to-class="toast-leave-to"
    >
      <div
        v-for="n in store.notifications"
        :key="n.id"
        class="toast-pill"
        :style="{ background: bgMap[n.type] }"
        @click="store.remove(n.id)"
      >
        <span class="toast-icon">{{ iconMap[n.type] }}</span>
        <span class="toast-msg">{{ n.message || n.title }}</span>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-wrap {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: flex-end;
  pointer-events: none;
}

.toast-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 9999px;
  color: #fff;
  font-family: 'DM Sans', sans-serif;
  font-size: .84rem;
  font-weight: 500;
  cursor: pointer;
  pointer-events: all;
  box-shadow: 0 4px 16px rgba(0,0,0,.22);
  min-width: 160px;
  white-space: nowrap;
  user-select: none;
}

.toast-icon {
  font-size: .9rem;
  font-weight: 700;
  flex-shrink: 0;
  line-height: 1;
}

.toast-msg {
  line-height: 1.3;
}

/* Slide in from right */
.toast-enter-active  { transition: all .28s cubic-bezier(.22,1,.36,1); }
.toast-enter-from    { opacity: 0; transform: translateX(40px); }
.toast-enter-to      { opacity: 1; transform: translateX(0); }
.toast-leave-active  { transition: all .2s ease-in; }
.toast-leave-from    { opacity: 1; transform: translateX(0); }
.toast-leave-to      { opacity: 0; transform: translateX(40px); }
</style>
