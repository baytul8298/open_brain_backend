<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

interface NavParentMenu {
  id: number
  name: string
  type: 'admin' | 'teacher' | 'student'
  guard: string
  sort_order: number
  is_active: boolean
}

interface NavMenu {
  id: number
  parent_menu_id: number | null
  name: string
  type: 'admin' | 'teacher' | 'student'
  key: string
  icon_key: string | null
  to: string | null
  search_key: string | null
  guard: string
  sort_order: number
  is_active: boolean
}

interface NavSubmenu {
  id: number
  menu_id: number
  name: string
  search_key: string | null
  to: string | null
  sort_order: number
  is_active: boolean
}

interface NavButton {
  id: number
  submenu_id: number
  name: string
  key_value: string
  sort_order: number
  is_active: boolean
}

interface Props {
  parentMenus: NavParentMenu[]
  menus: NavMenu[]
}

const props = defineProps<Props>()

// State
const menuSearch = ref('')
const submenuSearch = ref('')
const buttonSearch = ref('')

const selectedMenu = ref<NavMenu | null>(null)
const selectedSubmenu = ref<NavSubmenu | null>(null)
const submenus = ref<NavSubmenu[]>([])
const buttons = ref<NavButton[]>([])

// Modal state
const showMenuModal = ref(false)
const showSubmenuModal = ref(false)
const showButtonModal = ref(false)
const showParentModal = ref(false)
const showDeleteConfirm = ref(false)

const deleteTarget = ref<{ type: 'menu' | 'submenu' | 'button' | 'parent', id: number, name: string } | null>(null)

// Parent menu local copy + drag
const localParents = ref<NavParentMenu[]>([...props.parentMenus])
watch(() => props.parentMenus, (val) => { localParents.value = [...val] }, { deep: true })

const parentDragId = ref<number | null>(null)
const parentDragMoved = ref(false)

function onParentDragStart(id: number, e: DragEvent) {
  parentDragId.value = id
  parentDragMoved.value = false
  if (e.dataTransfer) e.dataTransfer.effectAllowed = 'move'
}
function onParentDragOver(id: number, e: DragEvent) {
  e.preventDefault()
  if (!parentDragId.value || parentDragId.value === id) return
  const arr = [...localParents.value]
  const fromIdx = arr.findIndex(p => p.id === parentDragId.value)
  const toIdx = arr.findIndex(p => p.id === id)
  if (fromIdx === -1 || toIdx === -1) return
  const [item] = arr.splice(fromIdx, 1)
  arr.splice(toIdx, 0, item)
  localParents.value = arr
  parentDragMoved.value = true
}
async function onParentDragEnd() {
  if (parentDragMoved.value) {
    await axios.post('/parent-menus/reorder-batch', { ids: localParents.value.map(p => p.id) })
    router.reload({ only: ['parentMenus'] })
  }
  parentDragId.value = null
  parentDragMoved.value = false
}

// Parent menu form
const parentModalMode = ref<'create' | 'edit'>('create')
const parentForm = ref({
  id: null as number | null,
  name: '',
  type: 'admin' as 'admin' | 'teacher' | 'student',
  guard: 'web',
  sort_order: 0,
  is_active: true,
})

function openAddParent() {
  parentModalMode.value = 'create'
  parentForm.value = { id: null, name: '', type: 'admin', guard: 'web', sort_order: localParents.value.length, is_active: true }
  showParentModal.value = true
}
function openEditParent(p: NavParentMenu) {
  parentModalMode.value = 'edit'
  parentForm.value = { id: p.id, name: p.name, type: p.type, guard: p.guard, sort_order: p.sort_order, is_active: p.is_active }
  showParentModal.value = true
}
function submitParent() {
  if (parentModalMode.value === 'create') {
    router.post('/parent-menus', {
      name: parentForm.value.name,
      type: parentForm.value.type,
      guard: parentForm.value.guard,
      sort_order: parentForm.value.sort_order,
      is_active: parentForm.value.is_active,
    }, { onSuccess: () => { showParentModal.value = false } })
  } else {
    router.put(`/parent-menus/${parentForm.value.id}`, {
      name: parentForm.value.name,
      type: parentForm.value.type,
      guard: parentForm.value.guard,
      sort_order: parentForm.value.sort_order,
      is_active: parentForm.value.is_active,
    }, { onSuccess: () => { showParentModal.value = false } })
  }
}
function confirmDeleteParent(p: NavParentMenu) {
  deleteTarget.value = { type: 'parent', id: p.id, name: p.name }
  showDeleteConfirm.value = true
}

// Form state
const menuForm = ref({
  id: null as number | null,
  parent_menu_id: null as number | null,
  name: '',
  type: 'admin' as 'admin' | 'teacher' | 'student',
  key: '',
  icon_key: '',
  to: '',
  search_key: '',
  guard: 'web',
  sort_order: 0,
  is_active: true,
})

const submenuForm = ref({
  id: null as number | null,
  menu_id: 0,
  name: '',
  search_key: '',
  to: '',
  sort_order: 0,
  is_active: true,
})

const buttonForm = ref({
  id: null as number | null,
  submenu_id: 0,
  name: '',
  key_value: '',
  sort_order: 0,
  is_active: true,
})

const menuModalMode = ref<'create' | 'edit'>('create')
const submenuModalMode = ref<'create' | 'edit'>('create')
const buttonModalMode = ref<'create' | 'edit'>('create')

// Local mutable copies for drag-reorder (Inertia props are readonly)
const localMenus = ref<NavMenu[]>([...props.menus])
watch(() => props.menus, (val) => { localMenus.value = [...val] }, { deep: true })

// Drag state — track by item ID, null when not dragging
const menuDragId = ref<number | null>(null)
const menuDragMoved = ref(false)
const submenuDragId = ref<number | null>(null)
const submenuDragMoved = ref(false)
const buttonDragId = ref<number | null>(null)
const buttonDragMoved = ref(false)

// Computed filtered lists
const filteredMenus = computed(() => {
  if (!menuSearch.value) return localMenus.value
  const q = menuSearch.value.toLowerCase()
  return localMenus.value.filter(m =>
    m.name.toLowerCase().includes(q) || m.key.toLowerCase().includes(q)
  )
})

const filteredSubmenus = computed(() => {
  if (!submenuSearch.value) return submenus.value
  const q = submenuSearch.value.toLowerCase()
  return submenus.value.filter(s => s.name.toLowerCase().includes(q))
})

const filteredButtons = computed(() => {
  if (!buttonSearch.value) return buttons.value
  const q = buttonSearch.value.toLowerCase()
  return buttons.value.filter(b =>
    b.name.toLowerCase().includes(q) || b.key_value.toLowerCase().includes(q)
  )
})

// Menu actions
async function selectMenu(menu: NavMenu) {
  selectedMenu.value = menu
  selectedSubmenu.value = null
  buttons.value = []
  submenuSearch.value = ''
  buttonSearch.value = ''
  const res = await axios.get(`/menus/${menu.id}/submenus`)
  submenus.value = res.data
}

async function selectSubmenu(sub: NavSubmenu) {
  selectedSubmenu.value = sub
  buttonSearch.value = ''
  const res = await axios.get(`/submenus/${sub.id}/buttons`)
  buttons.value = res.data
}

function openAddMenu() {
  menuModalMode.value = 'create'
  menuForm.value = { id: null, parent_menu_id: null, name: '', type: 'admin', key: '', icon_key: '', to: '', search_key: '', guard: 'web', sort_order: props.menus.length, is_active: true }
  showMenuModal.value = true
}

function openEditMenu(menu: NavMenu) {
  menuModalMode.value = 'edit'
  menuForm.value = { id: menu.id, parent_menu_id: menu.parent_menu_id ?? null, name: menu.name, type: menu.type, key: menu.key, icon_key: menu.icon_key || '', to: menu.to || '', search_key: menu.search_key || '', guard: menu.guard, sort_order: menu.sort_order, is_active: menu.is_active }
  showMenuModal.value = true
}

function submitMenu() {
  const payload = {
    parent_menu_id: menuForm.value.parent_menu_id || null,
    name: menuForm.value.name,
    type: menuForm.value.type,
    key: menuForm.value.key,
    icon_key: menuForm.value.icon_key || null,
    to: menuForm.value.to || null,
    search_key: menuForm.value.search_key || null,
    guard: menuForm.value.guard,
    sort_order: menuForm.value.sort_order,
    is_active: menuForm.value.is_active,
  }
  if (menuModalMode.value === 'create') {
    router.post('/menus', payload, { onSuccess: () => { showMenuModal.value = false } })
  } else {
    router.put(`/menus/${menuForm.value.id}`, payload, { onSuccess: () => { showMenuModal.value = false } })
  }
}

function confirmDeleteMenu(menu: NavMenu) {
  deleteTarget.value = { type: 'menu', id: menu.id, name: menu.name }
  showDeleteConfirm.value = true
}

function onMenuDragStart(id: number, e: DragEvent) {
  menuDragId.value = id
  menuDragMoved.value = false
  if (e.dataTransfer) e.dataTransfer.effectAllowed = 'move'
}
function onMenuDragOver(id: number, e: DragEvent) {
  e.preventDefault()
  if (!menuDragId.value || menuDragId.value === id) return
  const arr = [...localMenus.value]
  const fromIdx = arr.findIndex(m => m.id === menuDragId.value)
  const toIdx = arr.findIndex(m => m.id === id)
  if (fromIdx === -1 || toIdx === -1) return
  const [item] = arr.splice(fromIdx, 1)
  arr.splice(toIdx, 0, item)
  localMenus.value = arr
  menuDragMoved.value = true
}
async function onMenuDragEnd() {
  if (menuDragMoved.value) {
    await axios.post('/menus/reorder-batch', { ids: localMenus.value.map(m => m.id) })
    router.reload({ only: ['menus'] })
  }
  menuDragId.value = null
  menuDragMoved.value = false
}

// Submenu actions
function openAddSubmenu() {
  if (!selectedMenu.value) return
  submenuModalMode.value = 'create'
  submenuForm.value = { id: null, menu_id: selectedMenu.value.id, name: '', search_key: '', to: '', sort_order: submenus.value.length, is_active: true }
  showSubmenuModal.value = true
}

function openEditSubmenu(sub: NavSubmenu) {
  submenuModalMode.value = 'edit'
  submenuForm.value = { id: sub.id, menu_id: sub.menu_id, name: sub.name, search_key: sub.search_key || '', to: sub.to || '', sort_order: sub.sort_order, is_active: sub.is_active }
  showSubmenuModal.value = true
}

function submitSubmenu() {
  if (submenuModalMode.value === 'create') {
    router.post('/submenus', {
      menu_id: submenuForm.value.menu_id,
      name: submenuForm.value.name,
      search_key: submenuForm.value.search_key || null,
      to: submenuForm.value.to || null,
      sort_order: submenuForm.value.sort_order,
      is_active: submenuForm.value.is_active,
    }, {
      onSuccess: async () => {
        showSubmenuModal.value = false
        if (selectedMenu.value) {
          const res = await axios.get(`/menus/${selectedMenu.value.id}/submenus`)
          submenus.value = res.data
        }
      }
    })
  } else {
    router.put(`/submenus/${submenuForm.value.id}`, {
      name: submenuForm.value.name,
      search_key: submenuForm.value.search_key || null,
      to: submenuForm.value.to || null,
      sort_order: submenuForm.value.sort_order,
      is_active: submenuForm.value.is_active,
    }, {
      onSuccess: async () => {
        showSubmenuModal.value = false
        if (selectedMenu.value) {
          const res = await axios.get(`/menus/${selectedMenu.value.id}/submenus`)
          submenus.value = res.data
        }
      }
    })
  }
}

function confirmDeleteSubmenu(sub: NavSubmenu) {
  deleteTarget.value = { type: 'submenu', id: sub.id, name: sub.name }
  showDeleteConfirm.value = true
}

function onSubmenuDragStart(id: number, e: DragEvent) {
  submenuDragId.value = id
  submenuDragMoved.value = false
  if (e.dataTransfer) e.dataTransfer.effectAllowed = 'move'
}
function onSubmenuDragOver(id: number, e: DragEvent) {
  e.preventDefault()
  if (!submenuDragId.value || submenuDragId.value === id) return
  const arr = [...submenus.value]
  const fromIdx = arr.findIndex(s => s.id === submenuDragId.value)
  const toIdx = arr.findIndex(s => s.id === id)
  if (fromIdx === -1 || toIdx === -1) return
  const [item] = arr.splice(fromIdx, 1)
  arr.splice(toIdx, 0, item)
  submenus.value = arr
  submenuDragMoved.value = true
}
async function onSubmenuDragEnd() {
  if (submenuDragMoved.value) {
    await axios.post('/submenus/reorder-batch', { ids: submenus.value.map(s => s.id) })
  }
  submenuDragId.value = null
  submenuDragMoved.value = false
}

function toggleSubmenuActive(sub: NavSubmenu) {
  router.put(`/submenus/${sub.id}`, {
    name: sub.name,
    search_key: sub.search_key,
    to: sub.to,
    sort_order: sub.sort_order,
    is_active: !sub.is_active,
  }, {
    onSuccess: async () => {
      if (selectedMenu.value) {
        const res = await axios.get(`/menus/${selectedMenu.value.id}/submenus`)
        submenus.value = res.data
      }
    }
  })
}

// Button actions
function openAddButton() {
  if (!selectedSubmenu.value) return
  buttonModalMode.value = 'create'
  buttonForm.value = { id: null, submenu_id: selectedSubmenu.value.id, name: '', key_value: '', sort_order: buttons.value.length, is_active: true }
  showButtonModal.value = true
}

function openEditButton(btn: NavButton) {
  buttonModalMode.value = 'edit'
  buttonForm.value = { id: btn.id, submenu_id: btn.submenu_id, name: btn.name, key_value: btn.key_value, sort_order: btn.sort_order, is_active: btn.is_active }
  showButtonModal.value = true
}

function submitButton() {
  if (buttonModalMode.value === 'create') {
    router.post('/buttons', {
      submenu_id: buttonForm.value.submenu_id,
      name: buttonForm.value.name,
      key_value: buttonForm.value.key_value,
      sort_order: buttonForm.value.sort_order,
      is_active: buttonForm.value.is_active,
    }, {
      onSuccess: async () => {
        showButtonModal.value = false
        if (selectedSubmenu.value) {
          const res = await axios.get(`/submenus/${selectedSubmenu.value.id}/buttons`)
          buttons.value = res.data
        }
      }
    })
  } else {
    router.put(`/buttons/${buttonForm.value.id}`, {
      name: buttonForm.value.name,
      key_value: buttonForm.value.key_value,
      sort_order: buttonForm.value.sort_order,
      is_active: buttonForm.value.is_active,
    }, {
      onSuccess: async () => {
        showButtonModal.value = false
        if (selectedSubmenu.value) {
          const res = await axios.get(`/submenus/${selectedSubmenu.value.id}/buttons`)
          buttons.value = res.data
        }
      }
    })
  }
}

function confirmDeleteButton(btn: NavButton) {
  deleteTarget.value = { type: 'button', id: btn.id, name: btn.name }
  showDeleteConfirm.value = true
}

function onButtonDragStart(id: number, e: DragEvent) {
  buttonDragId.value = id
  buttonDragMoved.value = false
  if (e.dataTransfer) e.dataTransfer.effectAllowed = 'move'
}
function onButtonDragOver(id: number, e: DragEvent) {
  e.preventDefault()
  if (!buttonDragId.value || buttonDragId.value === id) return
  const arr = [...buttons.value]
  const fromIdx = arr.findIndex(b => b.id === buttonDragId.value)
  const toIdx = arr.findIndex(b => b.id === id)
  if (fromIdx === -1 || toIdx === -1) return
  const [item] = arr.splice(fromIdx, 1)
  arr.splice(toIdx, 0, item)
  buttons.value = arr
  buttonDragMoved.value = true
}
async function onButtonDragEnd() {
  if (buttonDragMoved.value) {
    await axios.post('/buttons/reorder-batch', { ids: buttons.value.map(b => b.id) })
  }
  buttonDragId.value = null
  buttonDragMoved.value = false
}

function toggleButtonActive(btn: NavButton) {
  router.put(`/buttons/${btn.id}`, {
    name: btn.name,
    key_value: btn.key_value,
    sort_order: btn.sort_order,
    is_active: !btn.is_active,
  }, {
    onSuccess: async () => {
      if (selectedSubmenu.value) {
        const res = await axios.get(`/submenus/${selectedSubmenu.value.id}/buttons`)
        buttons.value = res.data
      }
    }
  })
}

function executeDelete() {
  if (!deleteTarget.value) return
  const { type, id } = deleteTarget.value
  if (type === 'parent') {
    router.delete(`/parent-menus/${id}`, {
      onSuccess: () => { showDeleteConfirm.value = false }
    })
  } else if (type === 'menu') {
    router.delete(`/menus/${id}`, {
      onSuccess: () => {
        showDeleteConfirm.value = false
        if (selectedMenu.value?.id === id) {
          selectedMenu.value = null
          submenus.value = []
          buttons.value = []
          selectedSubmenu.value = null
        }
      }
    })
  } else if (type === 'submenu') {
    router.delete(`/submenus/${id}`, {
      onSuccess: async () => {
        showDeleteConfirm.value = false
        if (selectedSubmenu.value?.id === id) {
          selectedSubmenu.value = null
          buttons.value = []
        }
        if (selectedMenu.value) {
          const res = await axios.get(`/menus/${selectedMenu.value.id}/submenus`)
          submenus.value = res.data
        }
      }
    })
  } else {
    router.delete(`/buttons/${id}`, {
      onSuccess: async () => {
        showDeleteConfirm.value = false
        if (selectedSubmenu.value) {
          const res = await axios.get(`/submenus/${selectedSubmenu.value.id}/buttons`)
          buttons.value = res.data
        }
      }
    })
  }
}

function refresh() {
  router.reload()
}
</script>

<template>
  <Head title="Menu Manager" />
  <AppLayout>
    <div style="padding:28px 26px">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px">
        <div>
          <h1 style="font-size:1.5rem;font-weight:700;color:#1a1208;margin:0 0 4px">Menu Manager</h1>
          <p style="font-size:.8rem;color:#888;margin:0">Left: Menus &bull; Middle: Submenus &bull; Right: Buttons (submenu-wise)</p>
        </div>
        <button @click="refresh" style="padding:7px 16px;background:#fff;border:1px solid #e2d5c3;border-radius:8px;font-size:.8rem;font-weight:500;cursor:pointer;color:#5a4a36">Refresh</button>
      </div>

      <!-- Parent Menus panel -->
      <div style="background:#fff;border:1px solid #e8ddd0;border-radius:12px;margin-bottom:20px;overflow:hidden">
        <div style="padding:12px 16px;border-bottom:1px solid #f0e8dc;display:flex;align-items:center;gap:10px">
          <span style="font-weight:700;font-size:.85rem;color:#1a1208;flex:1">Parent Menus <span style="font-size:.7rem;font-weight:400;color:#aaa">(section headers shown in sidebar)</span></span>
          <button @click="openAddParent" style="padding:5px 14px;background:#4338ca;color:#fff;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap">+ Add Parent Menu</button>
        </div>
        <div style="padding:10px 16px;display:flex;flex-wrap:wrap;gap:8px;min-height:48px">
          <div v-if="localParents.length === 0" style="font-size:.8rem;color:#bbb;line-height:28px">No parent menus yet. Add one to create sidebar sections.</div>
          <div
            v-for="p in localParents" :key="p.id"
            draggable="true"
            @dragstart="onParentDragStart(p.id, $event)"
            @dragover="onParentDragOver(p.id, $event)"
            @dragend="onParentDragEnd"
            :style="{
              display:'inline-flex',alignItems:'center',gap:'6px',
              padding:'5px 10px 5px 8px',
              background: parentDragId === p.id ? '#f5f0ea' : '#faf6ef',
              border:'1px solid #e8ddd0',borderRadius:'8px',
              opacity: parentDragId === p.id ? 0.4 : 1,
              cursor:'grab',transition:'opacity .15s',
            }"
          >
            <span style="font-size:.65rem;color:#bbb;cursor:grab">&#8597;</span>
            <span style="font-size:.8rem;font-weight:600;color:#1a1208">{{ p.name }}</span>
            <span :style="{fontSize:'.6rem',fontWeight:700,padding:'1px 6px',borderRadius:'20px',background: p.type==='admin'?'#ede9fe':p.type==='teacher'?'#dbeafe':'#dcfce7',color: p.type==='admin'?'#6d28d9':p.type==='teacher'?'#1d4ed8':'#15803d'}">{{ p.type }}</span>
            <span :style="{fontSize:'.65rem',fontWeight:600,color: p.is_active ? '#16a34a' : '#dc2626'}">{{ p.is_active ? '●' : '○' }}</span>
            <button @click.stop="openEditParent(p)" style="padding:1px 6px;background:none;border:1px solid #c9893c;border-radius:4px;font-size:.65rem;cursor:pointer;color:#c9893c;font-weight:500">Edit</button>
            <button @click.stop="confirmDeleteParent(p)" style="padding:1px 6px;background:none;border:1px solid #dc2626;border-radius:4px;font-size:.65rem;cursor:pointer;color:#dc2626;font-weight:500">Del</button>
          </div>
        </div>
      </div>

      <!-- 3-column layout -->
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;align-items:start">

        <!-- COLUMN 1: Menu List -->
        <div style="background:#fff;border:1px solid #e8ddd0;border-radius:12px;overflow:hidden">
          <div style="padding:14px 16px;border-bottom:1px solid #f0e8dc;display:flex;align-items:center;gap:10px">
            <span style="font-weight:600;font-size:.9rem;color:#1a1208;flex:1">Menu List</span>
            <input v-model="menuSearch" placeholder="Search..." style="flex:1;padding:6px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.78rem;outline:none" />
            <button @click="openAddMenu" style="padding:6px 14px;background:#4338ca;color:#fff;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap">+ Add Menu</button>
          </div>
          <div style="max-height:68vh;overflow-y:auto">
            <div
              v-for="menu in filteredMenus" :key="menu.id"
              draggable="true"
              @click="selectMenu(menu)"
              @dragstart="onMenuDragStart(menu.id, $event)"
              @dragover="onMenuDragOver(menu.id, $event)"
              @dragend="onMenuDragEnd"
              :style="{
                padding:'12px 16px',
                borderBottom:'1px solid #f5f0ea',
                cursor:'pointer',
                background: selectedMenu?.id === menu.id ? '#fef7ed' : 'transparent',
                borderLeft: selectedMenu?.id === menu.id ? '3px solid #c9893c' : '3px solid transparent',
                opacity: menuDragId === menu.id ? 0.4 : 1,
                transition: 'opacity .15s',
              }"
            >
              <div style="display:flex;align-items:center;justify-content:space-between">
                <div style="flex:1;min-width:0">
                  <div style="font-weight:600;font-size:.85rem;color:#1a1208;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ menu.name }}</div>
                  <div style="font-size:.72rem;color:#888;margin-top:2px;display:flex;align-items:center;flex-wrap:wrap;gap:4px">
                    Key: {{ menu.key }} &nbsp; Order: {{ menu.sort_order }}
                    <span :style="{color: menu.is_active ? '#16a34a' : '#dc2626', fontWeight:600}">{{ menu.is_active ? 'Active' : 'Inactive' }}</span>
                    <span :style="{fontSize:'.6rem',fontWeight:700,padding:'1px 6px',borderRadius:'20px',background: menu.type==='admin'?'#ede9fe':menu.type==='teacher'?'#dbeafe':'#dcfce7',color: menu.type==='admin'?'#6d28d9':menu.type==='teacher'?'#1d4ed8':'#15803d'}">{{ menu.type }}</span>
                  </div>
                  <div v-if="menu.to" style="font-size:.7rem;color:#666;margin-top:1px">To: {{ menu.to }}</div>
                  <div v-if="menu.parent_menu_id" style="font-size:.7rem;color:#c9893c;margin-top:1px">Parent: {{ localParents.find(p => p.id === menu.parent_menu_id)?.name || '—' }}</div>
                </div>
                <div style="display:flex;gap:4px;margin-left:8px;flex-shrink:0" @click.stop>
                  <button style="padding:3px 7px;background:none;border:1px solid #e2d5c3;border-radius:5px;font-size:.75rem;cursor:grab;color:#999;line-height:1" title="Drag to reorder">&#8597;</button>
                  <button @click="openEditMenu(menu)" style="padding:3px 8px;background:none;border:1px solid #c9893c;border-radius:5px;font-size:.72rem;cursor:pointer;color:#c9893c;font-weight:500">Edit</button>
                  <button @click="confirmDeleteMenu(menu)" style="padding:3px 8px;background:none;border:1px solid #dc2626;border-radius:5px;font-size:.72rem;cursor:pointer;color:#dc2626;font-weight:500">Delete</button>
                </div>
              </div>
            </div>
            <div v-if="filteredMenus.length === 0" style="padding:32px;text-align:center;color:#aaa;font-size:.85rem">No menus found.</div>
          </div>
        </div>

        <!-- COLUMN 2: Submenus -->
        <div style="background:#fff;border:1px solid #e8ddd0;border-radius:12px;overflow:hidden">
          <div style="padding:14px 16px;border-bottom:1px solid #f0e8dc;display:flex;align-items:center;gap:10px">
            <span style="font-weight:600;font-size:.9rem;color:#1a1208;flex:1">Submenus</span>
            <input v-model="submenuSearch" placeholder="Search submenus..." style="flex:1;padding:6px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.78rem;outline:none" />
            <button @click="openAddSubmenu" :disabled="!selectedMenu" style="padding:6px 14px;background:#4338ca;color:#fff;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap" :style="{ opacity: selectedMenu ? 1 : 0.5 }">+ Add Submenu</button>
          </div>
          <div style="max-height:68vh;overflow-y:auto">
            <div v-if="!selectedMenu" style="padding:32px;text-align:center">
              <p style="color:#c9893c;font-weight:600;font-size:.85rem;margin-bottom:6px">Select a menu from left</p>
              <p style="color:#aaa;font-size:.8rem">No menu selected.</p>
            </div>
            <template v-else>
              <div style="padding:8px 16px;background:#fef7ed;border-bottom:1px solid #f0e8dc;font-size:.78rem;font-weight:600;color:#c9893c">Menu: {{ selectedMenu.name }}</div>
              <div
                v-for="sub in filteredSubmenus" :key="sub.id"
                draggable="true"
                @click="selectSubmenu(sub)"
                @dragstart="onSubmenuDragStart(sub.id, $event)"
                @dragover="onSubmenuDragOver(sub.id, $event)"
                @dragend="onSubmenuDragEnd"
                :style="{
                  padding:'11px 16px',
                  borderBottom:'1px solid #f5f0ea',
                  cursor:'pointer',
                  background: selectedSubmenu?.id === sub.id ? '#fef7ed' : 'transparent',
                  borderLeft: selectedSubmenu?.id === sub.id ? '3px solid #c9893c' : '3px solid transparent',
                  opacity: submenuDragId === sub.id ? 0.4 : 1,
                  transition: 'opacity .15s',
                }"
              >
                <div style="display:flex;align-items:center;justify-content:space-between">
                  <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:.85rem;color:#1a1208">{{ sub.name }}</div>
                    <div style="font-size:.7rem;color:#888;margin-top:2px">To: {{ sub.to || '—' }} &bull; Order: {{ sub.sort_order }}</div>
                  </div>
                  <div style="display:flex;gap:4px;margin-left:8px;flex-shrink:0" @click.stop>
                    <button style="padding:3px 7px;background:none;border:1px solid #e2d5c3;border-radius:5px;font-size:.75rem;cursor:grab;color:#999;line-height:1" title="Drag to reorder">&#8597;</button>
                    <button
                      @click="toggleSubmenuActive(sub)"
                      :style="{padding:'3px 8px',background:'none',border:'1px solid',borderColor: sub.is_active ? '#16a34a' : '#dc2626',borderRadius:'5px',fontSize:'.72rem',cursor:'pointer',color: sub.is_active ? '#16a34a' : '#dc2626',fontWeight:500}"
                    >{{ sub.is_active ? 'Active' : 'Inactive' }}</button>
                    <button @click="openEditSubmenu(sub)" style="padding:3px 8px;background:none;border:1px solid #c9893c;border-radius:5px;font-size:.72rem;cursor:pointer;color:#c9893c;font-weight:500">Edit</button>
                    <button @click="confirmDeleteSubmenu(sub)" style="padding:3px 8px;background:none;border:1px solid #dc2626;border-radius:5px;font-size:.72rem;cursor:pointer;color:#dc2626;font-weight:500">Delete</button>
                  </div>
                </div>
              </div>
              <div v-if="filteredSubmenus.length === 0" style="padding:32px;text-align:center;color:#aaa;font-size:.85rem">No submenus found.</div>
            </template>
          </div>
        </div>

        <!-- COLUMN 3: Button List -->
        <div style="background:#fff;border:1px solid #e8ddd0;border-radius:12px;overflow:hidden">
          <div style="padding:14px 16px;border-bottom:1px solid #f0e8dc;display:flex;align-items:center;gap:10px">
            <span style="font-weight:600;font-size:.9rem;color:#1a1208;flex:1">Button List</span>
            <input v-model="buttonSearch" placeholder="Search buttons..." style="flex:1;padding:6px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.78rem;outline:none" />
            <button @click="openAddButton" :disabled="!selectedSubmenu" style="padding:6px 14px;background:#4338ca;color:#fff;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap" :style="{ opacity: selectedSubmenu ? 1 : 0.5 }">+ Add Button</button>
          </div>
          <div style="max-height:68vh;overflow-y:auto">
            <div v-if="!selectedSubmenu" style="padding:32px;text-align:center">
              <p style="color:#c9893c;font-weight:600;font-size:.85rem;margin-bottom:6px">Select a Submenu List from left</p>
              <p style="color:#aaa;font-size:.8rem">No menu selected.</p>
            </div>
            <template v-else>
              <div style="padding:8px 16px;background:#fef7ed;border-bottom:1px solid #f0e8dc;font-size:.78rem;font-weight:600;color:#c9893c">Submenu: {{ selectedSubmenu.name }}</div>
              <div
                v-for="btn in filteredButtons" :key="btn.id"
                draggable="true"
                @dragstart="onButtonDragStart(btn.id, $event)"
                @dragover="onButtonDragOver(btn.id, $event)"
                @dragend="onButtonDragEnd"
                :style="{
                  padding:'11px 16px',
                  borderBottom:'1px solid #f5f0ea',
                  opacity: buttonDragId === btn.id ? 0.4 : 1,
                  transition: 'opacity .15s',
                }"
              >
                <div style="display:flex;align-items:center;justify-content:space-between">
                  <div>
                    <div style="font-weight:600;font-size:.85rem;color:#1a1208">{{ btn.name }}</div>
                    <div style="font-size:.7rem;color:#888;margin-top:2px">Order: {{ btn.sort_order }} &bull; {{ btn.key_value }}</div>
                  </div>
                  <div style="display:flex;gap:4px;flex-shrink:0" @click.stop>
                    <button style="padding:3px 7px;background:none;border:1px solid #e2d5c3;border-radius:5px;font-size:.75rem;cursor:grab;color:#999;line-height:1" title="Drag to reorder">&#8597;</button>
                    <button
                      @click="toggleButtonActive(btn)"
                      :style="{padding:'3px 8px',background:'none',border:'1px solid',borderColor: btn.is_active ? '#16a34a' : '#dc2626',borderRadius:'5px',fontSize:'.72rem',cursor:'pointer',color: btn.is_active ? '#16a34a' : '#dc2626',fontWeight:500}"
                    >{{ btn.is_active ? 'Active' : 'Inactive' }}</button>
                    <button @click="openEditButton(btn)" style="padding:3px 8px;background:none;border:1px solid #c9893c;border-radius:5px;font-size:.72rem;cursor:pointer;color:#c9893c;font-weight:500">Edit</button>
                    <button @click="confirmDeleteButton(btn)" style="padding:3px 8px;background:none;border:1px solid #dc2626;border-radius:5px;font-size:.72rem;cursor:pointer;color:#dc2626;font-weight:500">Delete</button>
                  </div>
                </div>
              </div>
              <div v-if="filteredButtons.length === 0" style="padding:32px;text-align:center;color:#aaa;font-size:.85rem">No buttons found.</div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- MENU MODAL -->
    <div v-if="showMenuModal" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:flex;align-items:center;justify-content:center">
      <div style="background:#fff;border-radius:14px;padding:28px;width:560px;max-width:95vw;max-height:90vh;overflow-y:auto;position:relative">
        <button @click="showMenuModal=false" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#999">&times;</button>
        <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 4px">{{ menuModalMode === 'create' ? 'Add Menu' : 'Edit Menu #' + menuForm.name }}</h2>
        <p style="font-size:.78rem;color:#888;margin:0 0 20px">Create or update menu information.</p>

        <!-- Mode indicator -->
        <div style="background:#f8f5f0;border-radius:8px;padding:8px 12px;margin-bottom:16px;font-size:.78rem;color:#666">
          Mode: {{ menuModalMode === 'create' ? 'Creating New Menu' : 'Editing Menu #' + menuForm.name }}
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Menu Name/Label</label>
            <input v-model="menuForm.name" placeholder="e.g. Business Management" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Key (unique)</label>
            <input v-model="menuForm.key" placeholder="e.g. business_management" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Icon Key</label>
            <input v-model="menuForm.icon_key" placeholder="e.g. company" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">To (route optional)</label>
            <input v-model="menuForm.to" placeholder="Group menu? keep empty" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Parent Menu <span style="font-weight:400;color:#aaa">(sidebar section)</span></label>
            <select v-model="menuForm.parent_menu_id" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;background:#fff">
              <option :value="null">— No parent —</option>
              <option v-for="p in localParents" :key="p.id" :value="p.id">{{ p.name }} ({{ p.type.charAt(0).toUpperCase() + p.type.slice(1) }})</option>
            </select>
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Search Key (optional)</label>
            <input v-model="menuForm.search_key" placeholder="e.g. Manage Business" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Type <span style="color:red">*</span></label>
            <select v-model="menuForm.type" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;background:#fff">
              <option value="admin">Admin</option>
              <option value="teacher">Teacher</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Guard</label>
            <select v-model="menuForm.guard" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;background:#fff">
              <option value="web">web</option>
              <option value="admin">admin</option>
            </select>
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Sort Order</label>
            <input v-model.number="menuForm.sort_order" type="number" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;background:#f8f5f0;padding:12px 14px;border-radius:8px;margin-bottom:20px">
          <div>
            <div style="font-size:.82rem;font-weight:600;color:#333">Active</div>
            <div style="font-size:.72rem;color:#888">Show this menu in UI</div>
          </div>
          <button @click="menuForm.is_active = !menuForm.is_active" :style="{width:'42px',height:'24px',borderRadius:'12px',border:'none',cursor:'pointer',background: menuForm.is_active ? '#16a34a' : '#ddd',position:'relative',transition:'background .2s'}">
            <span :style="{position:'absolute',top:'2px',left: menuForm.is_active ? '20px' : '2px',width:'20px',height:'20px',background:'#fff',borderRadius:'50%',transition:'left .2s',display:'block'}"></span>
          </button>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px">
          <button @click="showMenuModal=false" style="padding:8px 18px;border:1px solid #e2d5c3;border-radius:8px;background:#fff;font-size:.83rem;cursor:pointer;color:#555">Cancel</button>
          <button @click="submitMenu" style="padding:8px 22px;background:#4338ca;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer">{{ menuModalMode === 'create' ? 'Create Menu' : 'Update Menu' }}</button>
        </div>
      </div>
    </div>

    <!-- SUBMENU MODAL -->
    <div v-if="showSubmenuModal" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:flex;align-items:center;justify-content:center">
      <div style="background:#fff;border-radius:14px;padding:28px;width:520px;max-width:95vw;position:relative">
        <button @click="showSubmenuModal=false" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#999">&times;</button>
        <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 2px">{{ submenuModalMode === 'create' ? 'Add Submenu' : 'Edit Submenu #' + submenuForm.name }}</h2>
        <p style="font-size:.78rem;color:#888;margin:0 0 16px">Parent: {{ selectedMenu?.name }}</p>

        <div style="background:#f8f5f0;border-radius:8px;padding:8px 12px;margin-bottom:16px;font-size:.78rem;color:#666;display:flex;justify-content:space-between">
          <span>Selected Menu: {{ selectedMenu?.name }} (#{{ selectedMenu?.id }})</span>
          <span>{{ submenuModalMode === 'create' ? 'Creating New Submenu' : 'Editing Submenu #' + submenuForm.id }}</span>
        </div>

        <div style="margin-bottom:14px">
          <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Sub Menu/Sub Label</label>
          <input v-model="submenuForm.name" placeholder="e.g. Pending Approval" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
        </div>
        <div style="margin-bottom:14px">
          <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Search Key (optional)</label>
          <input v-model="submenuForm.search_key" placeholder="e.g. Manage Business" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">To (route)</label>
            <input v-model="submenuForm.to" placeholder="e.g. /admin/premium/reranking" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Sort Order</label>
            <input v-model.number="submenuForm.sort_order" type="number" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;background:#f8f5f0;padding:12px 14px;border-radius:8px;margin-bottom:20px">
          <div>
            <div style="font-size:.82rem;font-weight:600;color:#333">Active</div>
            <div style="font-size:.72rem;color:#888">Visible submenu</div>
          </div>
          <button @click="submenuForm.is_active = !submenuForm.is_active" :style="{width:'42px',height:'24px',borderRadius:'12px',border:'none',cursor:'pointer',background: submenuForm.is_active ? '#16a34a' : '#ddd',position:'relative',transition:'background .2s'}">
            <span :style="{position:'absolute',top:'2px',left: submenuForm.is_active ? '20px' : '2px',width:'20px',height:'20px',background:'#fff',borderRadius:'50%',transition:'left .2s',display:'block'}"></span>
          </button>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px">
          <button @click="showSubmenuModal=false" style="padding:8px 18px;border:1px solid #e2d5c3;border-radius:8px;background:#fff;font-size:.83rem;cursor:pointer;color:#555">Cancel</button>
          <button @click="submitSubmenu" style="padding:8px 22px;background:#4338ca;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer">{{ submenuModalMode === 'create' ? 'Create Submenu' : 'Update Submenu' }}</button>
        </div>
      </div>
    </div>

    <!-- BUTTON MODAL -->
    <div v-if="showButtonModal" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:flex;align-items:center;justify-content:center">
      <div style="background:#fff;border-radius:14px;padding:28px;width:480px;max-width:95vw;position:relative">
        <button @click="showButtonModal=false" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#999">&times;</button>
        <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 2px">{{ buttonModalMode === 'create' ? 'Add Button' : 'Edit Button #' + buttonForm.id }}</h2>
        <p style="font-size:.78rem;color:#888;margin:0 0 16px">Submenu: {{ selectedSubmenu?.name }}</p>

        <div style="background:#f8f5f0;border-radius:8px;padding:8px 12px;margin-bottom:16px;font-size:.78rem;color:#666;display:flex;justify-content:space-between">
          <span>Selected Submenu: {{ selectedSubmenu?.name }} (#{{ selectedSubmenu?.id }})</span>
          <span>{{ buttonModalMode === 'create' ? 'Creating New Button' : 'Editing Button #' + buttonForm.id }}</span>
        </div>

        <div style="margin-bottom:14px">
          <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Button Name <span style="color:red">*</span></label>
          <input v-model="buttonForm.name" placeholder="e.g. Edit" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          <div style="font-size:.7rem;color:#888;margin-top:3px">Must be unique inside the selected submenu.</div>
        </div>
        <div style="margin-bottom:14px">
          <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Key Value <span style="color:red">*</span></label>
          <input v-model="buttonForm.key_value" placeholder="e.g. dashboard.edit" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          <div style="font-size:.7rem;color:#888;margin-top:3px">Must be unique inside the selected submenu.</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Sort Order</label>
            <input v-model.number="buttonForm.sort_order" type="number" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
            <div style="font-size:.7rem;color:#888;margin-top:3px">Lower value appears first.</div>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;background:#f8f5f0;padding:10px 12px;border-radius:8px">
            <div>
              <div style="font-size:.82rem;font-weight:600;color:#333">Active</div>
              <div style="font-size:.72rem;color:#888">Visible button</div>
            </div>
            <button @click="buttonForm.is_active = !buttonForm.is_active" :style="{width:'42px',height:'24px',borderRadius:'12px',border:'none',cursor:'pointer',background: buttonForm.is_active ? '#16a34a' : '#ddd',position:'relative',transition:'background .2s'}">
              <span :style="{position:'absolute',top:'2px',left: buttonForm.is_active ? '20px' : '2px',width:'20px',height:'20px',background:'#fff',borderRadius:'50%',transition:'left .2s',display:'block'}"></span>
            </button>
          </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px">
          <button @click="showButtonModal=false" style="padding:8px 18px;border:1px solid #e2d5c3;border-radius:8px;background:#fff;font-size:.83rem;cursor:pointer;color:#555">Cancel</button>
          <button @click="submitButton" style="padding:8px 22px;background:#4338ca;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer">{{ buttonModalMode === 'create' ? 'Create Button' : 'Update Button' }}</button>
        </div>
      </div>
    </div>

    <!-- PARENT MENU MODAL -->
    <div v-if="showParentModal" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:flex;align-items:center;justify-content:center">
      <div style="background:#fff;border-radius:14px;padding:28px;width:400px;max-width:95vw;position:relative">
        <button @click="showParentModal=false" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#999">&times;</button>
        <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 4px">{{ parentModalMode === 'create' ? 'Add Parent Menu' : 'Edit Parent Menu' }}</h2>
        <p style="font-size:.78rem;color:#888;margin:0 0 18px">Parent menus appear as section headers in the sidebar.</p>
        <div style="margin-bottom:14px">
          <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Name <span style="color:red">*</span></label>
          <input v-model="parentForm.name" placeholder="e.g. Overview" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px">
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Type <span style="color:red">*</span></label>
            <select v-model="parentForm.type" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;background:#fff">
              <option value="admin">Admin</option>
              <option value="teacher">Teacher</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Guard</label>
            <select v-model="parentForm.guard" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;background:#fff">
              <option value="web">web</option>
              <option value="admin">admin</option>
            </select>
          </div>
          <div>
            <label style="font-size:.78rem;font-weight:600;color:#333;display:block;margin-bottom:4px">Sort Order</label>
            <input v-model.number="parentForm.sort_order" type="number" style="width:100%;padding:8px 10px;border:1px solid #e2d5c3;border-radius:7px;font-size:.8rem;outline:none;box-sizing:border-box" />
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;background:#f8f5f0;padding:10px 14px;border-radius:8px;margin-bottom:20px">
          <div>
            <div style="font-size:.82rem;font-weight:600;color:#333">Active</div>
            <div style="font-size:.72rem;color:#888">Show in sidebar</div>
          </div>
          <button @click="parentForm.is_active = !parentForm.is_active" :style="{width:'42px',height:'24px',borderRadius:'12px',border:'none',cursor:'pointer',background: parentForm.is_active ? '#16a34a' : '#ddd',position:'relative',transition:'background .2s'}">
            <span :style="{position:'absolute',top:'2px',left: parentForm.is_active ? '20px' : '2px',width:'20px',height:'20px',background:'#fff',borderRadius:'50%',transition:'left .2s',display:'block'}"></span>
          </button>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px">
          <button @click="showParentModal=false" style="padding:8px 18px;border:1px solid #e2d5c3;border-radius:8px;background:#fff;font-size:.83rem;cursor:pointer;color:#555">Cancel</button>
          <button @click="submitParent" style="padding:8px 22px;background:#4338ca;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer">{{ parentModalMode === 'create' ? 'Create Parent Menu' : 'Update Parent Menu' }}</button>
        </div>
      </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div v-if="showDeleteConfirm" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:flex;align-items:center;justify-content:center">
      <div style="background:#fff;border-radius:14px;padding:28px;width:380px;max-width:95vw;text-align:center">
        <div style="width:48px;height:48px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.4rem">&#9888;</div>
        <h3 style="font-size:1rem;font-weight:700;margin:0 0 8px;color:#1a1208">Delete {{ deleteTarget?.type }}?</h3>
        <p style="font-size:.83rem;color:#666;margin:0 0 20px">Are you sure you want to delete "<strong>{{ deleteTarget?.name }}</strong>"? This action cannot be undone.</p>
        <div style="display:flex;justify-content:center;gap:10px">
          <button @click="showDeleteConfirm=false" style="padding:8px 20px;border:1px solid #e2d5c3;border-radius:8px;background:#fff;font-size:.83rem;cursor:pointer;color:#555">Cancel</button>
          <button @click="executeDelete" style="padding:8px 20px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer">Delete</button>
        </div>
      </div>
    </div>

  </AppLayout>
</template>
