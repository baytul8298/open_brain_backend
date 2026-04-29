import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Notification {
  id: string
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
  duration?: number
  timestamp: Date
}

export const useNotificationsStore = defineStore('notifications', () => {
  const notifications = ref<Notification[]>([])

  function add(notification: Omit<Notification, 'id' | 'timestamp'>) {
    const id = Math.random().toString(36).substring(7)
    const newNotification: Notification = {
      ...notification,
      id,
      timestamp: new Date(),
    }

    notifications.value.push(newNotification)

    if (notification.duration !== 0) {
      setTimeout(() => {
        remove(id)
      }, notification.duration || 5000)
    }
  }

  function remove(id: string) {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }

  function clear() {
    notifications.value = []
  }

  return {
    notifications,
    add,
    remove,
    clear,
  }
})
