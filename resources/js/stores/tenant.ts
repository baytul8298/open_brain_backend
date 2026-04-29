import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Tenant } from '@/types/models/Tenant'

export const useTenantStore = defineStore('tenant', () => {
  const currentTenant = ref<Tenant | null>(null)
  const availableTenants = ref<Tenant[]>([])

  const hasTenant = computed(() => currentTenant.value !== null)

  function setCurrentTenant(tenant: Tenant | null) {
    currentTenant.value = tenant
  }

  function setAvailableTenants(tenants: Tenant[]) {
    availableTenants.value = tenants
  }

  async function switchTenant(tenantId: number) {
    // Implementation will make API call to switch tenant
    // Update currentTenant after successful switch
  }

  return {
    currentTenant,
    availableTenants,
    hasTenant,
    setCurrentTenant,
    setAvailableTenants,
    switchTenant,
  }
})
