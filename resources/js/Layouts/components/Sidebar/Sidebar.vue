<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import type { PageProps, NavMenu, NavParentMenu } from '@/types/inertia'

const page = usePage<PageProps>()

const isActive = (href: string) =>
  window.location.pathname === href ||
  window.location.pathname.startsWith(href + '/')

const userInitials = computed(() => {
  const name = page.props.auth?.user?.name || 'SA'
  return name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2)
})

const userName = computed(() => page.props.auth?.user?.name || 'Super Admin')
const signOut = () => router.post('/logout')

const navParentMenus = computed<NavParentMenu[]>(() => page.props.nav_parent_menus || [])

const expandedMenus = ref<Set<number>>(new Set())

function hasActiveSubmenu(menu: NavMenu) {
  return menu.submenus?.some(s => s.to && isActive(s.to)) ?? false
}

// Auto-expand any menu whose submenu is currently active
watchEffect(() => {
  navParentMenus.value.forEach(parent => {
    parent.menus?.forEach(menu => {
      if (hasActiveSubmenu(menu)) {
        expandedMenus.value.add(menu.id)
      }
    })
  })
})

function toggleMenu(menu: NavMenu) {
  if (expandedMenus.value.has(menu.id)) {
    expandedMenus.value.delete(menu.id)
  } else {
    expandedMenus.value.add(menu.id)
  }
}

function isMenuExpanded(menu: NavMenu) {
  return expandedMenus.value.has(menu.id)
}

function isParentActive(parent: NavParentMenu) {
  return parent.menus?.some(m =>
    (m.to && isActive(m.to)) || hasActiveSubmenu(m)
  ) ?? false
}
</script>

<template>
  <aside class="sb">
    <!-- Logo -->
    <div class="sb-logo">
      <div class="sb-mark">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
      </div>
      <div>
        <span class="sb-nm">OpenBrain</span>
        <span class="sb-tag">Admin Console</span>
      </div>
    </div>

    <!-- User -->
    <div class="sb-user">
      <div class="sb-av">
        {{ userInitials }}
        <span class="sb-dot"></span>
      </div>
      <div>
        <div class="sb-unm">{{ userName }}</div>
        <span class="sb-rol">Super Admin</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sb-nav">

      <template v-for="parent in navParentMenus" :key="parent.id">
        <div :class="['sb-grp', isParentActive(parent) ? 'sb-grp-on' : '']">
          {{ parent.name }}
        </div>
        <template v-for="menu in parent.menus" :key="menu.id">

          <!-- Direct link (no submenus) -->
          <Link
            v-if="menu.to && (!menu.submenus || menu.submenus.length === 0)"
            :href="menu.to"
            :class="['ni', isActive(menu.to) ? 'on' : '']"
          >
            <i v-if="menu.icon_key" :class="menu.icon_key" class="ni-icon"></i>
            <svg v-else viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
            {{ menu.name }}
          </Link>

          <!-- Dropdown menu with submenus -->
          <template v-else>
            <button
              @click="toggleMenu(menu)"
              :class="['ni', hasActiveSubmenu(menu) ? 'on' : '']"
            >
              <i v-if="menu.icon_key" :class="menu.icon_key" class="ni-icon"></i>
              <svg v-else viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
              {{ menu.name }}
              <span class="ni-chevron">{{ isMenuExpanded(menu) ? '▲' : '▼' }}</span>
            </button>
            <template v-if="isMenuExpanded(menu) && menu.submenus">
              <Link
                v-for="sub in menu.submenus" :key="sub.id"
                :href="sub.to || '#'"
                :class="['ni', 'ni-sub', sub.to && isActive(sub.to) ? 'on' : '']"
              >
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                {{ sub.name }}
              </Link>
            </template>
          </template>

        </template>
      </template>

    </nav>

    <!-- Bottom -->
    <div class="sb-bot">
      <button class="ni" @click="signOut">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Sign Out
      </button>
    </div>
  </aside>
</template>

<style scoped>
.sb{width:248px;flex-shrink:0;background:#100d09;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:200;overflow-y:auto;overflow-x:hidden}
.sb::after{content:'';position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 120% 35% at 50% 0%,rgba(201,137,60,.13),transparent 55%),radial-gradient(ellipse 80% 20% at 50% 100%,rgba(184,75,47,.09),transparent 50%)}
.sb-logo{position:relative;z-index:1;padding:20px 16px 16px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:10px}
.sb-mark{width:36px;height:36px;border-radius:10px;background:linear-gradient(145deg,#c9893c,#b84b2f);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 0 22px rgba(201,137,60,.32)}
.sb-nm{font-family:'Playfair Display',serif;font-size:1rem;color:#faf6ef;display:block}
.sb-tag{font-size:.57rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(250,246,239,.26);display:block;margin-top:1px}
.sb-user{position:relative;z-index:1;padding:11px 16px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:9px}
.sb-av{width:31px;height:31px;border-radius:9px;background:linear-gradient(135deg,#b84b2f,#c9893c);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:.72rem;color:#fff;flex-shrink:0;position:relative}
.sb-dot{position:absolute;bottom:-2px;right:-2px;width:8px;height:8px;background:#4ade80;border-radius:50%;border:2px solid #100d09}
.sb-unm{font-size:.79rem;font-weight:600;color:#faf6ef}
.sb-rol{font-size:.57rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#e8b96a;background:rgba(201,137,60,.14);border:1px solid rgba(201,137,60,.28);padding:2px 7px;border-radius:20px;display:inline-block;margin-top:2px}
.sb-nav{position:relative;z-index:1;flex:1;padding:6px 0;overflow-y:auto}
.sb-grp{font-size:.55rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(250,246,239,.22);padding:14px 16px 4px;transition:color .15s}
.sb-grp-on{color:rgba(99,102,241,.9);background:rgba(99,102,241,.08);border-radius:6px;margin:0 8px;padding:6px 8px}

/* Shared style for both <Link> and <button> nav items */
.ni{
  display:flex;align-items:center;gap:9px;
  padding:7px 16px;
  font-size:.79rem;font-weight:500;
  color:rgba(250,246,239,.42);
  cursor:pointer;
  border-left:3px solid transparent;
  border-top:none;border-right:none;border-bottom:none;
  background:none;
  width:100%;text-align:left;
  transition:all .15s;
  position:relative;z-index:1;
  white-space:nowrap;user-select:none;
  text-decoration:none;
  font-family:'DM Sans',sans-serif;
  box-sizing:border-box;
}
.ni svg{width:14px;height:14px;flex-shrink:0;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.ni-icon{width:14px;flex-shrink:0;font-size:13px;text-align:center;display:inline-flex;align-items:center;justify-content:center}
.ni-chevron{margin-left:auto;font-size:.65rem;opacity:.5}
.ni:hover{color:rgba(250,246,239,.75);background:rgba(255,255,255,.035)}
.ni.on{color:#e8b96a;border-left-color:#c9893c;background:rgba(201,137,60,.09)}
.ni-sub{padding-left:32px;font-size:.76rem}
.sb-bot{position:relative;z-index:1;border-top:1px solid rgba(255,255,255,.07);padding:6px 0}
.sb-bot .ni{color:rgba(250,246,239,.25)}
</style>
