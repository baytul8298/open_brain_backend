import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUIStore = defineStore('ui', () => {
  const sidebarOpen = ref(true)
  const sidebarCollapsed = ref(false)
  const modalStack = ref<string[]>([])

  function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value
  }

  function collapseSidebar() {
    sidebarCollapsed.value = !sidebarCollapsed.value
  }

  function openModal(modalId: string) {
    modalStack.value.push(modalId)
  }

  function closeModal(modalId?: string) {
    if (modalId) {
      modalStack.value = modalStack.value.filter(id => id !== modalId)
    } else {
      modalStack.value.pop()
    }
  }

  function isModalOpen(modalId: string): boolean {
    return modalStack.value.includes(modalId)
  }

  return {
    sidebarOpen,
    sidebarCollapsed,
    modalStack,
    toggleSidebar,
    collapseSidebar,
    openModal,
    closeModal,
    isModalOpen,
  }
})
