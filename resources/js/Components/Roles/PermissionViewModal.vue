<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import type { Role } from '@/types/models/User'

interface NavButton  { id: number; name: string; checked: boolean }
interface NavSubmenu { id: number; name: string; checked: boolean; buttons: NavButton[] }
interface NavMenu    { id: number; name: string; checked: boolean; submenus: NavSubmenu[] }

interface Props { open: boolean; role: Role | null }
const props = defineProps<Props>()
const emit  = defineEmits<{ close: [] }>()

// ── State ──────────────────────────────────────────────────────────────────
const loading       = ref(false)
const navTree       = ref<NavMenu[]>([])
const expanded      = ref<Set<number>>(new Set())
const subCollapsed  = ref<Set<number>>(new Set())
const search        = ref('')

// Fetch nav permissions when modal opens
watch(() => props.open, async (v) => {
  if (!v || !props.role) return
  expanded.value     = new Set()
  subCollapsed.value = new Set()
  search.value       = ''
  loading.value      = true
  try {
    const res = await fetch(`/manage-permissions/tree?role_id=${props.role.id}`)
    const data = await res.json()
    navTree.value = (data.menus ?? []) as NavMenu[]
  } catch {
    navTree.value = []
  } finally {
    loading.value = false
  }
})

// ── Grouping: only menus/submenus that have at least one checked item ──────
interface FlatMenu    { menu: NavMenu; subs: FlatSub[] }
interface FlatSub     { sub: NavSubmenu; buttons: NavButton[] }

const allGranted = computed<FlatMenu[]>(() => {
  return navTree.value
    .map(menu => {
      const subs: FlatSub[] = menu.submenus
        .filter(sub => sub.checked || sub.buttons.some(b => b.checked))
        .map(sub => ({
          sub,
          buttons: sub.buttons.filter(b => b.checked),
        }))
      return { menu, subs }
    })
    .filter(m => m.menu.checked || m.subs.length > 0)
})

// ── Search filter ──────────────────────────────────────────────────────────
const q = computed(() => search.value.trim().toLowerCase())

const granted = computed<FlatMenu[]>(() => {
  if (!q.value) return allGranted.value
  return allGranted.value
    .map(fm => ({
      ...fm,
      subs: fm.subs
        .map(fs => ({
          ...fs,
          buttons: fs.buttons.filter(b => b.name.toLowerCase().includes(q.value)),
        }))
        .filter(fs =>
          fs.sub.name.toLowerCase().includes(q.value) ||
          fs.buttons.length > 0
        ),
    }))
    .filter(fm =>
      fm.menu.name.toLowerCase().includes(q.value) || fm.subs.length > 0
    )
})

// Auto-expand on search
watch(q, (val) => {
  if (val) {
    const e = new Set(expanded.value)
    granted.value.forEach(fm => e.add(fm.menu.id))
    expanded.value = e
  }
})

// ── Stats ──────────────────────────────────────────────────────────────────
const totalMenus = computed(() => allGranted.value.length)
const totalItems = computed(() => {
  return allGranted.value.reduce((s, fm) => {
    let c = fm.menu.checked ? 1 : 0
    fm.subs.forEach(fs => {
      if (fs.sub.checked) c++
      c += fs.buttons.length
    })
    return s + c
  }, 0)
})

// ── Expand / Collapse all ──────────────────────────────────────────────────
function expandAll()   { expanded.value = new Set(granted.value.map(fm => fm.menu.id)) }
function collapseAll() { expanded.value = new Set() }
const allExpanded = computed(() => granted.value.every(fm => expanded.value.has(fm.menu.id)))

function toggleMenu(id: number) {
  const e = new Set(expanded.value)
  e.has(id) ? e.delete(id) : e.add(id)
  expanded.value = e
}

function toggleSub(id: number) {
  const c = new Set(subCollapsed.value)
  c.has(id) ? c.delete(id) : c.add(id)
  subCollapsed.value = c
}

// ── Highlight ─────────────────────────────────────────────────────────────
function highlight(text: string): string {
  if (!q.value) return text
  const escaped = q.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
  return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>')
}
</script>

<template>
  <Teleport to="body">
    <Transition name="backdrop">
      <div v-if="open" class="modal" @click.self="emit('close')">
        <Transition name="panel">
          <div v-if="open" class="mc" @click.stop>

            <!-- ── Header ─────────────────────────────────────────────────── -->
            <div class="mh">
              <div class="mh-left">
                <div class="msubtitle">Nav Permissions · Manage Permissions</div>
                <div class="mtitle">{{ role?.display_name || role?.name }}</div>
                <div class="mstats">
                  <span class="stat-pill">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    {{ totalMenus }} menus
                  </span>
                  <span class="stat-pill stat-pill--amber">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    {{ totalItems }} items
                  </span>
                </div>
              </div>
              <button class="mclose" @click="emit('close')">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- ── Toolbar ────────────────────────────────────────────────── -->
            <div class="toolbar" v-if="granted.length || loading">
              <div class="search-wrap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input v-model="search" placeholder="Search menus, submenus, buttons…" class="search-input" />
                <button v-if="search" class="search-clear" @click="search = ''">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div class="tb-actions">
                <button class="tb-btn" @click="allExpanded ? collapseAll() : expandAll()">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <template v-if="allExpanded"><polyline points="18 15 12 9 6 15"/></template>
                    <template v-else><polyline points="6 9 12 15 18 9"/></template>
                  </svg>
                  {{ allExpanded ? 'Collapse All' : 'Expand All' }}
                </button>
              </div>
            </div>

            <!-- ── Body ──────────────────────────────────────────────────── -->
            <div class="mbody">

              <!-- Loading -->
              <div v-if="loading" class="empty">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:#d4c4b0;margin-bottom:10px;animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"/></svg>
                <p>Loading permissions…</p>
              </div>

              <!-- Empty state -->
              <div v-else-if="!allGranted.length" class="empty">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="color:#d4c4b0;margin-bottom:10px"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <p>No nav permissions assigned to this role.</p>
              </div>

              <!-- No search results -->
              <div v-else-if="!granted.length" class="empty">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="color:#d4c4b0;margin-bottom:10px"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p>No permissions match "<strong>{{ search }}</strong>"</p>
              </div>

              <!-- Menu grid -->
              <div v-else class="mod-grid">
                <div v-for="fm in granted" :key="fm.menu.id" class="mod-card">

                  <!-- Menu header -->
                  <div
                    class="mod-hd"
                    :class="{ 'mod-hd--open': expanded.has(fm.menu.id) }"
                    @click="toggleMenu(fm.menu.id)"
                  >
                    <span class="mod-icon">
                      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span class="mod-name" v-html="highlight(fm.menu.name)"></span>
                    <span class="mod-count">{{ fm.subs.length }} sub</span>
                    <span class="mod-chevron" :class="{ 'mod-chevron--open': expanded.has(fm.menu.id) }">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                  </div>

                  <!-- Collapsible body -->
                  <Transition name="accordion">
                    <div v-if="expanded.has(fm.menu.id)" class="mod-body">
                      <div
                        v-for="(fs, si) in fm.subs"
                        :key="fs.sub.id"
                        class="sub-section"
                        :class="{ 'sub-border': si > 0 }"
                      >
                        <!-- Submenu header -->
                        <div class="sub-hd" @click="toggleSub(fs.sub.id)">
                          <span class="sub-tick">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                          </span>
                          <span class="sub-name" v-html="highlight(fs.sub.name)"></span>
                          <span v-if="fs.buttons.length" class="sub-count">{{ fs.buttons.length }}</span>
                          <span v-if="fs.buttons.length" class="sub-chevron" :class="{ 'sub-chevron--up': subCollapsed.has(fs.sub.id) }">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                          </span>
                        </div>

                        <!-- Buttons -->
                        <Transition name="accordion">
                          <div v-if="fs.buttons.length && !subCollapsed.has(fs.sub.id)" class="perm-grid">
                            <div v-for="btn in fs.buttons" :key="btn.id" class="perm-item">
                              <span class="perm-dot">
                                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                              </span>
                              <span class="perm-label" v-html="highlight(btn.name)"></span>
                            </div>
                          </div>
                        </Transition>
                      </div>

                      <!-- Menu has no submenus but is directly checked -->
                      <div v-if="!fm.subs.length" class="sub-section" style="color:#8a7f72;font-size:.73rem;font-style:italic;">
                        Menu access only
                      </div>
                    </div>
                  </Transition>

                </div>
              </div>
            </div>

            <!-- ── Footer ────────────────────────────────────────────────── -->
            <div class="mfoot">
              <span v-if="search" style="font-size:.73rem;color:#8a7f72;">
                Showing {{ granted.length }} of {{ allGranted.length }} menus
              </span>
              <span v-else></span>
              <button class="btn-close" @click="emit('close')">Close</button>
            </div>

          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
@keyframes spin { to { transform: rotate(360deg); } }

.modal { position:fixed;inset:0;background:rgba(14,11,7,.55);z-index:1000;display:flex;align-items:center;justify-content:center;padding:16px; }
.mc    { background:#faf6ef;border:1.5px solid #e0d8cc;border-radius:16px;width:100%;max-width:840px;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 28px 56px rgba(14,11,7,.22); }

.backdrop-enter-active,.backdrop-leave-active { transition:opacity .2s ease; }
.backdrop-enter-from,.backdrop-leave-to       { opacity:0; }
.panel-enter-active,.panel-leave-active       { transition:opacity .22s ease,transform .22s ease; }
.panel-enter-from,.panel-leave-to             { opacity:0;transform:scale(.96) translateY(6px); }

.mh       { display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:18px 22px 14px;border-bottom:1px solid #e0d8cc;flex-shrink:0; }
.mh-left  { display:flex;flex-direction:column;gap:5px; }
.msubtitle{ font-size:.6rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#8a7f72; }
.mtitle   { font-family:'Playfair Display',serif;font-size:1.12rem;font-weight:600;color:#0e0b07; }
.mstats   { display:flex;gap:6px;flex-wrap:wrap; }
.stat-pill{ display:inline-flex;align-items:center;gap:4px;font-size:.67rem;font-weight:600;padding:2px 8px;border-radius:20px;background:#f0e8d6;border:1px solid #e0d8cc;color:#8a7f72; }
.stat-pill--amber{ background:rgba(201,137,60,.1);border-color:rgba(201,137,60,.28);color:#a06828; }
.mclose   { width:28px;height:28px;border-radius:7px;border:1.5px solid #e0d8cc;background:#f0e8d6;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#8a7f72;flex-shrink:0;transition:all .15s; }
.mclose:hover { border-color:#c9893c;color:#c9893c; }

.toolbar    { display:flex;align-items:center;gap:10px;padding:11px 22px;border-bottom:1px solid #e0d8cc;flex-shrink:0;flex-wrap:wrap; }
.search-wrap{ display:flex;align-items:center;gap:7px;flex:1;min-width:180px;background:#fff;border:1.5px solid #e0d8cc;border-radius:9px;padding:6px 10px;transition:border-color .15s; }
.search-wrap:focus-within { border-color:#c9893c; }
.search-wrap svg { flex-shrink:0;color:#8a7f72; }
.search-input{ border:none;background:none;font-family:'DM Sans',sans-serif;font-size:.78rem;color:#0e0b07;outline:none;flex:1;min-width:0; }
.search-input::placeholder{ color:#b0a090; }
.search-clear{ background:none;border:none;cursor:pointer;display:flex;align-items:center;color:#b0a090;padding:0;transition:color .15s; }
.search-clear:hover { color:#b84b2f; }
.tb-actions { display:flex;gap:6px; }
.tb-btn     { display:inline-flex;align-items:center;gap:5px;padding:5px 12px;font-family:'DM Sans',sans-serif;font-size:.72rem;font-weight:600;border-radius:8px;border:1.5px solid #e0d8cc;background:#f0e8d6;color:#8a7f72;cursor:pointer;transition:all .15s;white-space:nowrap; }
.tb-btn:hover { border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07); }

.mbody { flex:1;overflow-y:auto;padding:16px 22px; }

.empty { display:flex;flex-direction:column;align-items:center;justify-content:center;padding:44px 24px;color:#8a7f72;font-size:.78rem;text-align:center; }

.mod-grid { display:grid;grid-template-columns:1fr 1fr;gap:12px;align-items:start; }
@media (max-width:600px) { .mod-grid { grid-template-columns:1fr; } }

.mod-card { border-radius:10px;overflow:hidden;box-shadow:0 1px 5px rgba(0,0,0,.07); }

.mod-hd {
  display:flex;align-items:center;gap:10px;padding:11px 14px;
  background:linear-gradient(135deg,#c9893c,#b84b2f);
  cursor:pointer;user-select:none;transition:filter .15s;
}
.mod-hd:hover { filter:brightness(1.08); }

.mod-icon { width:20px;height:20px;border-radius:50%;border:2px solid #fff;background:#fff;color:#b84b2f;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0; }
.mod-name   { flex:1;font-family:'DM Sans',sans-serif;font-size:.82rem;font-weight:700;color:#fff;letter-spacing:.01em; }
.mod-count  { font-size:.65rem;font-weight:700;color:rgba(255,255,255,.8);background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);border-radius:20px;padding:1px 7px;white-space:nowrap; }
.mod-chevron{ color:rgba(255,255,255,.85);display:flex;align-items:center;flex-shrink:0;margin-left:2px;transition:transform .22s ease; }
.mod-chevron--open { transform:rotate(180deg); }

.mod-body { background:#fff;border:1.5px solid rgba(201,137,60,.18);border-top:none;border-radius:0 0 10px 10px; }

.sub-section { padding:10px 14px 8px; }
.sub-border  { border-top:1px solid #f5ede0; }

.sub-hd { display:flex;align-items:center;gap:6px;margin-bottom:7px;cursor:pointer;user-select:none;padding:3px 4px;border-radius:6px;transition:background .13s; }
.sub-hd:hover { background:rgba(201,137,60,.07); }

.sub-tick   { color:#c9893c;display:inline-flex;align-items:center;flex-shrink:0; }
.sub-name   { flex:1;font-family:'DM Sans',sans-serif;font-size:.77rem;font-weight:700;color:#8a4a1e; }
.sub-count  { font-size:.63rem;font-weight:600;color:#b0956a;background:rgba(201,137,60,.1);border-radius:20px;padding:1px 6px;border:1px solid rgba(201,137,60,.2); }
.sub-chevron{ color:#c9893c;display:flex;align-items:center;flex-shrink:0;transition:transform .2s ease; }
.sub-chevron--up { transform:rotate(180deg); }

.perm-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:4px; }
.perm-item { display:flex;align-items:center;gap:6px;padding:5px 7px;border-radius:7px;cursor:default;transition:background .13s,box-shadow .13s; }
.perm-item:hover { background:rgba(201,137,60,.08);box-shadow:0 0 0 1px rgba(201,137,60,.2); }
.perm-dot  { width:16px;height:16px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,#c9893c,#b84b2f);color:#fff;display:inline-flex;align-items:center;justify-content:center; }
.perm-label { font-family:'DM Sans',sans-serif;font-size:.72rem;font-weight:500;color:#3d3529;line-height:1.2; }

:deep(mark) { background:rgba(201,137,60,.28);color:#7a3a10;border-radius:2px;padding:0 1px;font-weight:700; }

.accordion-enter-active { transition:all .24s ease;overflow:hidden;max-height:800px; }
.accordion-leave-active { transition:all .2s ease;overflow:hidden;max-height:800px; }
.accordion-enter-from   { max-height:0;opacity:0; }
.accordion-leave-to     { max-height:0;opacity:0; }

.mfoot { padding:11px 22px 15px;border-top:1px solid #e0d8cc;display:flex;justify-content:space-between;align-items:center;flex-shrink:0; }
.btn-close { padding:7px 20px;font-family:'DM Sans',sans-serif;font-size:.78rem;font-weight:600;border-radius:9px;border:1.5px solid #e0d8cc;background:#f0e8d6;color:#8a7f72;cursor:pointer;transition:all .15s; }
.btn-close:hover { border-color:#c9893c;color:#c9893c;background:rgba(201,137,60,.07); }
</style>
