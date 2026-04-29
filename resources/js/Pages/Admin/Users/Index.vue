<script setup lang="ts">
import UserModal from '@/Components/Users/UserModal.vue'
import UsersTable from '@/Components/Users/UsersTable.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import type { Role, User } from '@/types/models/User'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Props {
  users: {
    data: User[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  roles: Role[]
  userTypeOptions: Array<{ value: string; label: string }>
  statusOptions: Array<{ value: string; label: string }>
  languageOptions: Array<{ value: string; label: string }>
  filters: {
    search: string | null
    per_page: number
    user_type: string | null
    status: string | null
    created_from: string | null
    created_to: string | null
  }
}

const props = defineProps<Props>()

const showModal = ref(false)
const modalMode = ref<'create' | 'edit'>('create')
const selectedUser = ref<User | null>(null)
const showDeleteConfirm = ref(false)
const userToDelete = ref<User | null>(null)

// Filter states
const userType = ref(props.filters.user_type || '')
const status = ref(props.filters.status || '')
const createdFromDate = ref<Date | null>(
  props.filters.created_from ? new Date(props.filters.created_from) : null
)
const createdToDate = ref<Date | null>(
  props.filters.created_to ? new Date(props.filters.created_to) : null
)

function openCreateModal() {
  modalMode.value = 'create'
  selectedUser.value = null
  showModal.value = true
}

function openEditModal(user: User) {
  modalMode.value = 'edit'
  selectedUser.value = user
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedUser.value = null
}

function handleDeleteClick(user: User) {
  userToDelete.value = user
  showDeleteConfirm.value = true
}

function confirmDelete() {
  if (userToDelete.value) {
    router.delete(`/users/${userToDelete.value.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        showDeleteConfirm.value = false
        userToDelete.value = null
      },
    })
  }
}

function cancelDelete() {
  showDeleteConfirm.value = false
  userToDelete.value = null
}

function applyFilters() {
  router.get(
    '/users',
    {
      user_type: userType.value || undefined,
      status: status.value || undefined,
      created_from: createdFromDate.value
        ? createdFromDate.value.toISOString().split('T')[0]
        : undefined,
      created_to: createdToDate.value
        ? createdToDate.value.toISOString().split('T')[0]
        : undefined,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}

function resetFilters() {
  userType.value = ''
  status.value = ''
  createdFromDate.value = null
  createdToDate.value = null

  router.get('/users', {}, {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Users" />

    <!-- Hero -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div style="font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.28);margin-bottom:7px;">User Management</div>
          <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;color:#faf6ef;line-height:1.1;margin-bottom:5px;">All <em style="font-style:italic;color:#e8b96a;">Users</em></h1>
          <p style="font-size:.78rem;color:rgba(250,246,239,.36);line-height:1.6;">Search, filter, manage and moderate all platform users</p>
        </div>
        <div style="display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;align-self:flex-start;">
          <div style="padding:11px 17px;border-right:1px solid rgba(255,255,255,.08);text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px;">{{ users.total }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Total</div>
          </div>
          <div style="padding:11px 17px;text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px;">{{ users.last_page }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Pages</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page Body -->
    <div style="padding:20px 26px;display:flex;flex-direction:column;gap:16px;">
      <!-- Action bar -->
      <div style="display:flex;align-items:center;justify-content:flex-end;">
        <button @click="openCreateModal" style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:none;cursor:pointer;background:linear-gradient(135deg,#c9893c,#b84b2f);color:#fff;box-shadow:0 2px 10px rgba(201,137,60,.22);">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add User
        </button>
      </div>

      <!-- Users Table Card -->
      <UsersTable
        :users="users"
        :user-type="userType"
        :user-type-options="userTypeOptions"
        :status="status"
        :status-options="statusOptions"
        :created-from-date="createdFromDate"
        :created-to-date="createdToDate"
        @edit="openEditModal"
        @delete="handleDeleteClick"
        @apply-filters="applyFilters"
        @reset-filters="resetFilters"
        @update:user-type="userType = $event"
        @update:status="status = $event"
        @update:created-from-date="createdFromDate = $event"
        @update:created-to-date="createdToDate = $event"
      />
    </div>

    <!-- User Modal -->
    <UserModal
      :open="showModal"
      :mode="modalMode"
      :user="selectedUser"
      :roles="roles"
      :user-type-options="userTypeOptions"
      :status-options="statusOptions"
      :language-options="languageOptions"
      @close="closeModal"
      @success="closeModal"
    />

    <!-- Delete Confirmation Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="cancelDelete">
          <div style="background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:17px;width:min(560px,93vw);padding:24px;" @click.stop>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.08rem;color:#0e0b07;margin-bottom:8px;">Delete User</h3>
            <p style="font-size:.8rem;color:#8a7f72;margin-bottom:20px;line-height:1.6;">Are you sure you want to delete <strong>{{ userToDelete?.name }}</strong>? This action cannot be undone.</p>
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
