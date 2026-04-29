<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { SubModule } from '@/types/models/SubModule'
import type { Module } from '@/types/models/Module'
import { format } from 'date-fns'

interface Props {
  subModules: {
    data: SubModule[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  modules: Module[]
  moduleFilter: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  edit: [subModule: SubModule]
  delete: [subModule: SubModule]
  'update:moduleFilter': [value: string]
}>()

const search = ref('')

function handleEdit(s: SubModule) { emit('edit', s) }
function handleDelete(s: SubModule) { emit('delete', s) }

function formatDate(d: string) {
  try { return format(new Date(d), 'MMM d, yyyy') } catch { return d }
}

function goToPage(page: number) {
  if (page < 1 || page > props.subModules.last_page) return
  router.visit(`/sub-modules?page=${page}`, { preserveState: true, preserveScroll: true })
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
          <input v-model="search" placeholder="Sub-module name…"/>
        </div>
        <div class="msel">
          <select
            :value="moduleFilter"
            @change="emit('update:moduleFilter', ($event.target as HTMLSelectElement).value)"
            class="mselect"
          >
            <option value="">All Modules</option>
            <option v-for="mod in modules" :key="mod.id" :value="String(mod.id)">{{ mod.module_name }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="tw">
      <table class="dt">
        <thead>
          <tr>
            <th>Sub-Module Name</th>
            <th>Module</th>
            <th>Permissions</th>
            <th>Created</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sub in subModules.data" :key="sub.id">
            <td>
              <div class="mnm">{{ sub.name }}</div>
            </td>
            <td>
              <span class="mbadge" v-if="sub.module">{{ sub.module.module_name }}</span>
              <span v-else style="color:#8a7f72;font-size:.74rem">—</span>
            </td>
            <td>
              <span class="pcnt">{{ sub.permissions_count ?? '—' }}</span>
            </td>
            <td style="color:#8a7f72;font-size:.74rem">{{ formatDate(sub.created_at) }}</td>
            <td>
              <div class="ar">
                <button class="btn bg2 bxs" @click="handleEdit(sub)">Edit</button>
                <button class="btn bd bxs" @click="handleDelete(sub)">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!subModules.data.length">
            <td colspan="5" style="text-align:center;padding:24px;color:#8a7f72;font-size:.8rem;">No sub-modules found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="cb" style="padding-top:9px;padding-bottom:9px">
      <div class="pagi">
        <span class="pinfo">{{ (subModules.current_page - 1) * subModules.per_page + 1 }}–{{ Math.min(subModules.current_page * subModules.per_page, subModules.total) }} of {{ subModules.total }}</span>
        <button class="pb2" @click="goToPage(subModules.current_page - 1)">‹</button>
        <button
          v-for="page in pages(subModules.current_page, subModules.last_page)"
          :key="page"
          :class="['pb2', page === subModules.current_page ? 'on' : '']"
          @click="goToPage(page)"
        >{{ page }}</button>
        <button class="pb2" @click="goToPage(subModules.current_page + 1)">›</button>
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
.msel{display:flex;align-items:center;}
.mselect{padding:6px 10px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.78rem;color:#0e0b07;outline:none;cursor:pointer;transition:border-color .16s}
.mselect:focus{border-color:#e8b96a}
.btn{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:600;border-radius:9px;border:1.5px solid;cursor:pointer;transition:all .16s;white-space:nowrap;background:none}
.bg2{background:#f0e8d6!important;border-color:#e0d8cc;color:#8a7f72}
.bg2:hover{border-color:#e8b96a;color:#c9893c}
.bd{background:rgba(184,50,50,.07)!important;border-color:rgba(184,50,50,.22);color:#b83232}
.bxs{padding:3px 8px;font-size:.67rem}
.tw{overflow-x:auto}
.dt{width:100%;border-collapse:collapse}
.dt th{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8a7f72;padding:9px 13px;text-align:left;border-bottom:2px solid #e0d8cc;background:#f0e8d6;white-space:nowrap}
.dt td{padding:10px 13px;border-bottom:1px solid #e0d8cc;vertical-align:middle;font-size:.79rem}
.dt tr:last-child td{border-bottom:none}
.dt tbody tr:hover td{background:rgba(240,232,214,.32)}
.ar{display:flex;gap:5px;align-items:center;justify-content:flex-end}
.mnm{font-weight:600;font-size:.79rem;color:#0e0b07}
.mbadge{display:inline-flex;align-items:center;padding:2px 9px;border-radius:7px;font-size:.72rem;font-weight:600;background:rgba(201,137,60,.1);border:1px solid rgba(201,137,60,.22);color:#c9893c}
.pcnt{display:inline-flex;align-items:center;padding:2px 8px;border-radius:7px;font-size:.72rem;font-weight:600;background:#f0e8d6;border:1px solid #e0d8cc;color:#8a7f72}
.pagi{display:flex;align-items:center;gap:5px;justify-content:flex-end;padding-top:11px;flex-wrap:wrap}
.pinfo{font-size:.7rem;color:#8a7f72;margin-right:auto}
.pb2{width:29px;height:29px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.73rem;font-weight:600;color:#8a7f72;transition:all .15s}
.pb2:hover,.pb2.on{border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07)}
</style>
