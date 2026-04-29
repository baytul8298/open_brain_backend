<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { User } from '@/types/models/User'
import { format } from 'date-fns'

interface Props {
  users: {
    data: User[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  userType: string
  userTypeOptions: Array<{ value: string; label: string }>
  status: string
  statusOptions: Array<{ value: string; label: string }>
  createdFromDate: Date | null
  createdToDate: Date | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
  edit: [user: User]
  delete: [user: User]
  'apply-filters': []
  'reset-filters': []
  'update:userType': [value: string]
  'update:status': [value: string]
  'update:createdFromDate': [value: Date | null]
  'update:createdToDate': [value: Date | null]
}>()

const search = ref('')

function handleEdit(user: User) { emit('edit', user) }
function handleDelete(user: User) { emit('delete', user) }

function formatDate(d: string | null | undefined) {
  if (!d) return '—'
  try { return format(new Date(d), 'MMM d, yyyy') } catch { return String(d) }
}

function getInitials(name: string | null | undefined) {
  if (!name) return '?'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
}

const colors = ['#1a5c3a','#3a3a5c','#5c1a1a','#5c3a1a','#1a3a5c','#3a1a5c','#1a4a2a','#4a3a1a','#1a3a4a','#4a1a3a']
function getAvatarColor(name: string | null | undefined) {
  if (!name) return colors[0]
  let sum = 0
  for (const c of name) sum += c.charCodeAt(0)
  return colors[sum % colors.length]
}

function goToPage(page: number) {
  if (page < 1 || page > props.users.last_page) return
  router.visit(`/users?page=${page}`, { preserveState: true, preserveScroll: true })
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
          <input v-model="search" placeholder="Name, email, username…"/>
        </div>
        <select class="tsel" :value="userType" @change="$emit('update:userType', ($event.target as HTMLSelectElement).value)">
          <option value="">All Types</option>
          <option v-for="opt in userTypeOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <select class="tsel" :value="status" @change="$emit('update:status', ($event.target as HTMLSelectElement).value)">
          <option value="">All Status</option>
          <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
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
            <th>User</th>
            <th>Type</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id">
            <td>
              <div class="uc">
                <div class="uav" :style="`background:linear-gradient(135deg,${getAvatarColor(user.name)},${getAvatarColor(user.name)}88)`">
                  {{ getInitials(user.name) }}
                </div>
                <div>
                  <div class="unm">{{ user.name }}</div>
                  <div class="uem">{{ user.email }}</div>
                </div>
              </div>
            </td>
            <td><span class="badge bg-b">{{ user.user_type || 'N/A' }}</span></td>
            <td>
              <span v-if="user.roles && user.roles.length" class="badge bg-p">{{ user.roles[0].display_name || user.roles[0].name }}</span>
              <span v-else class="badge bg-m">No role</span>
            </td>
            <td>
              <span :class="['badge', user.status ? 'bg-g' : 'bg-r']">{{ user.status ? 'Active' : 'Inactive' }}</span>
            </td>
            <td style="color:#8a7f72;font-size:.74rem">{{ formatDate(user.created_at) }}</td>
            <td>
              <div class="ar">
                <button class="btn bg2 bxs" @click="handleEdit(user)">Edit</button>
                <button class="btn bd bxs" @click="handleDelete(user)">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!users.data.length">
            <td colspan="6" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No users found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="cb" style="padding-top:9px;padding-bottom:9px">
      <div class="pagi">
        <span class="pinfo">{{ (users.current_page - 1) * users.per_page + 1 }}–{{ Math.min(users.current_page * users.per_page, users.total) }} of {{ users.total }}</span>
        <button class="pb2" @click="goToPage(users.current_page - 1)">‹</button>
        <button
          v-for="page in pages(users.current_page, users.last_page)"
          :key="page"
          :class="['pb2', page === users.current_page ? 'on' : '']"
          @click="goToPage(page)"
        >{{ page }}</button>
        <button class="pb2" @click="goToPage(users.current_page + 1)">›</button>
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
.bg-g{color:#2d8a5e;border-color:rgba(45,138,94,.28);background:rgba(45,138,94,.07)}
.bg-r{color:#b83232;border-color:rgba(184,50,50,.28);background:rgba(184,50,50,.07)}
.bg-b{color:#2c5f9e;border-color:rgba(44,95,158,.28);background:rgba(44,95,158,.07)}
.bg-p{color:#6b4aaf;border-color:rgba(107,74,175,.28);background:rgba(107,74,175,.07)}
.bg-m{color:#8a7f72;border-color:#e0d8cc;background:#f0e8d6}
.uc{display:flex;align-items:center;gap:8px}
.uav{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:.67rem;color:#fff;flex-shrink:0}
.unm{font-weight:600;font-size:.79rem;color:#0e0b07}
.uem{font-size:.68rem;color:#8a7f72;margin-top:1px}
.pagi{display:flex;align-items:center;gap:5px;justify-content:flex-end;padding-top:11px;flex-wrap:wrap}
.pinfo{font-size:.7rem;color:#8a7f72;margin-right:auto}
.pb2{width:29px;height:29px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.73rem;font-weight:600;color:#8a7f72;transition:all .15s}
.pb2:hover,.pb2.on{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
</style>
