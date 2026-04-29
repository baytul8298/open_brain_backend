<script setup lang="ts">
import PermissionModal from '@/Components/Permissions/PermissionModal.vue'
import PermissionsTable from '@/Components/Permissions/PermissionsTable.vue'
import { Button } from '@/Components/shared'
import AppLayout from '@/Layouts/AppLayout.vue'
import type { Module } from '@/types/models/Module'
import type { Permission } from '@/types/models/User'
import { Head, router } from '@inertiajs/vue3'
import { Plus } from 'lucide-vue-next'
import { computed, onMounted, ref, watch } from 'vue'

interface Props {
  permissions: {
    data: Permission[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  filters: {
    search: string | null
    per_page: number
    guard_name?: string | null
    module_id?: string | null
    created_from?: string | null
    created_to?: string | null
  }
}

const props = defineProps<Props>()

const showModal = ref(false)
const modalMode = ref<'create' | 'edit'>('create')
const selectedPermission = ref<Permission | null>(null)
const searchQuery = ref(props.filters.search || '')
const showDeleteConfirm = ref(false)
const permissionToDelete = ref<Permission | null>(null)
const showFilters = ref(false)
const perPage = ref(props.filters.per_page || 15)

// Filter states
const guardName = ref(props.filters.guard_name || '')
const moduleId = ref(props.filters.module_id || '')
const createdFrom = ref(props.filters.created_from || '')
const createdTo = ref(props.filters.created_to || '')
const modules = ref<Module[]>([])

const guardOptions = [
  { value: '', label: 'All Guards' },
  { value: 'web', label: 'Web' },
  { value: 'api', label: 'API' },
]

const moduleOptions = computed(() => {
  return [
    { value: '', label: 'All Modules' },
    ...modules.value.map(module => ({
      value: module.id,
      label: module.module_name
    }))
  ]
})

const createdFromDate = ref<Date | null>(createdFrom.value ? new Date(createdFrom.value) : null)
const createdToDate = ref<Date | null>(createdTo.value ? new Date(createdTo.value) : null)

watch(createdFromDate, (newDate) => {
  createdFrom.value = newDate ? newDate.toISOString().split('T')[0] : ''
})

watch(createdToDate, (newDate) => {
  createdTo.value = newDate ? newDate.toISOString().split('T')[0] : ''
})

// Debounced search
let searchTimeout: NodeJS.Timeout
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    handleSearch()
  }, 500)
}

watch(searchQuery, () => {
  debouncedSearch()
})

watch(perPage, () => {
  handleSearch()
})

function openCreateModal() {
  modalMode.value = 'create'
  selectedPermission.value = null
  showModal.value = true
}

function openEditModal(permission: Permission) {
  modalMode.value = 'edit'
  selectedPermission.value = permission
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedPermission.value = null
}

function handleSearch() {
  const params = new URLSearchParams()
  if (searchQuery.value) {
    params.append('search', searchQuery.value)
  }
  params.append('per_page', perPage.value.toString())

  // Add filters
  if (guardName.value) {
    params.append('guard_name', guardName.value)
  }
  if (moduleId.value) {
    params.append('module_id', moduleId.value.toString())
  }
  if (createdFrom.value) {
    params.append('created_from', createdFrom.value)
  }
  if (createdTo.value) {
    params.append('created_to', createdTo.value)
  }

  const url = `/permissions${params.toString() ? '?' + params.toString() : ''}`
  router.visit(url, {
    preserveState: true,
    preserveScroll: true,
  })
}

function clearSearch() {
  searchQuery.value = ''
}

function applyFilters() {
  handleSearch()
  showFilters.value = false
}

function resetFilters() {
  guardName.value = ''
  moduleId.value = ''
  createdFrom.value = ''
  createdTo.value = ''
  createdFromDate.value = null
  createdToDate.value = null
  handleSearch()
  showFilters.value = false
}

function handleDeleteClick(permission: Permission) {
  permissionToDelete.value = permission
  showDeleteConfirm.value = true
}

function confirmDelete() {
  if (permissionToDelete.value) {
    router.delete(`/permissions/${permissionToDelete.value.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteConfirm.value = false
        permissionToDelete.value = null
      },
    })
  }
}

function cancelDelete() {
  showDeleteConfirm.value = false
  permissionToDelete.value = null
}

async function fetchModules() {
  try {
    const response = await fetch('/modules/all')
    const data = await response.json()
    modules.value = data.data
  } catch (error) {
    console.error('Failed to fetch modules:', error)
  }
}

// Fetch modules on component mount
onMounted(() => {
  fetchModules()
})
</script>

<template>
  <AppLayout>
    <Head title="Permissions" />

    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div style="font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.28);margin-bottom:7px;">System · Security</div>
          <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;color:#faf6ef;line-height:1.1;margin-bottom:5px;">Permissions &amp; <em style="font-style:italic;color:#e8b96a;">Access</em></h1>
          <p style="font-size:.78rem;color:rgba(250,246,239,.36);line-height:1.6;">Manage system permissions and access controls for all modules</p>
        </div>
        <div style="display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;align-self:flex-start;">
          <div style="padding:11px 17px;border-right:1px solid rgba(255,255,255,.08);text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px;">{{ permissions.total }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Total</div>
          </div>
          <div style="padding:11px 17px;text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px;">{{ permissions.current_page }}/{{ permissions.last_page }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Page</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page Body -->
    <div style="padding:20px 26px;display:flex;flex-direction:column;gap:16px;">
      <div style="display:flex;align-items:center;justify-content:flex-end;">
        <button @click="openCreateModal" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:none;cursor:pointer;background:linear-gradient(135deg,#c9893c,#b84b2f);color:#fff;box-shadow:0 2px 10px rgba(201,137,60,.22);">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Create Permission
        </button>
      </div>

      <!-- Permissions Table -->
      <PermissionsTable
        :permissions="permissions"
        :guard-name="guardName"
        :guard-options="guardOptions"
        :module-id="moduleId"
        :module-options="moduleOptions"
        :created-from-date="createdFromDate"
        :created-to-date="createdToDate"
        @edit="openEditModal"
        @delete="handleDeleteClick"
        @apply-filters="applyFilters"
        @reset-filters="resetFilters"
        @update:guard-name="guardName = $event"
        @update:module-id="moduleId = $event"
        @update:created-from-date="createdFromDate = $event"
        @update:created-to-date="createdToDate = $event"
      />
    </div>

    <!-- Permission Modal -->
    <PermissionModal
      :open="showModal"
      :mode="modalMode"
      :permission="selectedPermission"
      :modules="modules"
      @close="closeModal"
      @success="closeModal"
    />

    <!-- Delete Confirmation Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="cancelDelete">
          <div style="background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:17px;width:min(560px,93vw);padding:24px;" @click.stop>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.08rem;color:#0e0b07;margin-bottom:8px;">Delete Permission</h3>
            <p style="font-size:.8rem;color:#8a7f72;margin-bottom:20px;line-height:1.6;">Are you sure you want to delete <strong>{{ permissionToDelete?.name }}</strong>? This action cannot be undone.</p>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
              <button @click="cancelDelete" style="padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid #e0d8cc;background:#f0e8d6;color:#8a7f72;cursor:pointer;">Cancel</button>
              <button @click="confirmDelete" style="padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid rgba(184,50,50,.22);background:rgba(184,50,50,.07);color:#b83232;cursor:pointer;">Delete</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </AppLayout>
</template>
