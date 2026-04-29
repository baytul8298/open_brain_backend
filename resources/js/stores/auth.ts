import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User, Role, Permission } from '@/types/models/User'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)

  const isAuthenticated = computed(() => user.value !== null)

  const roles = computed(() => user.value?.roles || [])

  const permissions = computed(() => {
    const rolePermissions = roles.value.flatMap(role => role.permissions || [])
    const userPermissions = user.value?.permissions || []
    return [...rolePermissions, ...userPermissions]
  })

  function setUser(newUser: User | null) {
    user.value = newUser
  }

  function hasRole(roleName: string): boolean {
    return roles.value.some(role => role.name === roleName)
  }

  function hasPermission(permissionName: string): boolean {
    return permissions.value.some(permission => permission.name === permissionName)
  }

  function hasAnyPermission(permissionNames: string[]): boolean {
    return permissionNames.some(name => hasPermission(name))
  }

  function hasAllPermissions(permissionNames: string[]): boolean {
    return permissionNames.every(name => hasPermission(name))
  }

  return {
    user,
    isAuthenticated,
    roles,
    permissions,
    setUser,
    hasRole,
    hasPermission,
    hasAnyPermission,
    hasAllPermissions,
  }
})
