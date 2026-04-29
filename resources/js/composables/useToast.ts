import { useNotificationsStore } from '@/stores/notifications'

export function useToast() {
  const notificationsStore = useNotificationsStore()

  const toast = {
    success(title: string, message: string, duration?: number) {
      notificationsStore.add({ type: 'success', title, message, duration })
    },

    error(title: string, message: string, duration?: number) {
      notificationsStore.add({ type: 'error', title, message, duration })
    },

    warning(title: string, message: string, duration?: number) {
      notificationsStore.add({ type: 'warning', title, message, duration })
    },

    info(title: string, message: string, duration?: number) {
      notificationsStore.add({ type: 'info', title, message, duration })
    },
  }

  return toast
}
