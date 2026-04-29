<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { Role } from '@/types/models/User'
import { format } from 'date-fns'
import PermissionViewModal from './PermissionViewModal.vue'


interface Props {
  roles: {
    data: Role[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  search: string
  roleType: string
  roleTypeOptions: Array<{ value: string; label: string }>
}

const props = defineProps<Props>()
const emit = defineEmits<{
  edit: [role: Role]
  delete: [role: Role]
  'apply-filters': []
  'reset-filters': []
  'update:search': [value: string]
  'update:roleType': [value: string]
}>()
const showPermissionsModal = ref(false)
const selectedRoleForPermissions = ref<Role | null>(null)

function handleEdit(role: Role) { emit('edit', role) }
function handleDelete(role: Role) { emit('delete', role) }

function openPermissionsModal(role: Role) {
  selectedRoleForPermissions.value = role
  showPermissionsModal.value = true
}

function closePermissionsModal() {
  showPermissionsModal.value = false
  selectedRoleForPermissions.value = null
}

function formatDate(d: string) {
  try { return format(new Date(d), 'MMM d, yyyy') } catch { return d }
}

function goToPage(page: number) {
  if (page < 1 || page > props.roles.last_page) return
  router.visit(`/roles?page=${page}`, { preserveState: true, preserveScroll: true })
}

const pages = (current: number, last: number) => {
  const p: number[] = []
  for (let i = 1; i <= last; i++) {
    if (Math.abs(i - current) <= 2 || i === 1 || i === last) p.push(i)
  }
  return p
}
</script>

<template>
  <div class="card">
    <!-- Toolbar -->
    <div class="ch">
      <div class="tb" style="margin-left:auto">
        <div class="ts">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input
            :value="search"
            placeholder="Search roles…"
            @input="$emit('update:search', ($event.target as HTMLInputElement).value)"
          />
        </div>
        <select class="tsel" :value="roleType" @change="$emit('update:roleType', ($event.target as HTMLSelectElement).value); $emit('apply-filters')">
          <option v-for="opt in roleTypeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <button class="btn bg2 bsm" @click="$emit('reset-filters')">Reset</button>
      </div>
    </div>

    <!-- Table -->
    <div class="tw">
      <table class="dt">
        <thead>
          <tr>
            <th>Role</th>
            <th>Type</th>
            <th>Permissions</th>
            <th>Created</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in roles.data" :key="role.id">
            <td>
              <div>
                <div class="rnm">{{ role.display_name || role.name }}</div>
                <div class="rsub">{{ role.name }}</div>
              </div>
            </td>
            <td><span class="badge bg-b">{{ role.role_type || 'Admin' }}</span></td>
            <td>
              <button class="pcount" @click="openPermissionsModal(role)">
                View permissions
              </button>
            </td>
            <td style="color:#8a7f72;font-size:.74rem">{{ formatDate(role.created_at) }}</td>
            <td>
              <div class="ar">
                <button class="btn bg2 bxs" @click="handleEdit(role)">Edit</button>
                <button class="btn bd bxs" @click="handleDelete(role)">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!roles.data.length">
            <td colspan="5" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No roles found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="cb" style="padding-top:9px;padding-bottom:9px">
      <div class="pagi">
        <span class="pinfo">{{ (roles.current_page - 1) * roles.per_page + 1 }}–{{ Math.min(roles.current_page * roles.per_page, roles.total) }} of {{ roles.total }}</span>
        <button class="pb2" @click="goToPage(roles.current_page - 1)">‹</button>
        <button
          v-for="page in pages(roles.current_page, roles.last_page)"
          :key="page"
          :class="['pb2', page === roles.current_page ? 'on' : '']"
          @click="goToPage(page)"
        >{{ page }}</button>
        <button class="pb2" @click="goToPage(roles.current_page + 1)">›</button>
      </div>
    </div>
  </div>

  <PermissionViewModal
    :open="showPermissionsModal"
    :role="selectedRoleForPermissions"
    @close="closePermissionsModal"
  />
</template>

<style scoped>
.card{background:#fff;border:1.5px solid #e0d8cc;border-radius:13px;overflow:hidden}
.ch{padding:13px 17px 12px;border-bottom:1px solid #e0d8cc;display:flex;align-items:center;justify-content:space-between;gap:9px;flex-wrap:wrap}
.cb{padding:15px 17px}
.tb{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.ts{display:flex;align-items:center;gap:6px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;padding:6px 10px;width:200px;transition:border-color .16s}
.ts:focus-within{border-color:#e8b96a}
.ts svg{width:12px;height:12px;stroke:#8a7f72;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.ts input{border:none;background:none;font-family:'DM Sans',sans-serif;font-size:.78rem;color:#0e0b07;outline:none;flex:1;min-width:0}
.ts input::placeholder{color:#8a7f72}
select.tsel{padding:6px 9px;font-family:'DM Sans',sans-serif;font-size:.77rem;color:#0e0b07;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;outline:none;cursor:pointer}
select.tsel:focus{border-color:#e8b96a}
.btn{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid;cursor:pointer;transition:all .16s;white-space:nowrap;background:none}
.bp{background:linear-gradient(135deg,#c9893c,#b84b2f)!important;border-color:transparent;color:#fff;box-shadow:0 2px 10px rgba(201,137,60,.22)}
.bg2{background:#f0e8d6!important;border-color:#e0d8cc;color:#8a7f72}
.bg2:hover{border-color:#e8b96a;color:#c9893c}
.bd{background:rgba(184,50,50,.07)!important;border-color:rgba(184,50,50,.22);color:#b83232}
.bsm{padding:4px 10px;font-size:.71rem}
.bxs{padding:3px 8px;font-size:.67rem}
.tw{overflow-x:auto}
.dt{width:100%;border-collapse:collapse}
.dt th{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8a7f72;padding:9px 13px;text-align:left;border-bottom:2px solid #e0d8cc;background:#f0e8d6;white-space:nowrap}
.dt td{padding:10px 13px;border-bottom:1px solid #e0d8cc;vertical-align:middle;font-size:.79rem}
.dt tr:last-child td{border-bottom:none}
.dt tbody tr:hover td{background:rgba(240,232,214,.32)}
.ar{display:flex;gap:5px;align-items:center;justify-content:flex-end}
.badge{display:inline-flex;align-items:center;gap:4px;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:20px;border:1px solid;white-space:nowrap}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.6}
.bg-b{color:#2c5f9e;border-color:rgba(44,95,158,.28);background:rgba(44,95,158,.07)}
.rnm{font-weight:600;font-size:.79rem;color:#0e0b07}
.rsub{font-size:.68rem;color:#8a7f72;margin-top:1px}
.pcount{display:inline-flex;align-items:center;padding:2px 8px;border-radius:7px;font-size:.72rem;font-weight:600;background:#f0e8d6;border:1px solid #e0d8cc;color:#8a7f72;cursor:pointer;transition:all .15s}
.pcount:hover{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
.pagi{display:flex;align-items:center;gap:5px;justify-content:flex-end;padding-top:11px;flex-wrap:wrap}
.pinfo{font-size:.7rem;color:#8a7f72;margin-right:auto}
.pb2{width:29px;height:29px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.73rem;font-weight:600;color:#8a7f72;transition:all .15s}
.pb2:hover,.pb2.on{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
</style>
