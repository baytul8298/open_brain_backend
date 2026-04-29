<script setup lang="ts">
// v5 – OpenBrain theme colors + circular checkboxes
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

interface RoleItem { id: number; name: string; display_name: string | null; users_count: number }
interface UserItem  { id: string; name: string; email: string; roles: string }
interface BtnNode   { id: number; name: string; checked: boolean }
interface SubNode   { id: number; name: string; checked: boolean; button_count: number; buttons: BtnNode[] }
interface MenuNode  { id: number; name: string; checked: boolean; submenu_count: number; submenus: SubNode[] }
interface TreeResp  {
  type: 'role' | 'user' | 'none'
  role_name?: string | null
  role_menu_ids: number[]; role_submenu_ids: number[]; role_button_ids: number[]
  menus: MenuNode[]
}
interface Props { roles: RoleItem[]; users: UserItem[] }
const props = defineProps<Props>()

const activeType   = ref<'role' | 'user'>('role')
const selectedRole = ref<RoleItem | null>(null)
const selectedUser = ref<UserItem | null>(null)
const tree         = ref<TreeResp | null>(null)
const loading      = ref(false)
const saving       = ref(false)
const collapsed    = ref<Set<number>>(new Set())
const userSearch   = ref('')

const checkedMenus    = ref<Set<number>>(new Set())
const checkedSubmenus = ref<Set<number>>(new Set())
const checkedButtons  = ref<Set<number>>(new Set())
const roleMenuIds     = ref<number[]>([])
const roleSubmenuIds  = ref<number[]>([])
const roleButtonIds   = ref<number[]>([])

const filteredUsers = computed(() => {
  const q = userSearch.value.toLowerCase()
  return q ? props.users.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)) : props.users
})
const hasSelection = computed(() =>
  (activeType.value === 'role' && !!selectedRole.value) ||
  (activeType.value === 'user' && !!selectedUser.value)
)

function isInherited(type: 'menu'|'submenu'|'button', id: number) {
  if (activeType.value !== 'user') return false
  if (type === 'menu')    return roleMenuIds.value.includes(id)
  if (type === 'submenu') return roleSubmenuIds.value.includes(id)
  return roleButtonIds.value.includes(id)
}

function initChecked(data: TreeResp) {
  const m = new Set<number>(), s = new Set<number>(), b = new Set<number>()
  data.menus.forEach(mn => {
    if (mn.checked) m.add(mn.id)
    mn.submenus.forEach(sn => {
      if (sn.checked) s.add(sn.id)
      sn.buttons.forEach(bn => { if (bn.checked) b.add(bn.id) })
    })
  })
  checkedMenus.value = m; checkedSubmenus.value = s; checkedButtons.value = b
}

async function loadTree() {
  if (!hasSelection.value) return
  loading.value = true; tree.value = null
  try {
    const p = activeType.value === 'role'
      ? `role_id=${selectedRole.value!.id}`
      : `user_id=${selectedUser.value!.id}`
    const data: TreeResp = await fetch(`/manage-permissions/tree?${p}`, {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    }).then(r => r.json())
    tree.value = data
    initChecked(data)
    roleMenuIds.value    = data.role_menu_ids    ?? []
    roleSubmenuIds.value = data.role_submenu_ids ?? []
    roleButtonIds.value  = data.role_button_ids  ?? []
    collapsed.value      = new Set()
  } finally { loading.value = false }
}

function selectRole(r: RoleItem) { selectedRole.value = r; selectedUser.value = null; loadTree() }
function selectUser(u: UserItem) { selectedUser.value = u; selectedRole.value = null; loadTree() }
function switchType(t: 'role'|'user') {
  if (activeType.value === t) return
  activeType.value = t; selectedRole.value = null; selectedUser.value = null; tree.value = null
  checkedMenus.value = new Set(); checkedSubmenus.value = new Set(); checkedButtons.value = new Set()
}

function toggleMenu(mn: MenuNode) {
  if (checkedMenus.value.has(mn.id)) {
    checkedMenus.value.delete(mn.id)
    mn.submenus.forEach(s => { checkedSubmenus.value.delete(s.id); s.buttons.forEach(b => checkedButtons.value.delete(b.id)) })
  } else checkedMenus.value.add(mn.id)
}
function toggleSub(sn: SubNode) {
  if (checkedSubmenus.value.has(sn.id)) {
    checkedSubmenus.value.delete(sn.id); sn.buttons.forEach(b => checkedButtons.value.delete(b.id))
  } else checkedSubmenus.value.add(sn.id)
}
function toggleBtn(bn: BtnNode) {
  checkedButtons.value.has(bn.id) ? checkedButtons.value.delete(bn.id) : checkedButtons.value.add(bn.id)
}
function toggleCollapse(id: number) {
  collapsed.value.has(id) ? collapsed.value.delete(id) : collapsed.value.add(id)
}

function save() {
  if (!hasSelection.value || saving.value) return
  saving.value = true
  const payload = { menu_ids: [...checkedMenus.value], submenu_ids: [...checkedSubmenus.value], button_ids: [...checkedButtons.value] }
  if (activeType.value === 'role') {
    router.post('/manage-permissions/sync-role',
      { role_id: selectedRole.value!.id, ...payload },
      { preserveScroll: true, onFinish: () => { saving.value = false } })
  } else {
    router.post('/manage-permissions/sync-user', {
      user_id: selectedUser.value!.id, ...payload,
      role_menu_ids: roleMenuIds.value, role_submenu_ids: roleSubmenuIds.value, role_button_ids: roleButtonIds.value,
    }, { preserveScroll: true, onFinish: () => { saving.value = false } })
  }
}
</script>

<template>
  <AppLayout>
    <Head title="Manage Permissions" />

    <div class="wrap">

      <!-- ── PAGE HEADER ──────────────────────────────────────────────── -->
      <div class="hdr">
        <div class="hdr-glow"></div>
        <div class="hdr-l">
          <h1 class="hdr-title">Manage Permissions</h1>
          <p class="hdr-sub">
            <span class="hdr-crumb">User Type</span>
            <span class="hdr-dot">›</span>
            <span class="hdr-crumb">{{ activeType === 'role' ? 'Role List' : 'User List' }}</span>
            <span class="hdr-dot">›</span>
            <span class="hdr-crumb hdr-crumb-last">Permission Menu &amp; Buttons</span>
          </p>
        </div>
        <div class="hdr-r">
          <button v-if="hasSelection" class="btn-save" :disabled="saving" @click="save">
            <svg v-if="!saving" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            <span v-else class="spin"></span>
            {{ saving ? 'Saving…' : 'Save Changes' }}
          </button>
          <button class="btn-ghost" :disabled="!hasSelection || loading" @click="loadTree">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            Refresh
          </button>
        </div>
      </div>

      <!-- ── THREE CARDS ──────────────────────────────────────────────── -->
      <div class="board">

        <!-- Card 1: User Type -->
        <div class="card card-1">
          <div class="card-hd">
            <span class="card-hd-icon">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            User Type
          </div>
          <div class="card-bd">
            <button :class="['trow', activeType === 'role' && 'trow-on']" @click="switchType('role')">
              <span :class="['t-dot', activeType === 'role' && 't-dot-on']"></span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Role
            </button>
            <button :class="['trow', activeType === 'user' && 'trow-on']" @click="switchType('user')">
              <span :class="['t-dot', activeType === 'user' && 't-dot-on']"></span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Users
            </button>
          </div>
        </div>

        <!-- Card 2: List -->
        <div class="card card-2">
          <div class="card-hd">
            <span class="card-hd-icon">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </span>
            {{ activeType === 'role' ? 'Role List' : 'User List' }}
            <span class="cnt-pill">{{ activeType === 'role' ? roles.length : users.length }}</span>
          </div>
          <div class="search-wrap">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#a08060" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input v-model="userSearch" class="search-inp" placeholder="Search…" />
          </div>
          <div class="card-bd">
            <template v-if="activeType === 'role'">
              <button
                v-for="role in roles" :key="role.id"
                :class="['lrow', selectedRole?.id === role.id && 'lrow-on']"
                @click="selectRole(role)"
              >
                <span class="lrow-name">{{ role.display_name || role.name }}</span>
                <span class="lrow-meta">ID: {{ role.id }} &bull; {{ role.users_count }} user{{ role.users_count !== 1 ? 's' : '' }}</span>
              </button>
            </template>
            <template v-else>
              <button
                v-for="user in filteredUsers" :key="user.id"
                :class="['lrow', selectedUser?.id === user.id && 'lrow-on']"
                @click="selectUser(user)"
              >
                <span class="lrow-name">{{ user.name }}</span>
                <span class="lrow-meta">{{ user.email }}</span>
              </button>
              <p v-if="filteredUsers.length === 0" class="empty-msg">No users found</p>
            </template>
          </div>
        </div>

        <!-- Card 3: Permissions -->
        <div class="card card-3">
          <div class="card-hd perm-hd">
            <span class="card-hd-icon">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <span>Permissions</span>
            <span class="perm-hint">Check or uncheck to allow / deny access</span>
          </div>

          <!-- Empty -->
          <div v-if="!hasSelection" class="pstate">
            <div class="pstate-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <p class="pstate-title">No selection</p>
            <p class="pstate-desc">Select a {{ activeType === 'role' ? 'role' : 'user' }} from the list to manage its permissions</p>
          </div>

          <!-- Loading -->
          <div v-else-if="loading" class="pstate">
            <span class="spin spin-lg"></span>
            <p class="pstate-desc">Loading permissions…</p>
          </div>

          <!-- Tree -->
          <div v-else-if="tree" class="ptree">
            <p v-if="tree.menus.length === 0" class="empty-msg" style="padding:28px 20px">
              No menu items — add them in Menu Manager first.
            </p>

            <div v-for="mn in tree.menus" :key="mn.id" class="mblock">

              <!-- Menu row -->
              <div class="prow prow-menu">
                <label class="chk-lbl" @click.prevent="toggleMenu(mn)">
                  <span :class="['chk', checkedMenus.has(mn.id) && 'chk-on', isInherited('menu', mn.id) && 'chk-inh']">
                    <svg v-if="checkedMenus.has(mn.id)" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  </span>
                  <span class="row-name row-name-menu">{{ mn.name }}</span>
                  <span class="row-id">#{{ mn.id }}</span>
                </label>
                <span v-if="mn.submenu_count" class="count-badge">{{ mn.submenu_count }} sub</span>
                <button v-if="mn.submenus.length" class="tog-btn" @click="toggleCollapse(mn.id)">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline v-if="!collapsed.has(mn.id)" points="18 15 12 9 6 15"/>
                    <polyline v-else points="6 9 12 15 18 9"/>
                  </svg>
                </button>
              </div>

              <template v-if="!collapsed.has(mn.id)">
                <div v-for="sn in mn.submenus" :key="sn.id" class="sblock">

                  <!-- Submenu row -->
                  <div class="prow prow-sub">
                    <label class="chk-lbl" @click.prevent="toggleSub(sn)">
                      <span :class="['chk', checkedSubmenus.has(sn.id) && 'chk-on', isInherited('submenu', sn.id) && 'chk-inh']">
                        <svg v-if="checkedSubmenus.has(sn.id)" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                      </span>
                      <span class="row-name row-name-sub">{{ sn.name }}</span>
                      <span class="row-id">#{{ sn.id }}</span>
                    </label>
                    <span v-if="sn.button_count" class="count-badge count-badge-sm">{{ sn.button_count }} btn</span>
                  </div>

                  <!-- Button chips -->
                  <div v-if="sn.buttons.length" class="brow">
                    <label
                      v-for="bn in sn.buttons" :key="bn.id"
                      :class="['chip', checkedButtons.has(bn.id) && 'chip-on', isInherited('button', bn.id) && 'chip-inh']"
                      @click.prevent="toggleBtn(bn)"
                    >
                      <span :class="['chk-sm', checkedButtons.has(bn.id) && 'chk-sm-on']">
                        <svg v-if="checkedButtons.has(bn.id)" width="7" height="7" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                      </span>
                      {{ bn.name }}
                    </label>
                  </div>

                </div>
              </template>

            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* ── Variables ──────────────────────────────────────────────────────────── */
/* Theme palette (OpenBrain warm amber)
   --amber:  #c9893c
   --rust:   #b84b2f
   --gold:   #e8b96a
   --dark:   #100d09
   --warm-w: #faf6ef
*/

/* ── Root ───────────────────────────────────────────────────────────────── */
.wrap {
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 62px);
  background: #f5efe6;
}

/* ── Header ──────────────────────────────────────────────────────────────── */
.hdr {
  position: relative;
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 24px;
  background: #100d09;
  border-bottom: 1px solid rgba(201,137,60,.25);
  flex-shrink: 0; gap: 12px;
  overflow: hidden;
}
.hdr-glow {
  position: absolute; inset: 0; pointer-events: none;
  background: radial-gradient(ellipse 60% 100% at 30% 50%, rgba(201,137,60,.12), transparent 65%);
}
.hdr-l { display: flex; flex-direction: column; gap: 5px; position: relative; z-index: 1; }
.hdr-r { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
.hdr-title {
  margin: 0; font-size: 1.2rem; font-weight: 700;
  color: #faf6ef; font-family: 'Playfair Display', serif; letter-spacing: -.01em;
}
.hdr-sub { margin: 0; display: flex; align-items: center; gap: 6px; }
.hdr-crumb { font-size: .72rem; font-weight: 500; color: rgba(250,246,239,.4); }
.hdr-crumb-last { color: #e8b96a; font-weight: 600; }
.hdr-dot { font-size: .65rem; color: rgba(250,246,239,.22); }

.btn-save {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 16px; font-size: .78rem; font-weight: 700; letter-spacing: .02em;
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  color: #faf6ef; border: none; border-radius: 6px;
  cursor: pointer; font-family: 'DM Sans', sans-serif;
  box-shadow: 0 0 18px rgba(201,137,60,.35);
  transition: opacity .15s, box-shadow .15s;
}
.btn-save:disabled { opacity: .55; cursor: not-allowed; box-shadow: none; }
.btn-save:not(:disabled):hover { opacity: .9; box-shadow: 0 0 24px rgba(201,137,60,.5); }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; font-size: .78rem; font-weight: 500;
  background: rgba(255,255,255,.06); color: rgba(250,246,239,.6);
  border: 1px solid rgba(201,137,60,.28); border-radius: 6px;
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .15s;
}
.btn-ghost:disabled { opacity: .35; cursor: not-allowed; }
.btn-ghost:not(:disabled):hover { background: rgba(201,137,60,.12); color: #e8b96a; border-color: rgba(201,137,60,.5); }

/* ── Spinner ─────────────────────────────────────────────────────────────── */
.spin { display: inline-block; width: 12px; height: 12px; border: 2px solid rgba(255,255,255,.3); border-top-color: #faf6ef; border-radius: 50%; animation: sp .6s linear infinite; }
.spin-lg { width: 30px; height: 30px; border: 3px solid rgba(201,137,60,.2); border-top-color: #c9893c; }
@keyframes sp { to { transform: rotate(360deg); } }

/* ── Board ───────────────────────────────────────────────────────────────── */
.board {
  display: flex;
  flex: 1;
  gap: 16px;
  padding: 20px;
  align-items: flex-start;
}

/* ── Cards ───────────────────────────────────────────────────────────────── */
.card {
  background: #fff;
  border: 1px solid #e8ddd0;
  border-radius: 10px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: 0 2px 8px rgba(100,60,20,.07);
}
.card-1 { width: 168px; flex-shrink: 0; }
.card-2 { width: 258px; flex-shrink: 0; }
.card-3 { flex: 1; min-width: 0; min-height: 500px; }

.card-hd {
  display: flex; align-items: center; gap: 8px;
  padding: 11px 14px;
  background: linear-gradient(135deg, #1a1410 0%, #2a1f16 100%);
  border-bottom: 1px solid rgba(201,137,60,.22);
  font-size: .68rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: rgba(250,246,239,.55);
}
.card-hd-icon {
  width: 22px; height: 22px; border-radius: 5px;
  background: rgba(201,137,60,.18); border: 1px solid rgba(201,137,60,.3);
  display: flex; align-items: center; justify-content: center;
  color: #c9893c; flex-shrink: 0;
}
.perm-hd { gap: 0; flex-wrap: wrap; padding: 10px 14px; row-gap: 3px; }
.perm-hd > span { display: block; }
.perm-hint {
  width: 100%; font-size: .68rem; font-weight: 400; letter-spacing: 0;
  text-transform: none; color: rgba(250,246,239,.28); margin-top: 2px;
}

.cnt-pill {
  margin-left: auto; font-size: .66rem; font-weight: 700;
  background: rgba(201,137,60,.2); color: #e8b96a;
  border: 1px solid rgba(201,137,60,.3); border-radius: 20px; padding: 1px 8px;
}

.card-bd { flex: 1; overflow-y: auto; }

/* ── Type selector ───────────────────────────────────────────────────────── */
.trow {
  display: flex; align-items: center; gap: 9px;
  width: 100%; padding: 12px 14px;
  background: transparent; border: none; border-left: 3px solid transparent;
  border-bottom: 1px solid #f5ede4;
  font-size: .85rem; font-weight: 500; color: #5a4030; text-align: left;
  cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .14s;
}
.trow:last-child { border-bottom: none; }
.trow:hover { background: #fdf6ef; }
.trow-on { background: #fff8ee; color: #b84b2f; border-left-color: #c9893c; font-weight: 700; }
.trow-on svg { stroke: #c9893c; }

.t-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: #d8c8b8; flex-shrink: 0; transition: background .14s;
}
.t-dot-on { background: #c9893c; box-shadow: 0 0 6px rgba(201,137,60,.6); }

/* ── Search ──────────────────────────────────────────────────────────────── */
.search-wrap {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 12px; border-bottom: 1px solid #f0e8de; flex-shrink: 0;
  background: #fdfaf7;
}
.search-inp {
  flex: 1; border: none; outline: none;
  font-size: .8rem; color: #2a1f16; background: transparent;
  font-family: 'DM Sans', sans-serif;
}
.search-inp::placeholder { color: #c0a888; }

/* ── List rows ───────────────────────────────────────────────────────────── */
.lrow {
  display: flex; flex-direction: column; align-items: flex-start;
  width: 100%; padding: 11px 14px;
  background: transparent; border: none; border-left: 3px solid transparent;
  border-bottom: 1px solid #f5ede4;
  font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .14s; text-align: left;
}
.lrow:last-child { border-bottom: none; }
.lrow:hover { background: #fdf6ef; }
.lrow-on { background: #fff8ee; border-left-color: #c9893c; }
.lrow-on .lrow-name { color: #b84b2f; font-weight: 700; }

.lrow-name { font-size: .84rem; font-weight: 600; color: #1a1410; }
.lrow-meta { font-size: .72rem; color: #a08060; margin-top: 2px; }
.empty-msg { padding: 20px; text-align: center; font-size: .8rem; color: #b0906a; }

/* ── States ──────────────────────────────────────────────────────────────── */
.pstate {
  flex: 1; display: flex; flex-direction: column; align-items: center;
  justify-content: center; padding: 52px 28px; text-align: center;
}
.pstate-icon {
  width: 56px; height: 56px; border-radius: 14px;
  background: linear-gradient(135deg, rgba(201,137,60,.1), rgba(184,75,47,.08));
  border: 1px solid rgba(201,137,60,.2);
  display: flex; align-items: center; justify-content: center;
  color: #c9893c;
}
.pstate-title { margin: 14px 0 4px; font-size: .95rem; font-weight: 700; color: #2a1f16; }
.pstate-desc  { margin: 0; font-size: .8rem; color: #a08060; line-height: 1.6; max-width: 220px; }

/* ── Permission tree ─────────────────────────────────────────────────────── */
.ptree { flex: 1; overflow-y: auto; }

.mblock { border-bottom: 1px solid #f0e8de; }
.mblock:last-child { border-bottom: none; }

/* Rows */
.prow {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 16px;
}
.prow-menu { background: #fdfaf7; border-bottom: 1px solid #f0e8de; }
.prow-sub  { padding-left: 42px; background: #fff; border-bottom: 1px solid #f8f2ec; }

.chk-lbl {
  display: flex; align-items: center; gap: 10px;
  cursor: pointer; flex: 1; min-width: 0;
}

.row-name { font-size: .83rem; font-weight: 700; color: #1a1410; }
.row-name-sub { font-weight: 500; color: #3a2a1e; }
.row-id { font-size: .67rem; color: #c0a080; margin-left: 2px; }

/* ── CIRCULAR CHECKBOX ───────────────────────────────────────────────────── */
.chk {
  width: 22px; height: 22px;
  border-radius: 50%;
  border: 2px solid #d0b898;
  background: #fff;
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s;
  box-shadow: inset 0 1px 2px rgba(0,0,0,.06);
}
.chk-on {
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  border-color: #c9893c;
  box-shadow: 0 0 10px rgba(201,137,60,.4);
}
.chk-inh.chk-on {
  background: linear-gradient(135deg, #7c3aed, #5b21b6);
  border-color: #7c3aed;
  box-shadow: 0 0 10px rgba(124,58,237,.35);
}

/* ── Chips small checkbox ────────────────────────────────────────────────── */
.chk-sm {
  width: 15px; height: 15px; border-radius: 50%;
  border: 1.5px solid #d0b898; background: #fff;
  flex-shrink: 0; display: flex; align-items: center; justify-content: center;
  transition: all .14s;
}
.chk-sm-on {
  background: linear-gradient(135deg, #c9893c, #b84b2f);
  border-color: #c9893c;
  box-shadow: 0 0 6px rgba(201,137,60,.4);
}

/* ── Badges ──────────────────────────────────────────────────────────────── */
.count-badge {
  font-size: .62rem; font-weight: 700; color: #a08060;
  background: #f5ede4; border-radius: 20px; padding: 1px 7px; flex-shrink: 0;
}
.count-badge-sm { font-size: .59rem; }

/* ── Collapse toggle ─────────────────────────────────────────────────────── */
.tog-btn {
  margin-left: auto; background: none; border: none; cursor: pointer;
  color: #c0a080; padding: 3px 5px; border-radius: 4px;
  display: flex; align-items: center; transition: color .12s, background .12s; flex-shrink: 0;
}
.tog-btn:hover { color: #c9893c; background: rgba(201,137,60,.1); }

/* ── Button chips ────────────────────────────────────────────────────────── */
.brow {
  display: flex; flex-wrap: wrap; gap: 6px;
  padding: 7px 16px 10px 58px;
  border-bottom: 1px solid #f8f2ec;
  background: #fdfaf7;
}
.chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px 4px 6px; border-radius: 20px;
  font-size: .72rem; font-weight: 500;
  border: 1px solid #e8ddd0; background: #fff; color: #7a5840;
  cursor: pointer; font-family: 'DM Sans', sans-serif; user-select: none; transition: all .12s;
}
.chip:hover  { border-color: #c9893c; color: #b84b2f; background: #fff8ee; }
.chip-on     { border-color: #c9893c; background: #fff8ee; color: #b84b2f; font-weight: 600; }
.chip-inh.chip-on { border-color: #7c3aed; background: #f5f0ff; color: #6d28d9; }
</style>
