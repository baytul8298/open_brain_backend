<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import type { Role, Permission } from '@/types/models/User'
import type { Module } from '@/types/models/Module'

interface ModuleWithPermissions extends Module {
  permissions: Permission[]
}

interface Props {
  role: Role
  modules: ModuleWithPermissions[]
  assignedPermissionIds: number[]
}

const props = defineProps<Props>()

// ── State ──────────────────────────────────────────────────────────────────
// All modules start CLOSED
const expanded = ref<Set<number>>(new Set())
const selected = ref<Set<number>>(new Set(props.assignedPermissionIds))
const saving = ref(false)

// ── Helpers ────────────────────────────────────────────────────────────────
interface SubGroup {
  id: number | null
  name: string
}

function subModulesOf(mod: ModuleWithPermissions): SubGroup[] {
  const map = new Map<number | null, string>()
  mod.permissions.forEach(p => {
    if (!map.has(p.sub_module_id)) {
      map.set(p.sub_module_id, p.sub_module_relation?.name ?? '')
    }
  })
  return Array.from(map.entries()).map(([id, name]) => ({ id, name }))
}

function permsIn(mod: ModuleWithPermissions, subId: number | null): Permission[] {
  return mod.permissions.filter(p => p.sub_module_id === subId)
}

// ── Check state helpers ────────────────────────────────────────────────────
function permChecked(id: number)         { return selected.value.has(id) }

function subAllChecked(mod: ModuleWithPermissions, subId: number | null): boolean {
  const ps = permsIn(mod, subId)
  return ps.length > 0 && ps.every(p => selected.value.has(p.id))
}
function subPartial(mod: ModuleWithPermissions, subId: number | null): boolean {
  const ps = permsIn(mod, subId)
  const n  = ps.filter(p => selected.value.has(p.id)).length
  return n > 0 && n < ps.length
}

function modAllChecked(mod: ModuleWithPermissions): boolean {
  return mod.permissions.length > 0 && mod.permissions.every(p => selected.value.has(p.id))
}
function modPartial(mod: ModuleWithPermissions): boolean {
  const n = mod.permissions.filter(p => selected.value.has(p.id)).length
  return n > 0 && n < mod.permissions.length
}
function modSelectedCount(mod: ModuleWithPermissions): number {
  return mod.permissions.filter(p => selected.value.has(p.id)).length
}

// ── Toggle handlers ────────────────────────────────────────────────────────
function togglePerm(id: number) {
  const s = new Set(selected.value)
  s.has(id) ? s.delete(id) : s.add(id)
  selected.value = s
}

function toggleSub(mod: ModuleWithPermissions, subId: number | null) {
  const ps   = permsIn(mod, subId)
  const allOn = ps.every(p => selected.value.has(p.id))
  const s    = new Set(selected.value)
  ps.forEach(p => allOn ? s.delete(p.id) : s.add(p.id))
  selected.value = s
}

function toggleMod(mod: ModuleWithPermissions) {
  const allOn = modAllChecked(mod)
  const s     = new Set(selected.value)
  mod.permissions.forEach(p => allOn ? s.delete(p.id) : s.add(p.id))
  selected.value = s
}

function toggleExpand(id: number) {
  const e = new Set(expanded.value)
  e.has(id) ? e.delete(id) : e.add(id)
  expanded.value = e
}

// ── Stats ──────────────────────────────────────────────────────────────────
const totalPerms   = computed(() => props.modules.reduce((s, m) => s + m.permissions.length, 0))
const selectedCount = computed(() => selected.value.size)

// ── Submit ─────────────────────────────────────────────────────────────────
function save() {
  saving.value = true
  router.post(`/roles/${props.role.id}/assign-permission`, {
    permission_ids: Array.from(selected.value),
  }, { onFinish: () => { saving.value = false } })
}
</script>

<template>
  <AppLayout>
    <Head :title="`Assign Permission — ${role.display_name || role.name}`" />

    <!-- ── Hero ──────────────────────────────────────────────────────────── -->
    <div style="background:#100d09;position:relative;overflow:hidden;padding:28px 26px 26px;flex-shrink:0;">
      <div style="position:absolute;inset:0;background:radial-gradient(ellipse 55% 130% at 100% 50%,rgba(201,137,60,.15),transparent 62%),radial-gradient(ellipse 38% 85% at 0% 0%,rgba(184,75,47,.11),transparent 52%);pointer-events:none;"></div>
      <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.022) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;"></div>
      <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between;gap:18px;flex-wrap:wrap;">
        <div>
          <div style="font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.28);margin-bottom:7px;">System · Access Control</div>
          <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;color:#faf6ef;line-height:1.1;margin-bottom:5px;">
            Assign <em style="font-style:italic;color:#e8b96a;">Permission</em>
          </h1>
          <p style="font-size:.78rem;color:rgba(250,246,239,.36);line-height:1.6;">
            Role: <strong style="color:rgba(250,246,239,.75);">{{ role.display_name || role.name }}</strong>
          </p>
        </div>
        <div style="display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;align-self:flex-start;">
          <div style="padding:11px 17px;border-right:1px solid rgba(255,255,255,.08);text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#e8b96a;line-height:1;margin-bottom:2px;">{{ selectedCount }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Selected</div>
          </div>
          <div style="padding:11px 17px;text-align:center;min-width:76px;">
            <div style="font-family:'Playfair Display',serif;font-size:1.35rem;color:#faf6ef;line-height:1;margin-bottom:2px;">{{ totalPerms }}</div>
            <div style="font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(250,246,239,.26);">Total</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Body ──────────────────────────────────────────────────────────── -->
    <div class="ap-body">

      <!-- Action bar -->
      <div class="ap-bar">
        <button class="ap-back" @click="router.visit('/roles')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          Back to Roles
        </button>
        <button class="ap-save" :disabled="saving" @click="save">
          <svg v-if="!saving" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          <svg v-else width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
          {{ saving ? 'Saving…' : 'Save Permissions' }}
        </button>
      </div>

      <!-- Module grid -->
      <div class="ap-grid">
        <div v-for="mod in modules" :key="mod.id" class="mod-card">

          <!-- ── Layer 1: Module header ──────────────────────────────────── -->
          <div class="mod-hd" @click="toggleExpand(mod.id)">

            <!-- Circle icon: state indicator -->
            <span class="mod-circle-wrap" @click.stop="toggleMod(mod)">
              <!-- All checked -->
              <span v-if="modAllChecked(mod)" class="mod-circle mod-circle--full">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <!-- Partial -->
              <span v-else-if="modPartial(mod)" class="mod-circle mod-circle--partial">
                <span class="mod-circle-dash"></span>
              </span>
              <!-- Empty -->
              <span v-else class="mod-circle mod-circle--empty"></span>
            </span>

            <!-- Module name -->
            <span class="mod-name">{{ mod.module_name }}</span>

            <!-- Count badge -->
            <span class="mod-count">{{ modSelectedCount(mod) }}/{{ mod.permissions.length }}</span>

            <!-- Expand / collapse icon -->
            <span class="mod-toggle">
              <svg v-if="!expanded.has(mod.id)" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <svg v-else width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </span>
          </div>

          <!-- ── Body: visible when expanded ────────────────────────────── -->
          <div v-show="expanded.has(mod.id)" class="mod-body">

            <div
              v-for="(sub, si) in subModulesOf(mod)"
              :key="sub.id ?? 'none'"
              class="sub-section"
              :class="{ 'sub-section--border': si > 0 }"
            >
              <!-- ── Layer 2: Sub-module row ─────────────────────────────── -->
              <div v-if="sub.name" class="sub-hd" @click="toggleSub(mod, sub.id)">
                <!-- tick when all checked, dash when partial -->
                <span class="sub-tick">
                  <svg v-if="subAllChecked(mod, sub.id)" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  <span v-else-if="subPartial(mod, sub.id)" class="sub-tick-dash"></span>
                </span>
                <span class="sub-name">{{ sub.name }}</span>
              </div>

              <!-- ── Layer 3: Permission items ──────────────────────────── -->
              <div class="perm-grid">
                <label
                  v-for="perm in permsIn(mod, sub.id)"
                  :key="perm.id"
                  class="perm-item"
                  @click.prevent="togglePerm(perm.id)"
                >
                  <!-- Circle checkbox -->
                  <span class="perm-circle" :class="permChecked(perm.id) ? 'perm-circle--on' : ''">
                    <svg v-if="permChecked(perm.id)" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  </span>
                  <span class="perm-label">{{ perm.display_name || perm.name }}</span>
                </label>
              </div>
            </div>

            <div v-if="mod.permissions.length === 0" class="mod-empty">No permissions defined.</div>
          </div>

        </div>
      </div>

      <div v-if="modules.length === 0" class="ap-empty">No modules found. Create modules and permissions first.</div>
    </div>
  </AppLayout>
</template>

<style scoped>
@keyframes spin { to { transform: rotate(360deg) } }
.spin { animation: spin .8s linear infinite; }

/* ── Page body ── */
.ap-body {
  padding: 20px 26px 48px;
  display: flex;
  flex-direction: column;
  gap: 18px;
  background: #f5f0e8;
  min-height: calc(100vh - 130px);
}

/* ── Action bar ── */
.ap-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  flex-wrap: wrap;
}
.ap-back {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 15px;
  font-family: 'DM Sans', sans-serif; font-size: .77rem; font-weight: 600;
  border-radius: 10px; border: 1.5px solid #e0d8cc;
  background: #fff; color: #8a7f72; cursor: pointer;
  transition: all .15s;
}
.ap-back:hover { border-color: #c9893c; color: #c9893c; }

.ap-save {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 20px;
  font-family: 'DM Sans', sans-serif; font-size: .78rem; font-weight: 700;
  border-radius: 10px; border: none; cursor: pointer;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  color: #fff; box-shadow: 0 2px 14px rgba(201,137,60,.3);
  transition: opacity .15s;
}
.ap-save:disabled { opacity: .6; cursor: not-allowed; }

/* ── Grid ── */
.ap-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  align-items: start;
}
@media (max-width: 800px) {
  .ap-grid { grid-template-columns: 1fr; }
}

/* ── Module card ── */
.mod-card {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

/* ── Module header (Layer 1) ── */
.mod-hd {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 13px 16px;
  background: linear-gradient(135deg, #c9893c 0%, #b84b2f 100%);
  cursor: pointer;
  user-select: none;
}

/* Circle state icon */
.mod-circle-wrap { flex-shrink: 0; cursor: pointer; }
.mod-circle {
  width: 22px; height: 22px;
  border-radius: 50%;
  display: inline-flex; align-items: center; justify-content: center;
  transition: all .15s;
}
.mod-circle--empty  { border: 2.5px solid rgba(255,255,255,.65); background: transparent; }
.mod-circle--partial { border: 2.5px solid #fff; background: rgba(255,255,255,.25); }
.mod-circle--full   { border: 2.5px solid #fff; background: #fff; color: #b84b2f; }
.mod-circle-dash    { width: 9px; height: 2.5px; background: #fff; border-radius: 2px; }

/* Module name + count */
.mod-name {
  flex: 1;
  font-family: 'DM Sans', sans-serif; font-size: .84rem; font-weight: 700;
  color: #fff;
  letter-spacing: .01em;
}
.mod-count {
  font-family: 'DM Sans', sans-serif; font-size: .68rem; font-weight: 600;
  color: rgba(255,255,255,.7);
  background: rgba(255,255,255,.18);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 20px;
  padding: 2px 8px;
  white-space: nowrap;
}
.mod-toggle {
  color: rgba(255,255,255,.85);
  display: flex; align-items: center;
  flex-shrink: 0;
  margin-left: 2px;
}

/* ── Module body ── */
.mod-body {
  background: #fff;
  border: 1.5px solid rgba(201,137,60,.2);
  border-top: none;
  border-radius: 0 0 10px 10px;
}

/* ── Sub-section (Layer 2) ── */
.sub-section { padding: 12px 16px 10px; }
.sub-section--border { border-top: 1px solid #f0e8d6; }

.sub-hd {
  display: flex;
  align-items: center;
  gap: 7px;
  margin-bottom: 9px;
  cursor: pointer;
  user-select: none;
}
.sub-tick {
  width: 16px; height: 16px;
  display: inline-flex; align-items: center; justify-content: center;
  color: #c9893c;
  flex-shrink: 0;
}
.sub-tick-dash {
  width: 9px; height: 2px;
  background: #c9893c;
  border-radius: 1px;
}
.sub-name {
  font-family: 'DM Sans', sans-serif; font-size: .79rem; font-weight: 700;
  color: #8a4a1e;
}

/* ── Permission grid (Layer 3) ── */
.perm-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 6px 4px;
  padding-left: 2px;
}

.perm-item {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 5px 6px;
  border-radius: 6px;
  cursor: pointer;
  user-select: none;
  transition: background .13s;
}
.perm-item:hover { background: rgba(201,137,60,.07); }

/* Circle checkbox */
.perm-circle {
  width: 18px; height: 18px;
  border-radius: 50%;
  border: 2px solid #d4c4b0;
  background: #fff;
  display: inline-flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all .15s;
}
.perm-circle--on {
  border-color: #c9893c;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  color: #fff;
}

.perm-label {
  font-family: 'DM Sans', sans-serif;
  font-size: .74rem;
  font-weight: 500;
  color: #3d3529;
  line-height: 1.2;
}

/* ── Empty states ── */
.mod-empty {
  padding: 14px 16px;
  font-size: .74rem; font-style: italic; color: #8a7f72;
}
.ap-empty {
  text-align: center; padding: 60px 24px;
  font-size: .82rem; color: #8a7f72;
}
</style>
