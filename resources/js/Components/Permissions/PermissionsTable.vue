<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { Permission } from '@/types/models/User'
import type { Module } from '@/types/models/Module'
import { format } from 'date-fns'

interface Props {
  permissions: {
    data: Permission[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  guardName: string
  guardOptions: Array<{ value: string; label: string }>
  moduleId: string
  moduleOptions: Array<{ value: string | number; label: string }>
  createdFromDate: Date | null
  createdToDate: Date | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
  edit: [permission: Permission]
  delete: [permission: Permission]
  'apply-filters': []
  'reset-filters': []
  'update:guardName': [value: string]
  'update:moduleId': [value: string | number]
  'update:createdFromDate': [value: Date | null]
  'update:createdToDate': [value: Date | null]
}>()

const search = ref('')

function handleEdit(p: Permission) { emit('edit', p) }
function handleDelete(p: Permission) { emit('delete', p) }

function formatDate(d: string) {
  try { return format(new Date(d), 'MMM d, yyyy') } catch { return d }
}

function goToPage(page: number) {
  if (page < 1 || page > props.permissions.last_page) return
  router.visit(`/permissions?page=${page}`, { preserveState: true, preserveScroll: true })
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
      <div class="tb" style="flex:1">
        <div class="ts">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input v-model="search" placeholder="Permission name…"/>
        </div>
        <select class="tsel" :value="guardName" @change="$emit('update:guardName', ($event.target as HTMLSelectElement).value)">
          <option value="">All Guards</option>
          <option v-for="opt in guardOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <select class="tsel" :value="moduleId" @change="$emit('update:moduleId', ($event.target as HTMLSelectElement).value)">
          <option value="">All Modules</option>
          <option v-for="opt in moduleOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <button class="btn bg2 bsm" @click="$emit('reset-filters')">Reset</button>
        <button class="btn bp bsm" @click="$emit('apply-filters')">Apply</button>
      </div>
    </div>

    <!-- Table -->
    <div class="tw">
      <table class="dt">
        <thead>
          <tr>
            <th>Permission</th>
            <th>Display Name</th>
            <th>Module</th>
            <th>Guard</th>
            <th>Created</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="perm in permissions.data" :key="perm.id">
            <td>
              <div class="pnm">{{ perm.name }}</div>
            </td>
            <td style="color:#4a3f30;font-size:.78rem">{{ perm.display_name || '—' }}</td>
            <td><span class="badge bg-b">{{ perm.module?.module_name || 'General' }}</span></td>
            <td><span class="badge bg-m">{{ perm.guard_name || 'web' }}</span></td>
            <td style="color:#8a7f72;font-size:.74rem">{{ formatDate(perm.created_at) }}</td>
            <td>
              <div class="ar">
                <button class="btn bg2 bxs" @click="handleEdit(perm)">Edit</button>
                <button class="btn bd bxs" @click="handleDelete(perm)">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!permissions.data.length">
            <td colspan="6" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No permissions found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="cb" style="padding-top:9px;padding-bottom:9px">
      <div class="pagi">
        <span class="pinfo">{{ (permissions.current_page - 1) * permissions.per_page + 1 }}–{{ Math.min(permissions.current_page * permissions.per_page, permissions.total) }} of {{ permissions.total }}</span>
        <button class="pb2" @click="goToPage(permissions.current_page - 1)">‹</button>
        <button
          v-for="page in pages(permissions.current_page, permissions.last_page)"
          :key="page"
          :class="['pb2', page === permissions.current_page ? 'on' : '']"
          @click="goToPage(page)"
        >{{ page }}</button>
        <button class="pb2" @click="goToPage(permissions.current_page + 1)">›</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card{background:#fff;border:1.5px solid #e0d8cc;border-radius:13px;overflow:hidden}
.ch{padding:13px 17px 12px;border-bottom:1px solid #e0d8cc;display:flex;align-items:center;justify-content:space-between;gap:9px;flex-wrap:wrap}
.cb{padding:15px 17px}
.tb{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.ts{display:flex;align-items:center;gap:6px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;padding:6px 10px;flex:1;min-width:170px;transition:border-color .16s}
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
.bg-m{color:#8a7f72;border-color:#e0d8cc;background:#f0e8d6}
.pnm{font-weight:600;font-size:.78rem;color:#0e0b07;font-family:'JetBrains Mono',monospace}
.pagi{display:flex;align-items:center;gap:5px;justify-content:flex-end;padding-top:11px;flex-wrap:wrap}
.pinfo{font-size:.7rem;color:#8a7f72;margin-right:auto}
.pb2{width:29px;height:29px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.73rem;font-weight:600;color:#8a7f72;transition:all .15s}
.pb2:hover,.pb2.on{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
</style>
