<script setup lang="ts">
import { ref, computed, watchEffect, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { PageProps, NavParentMenu, NavMenu } from '@/types/inertia'
import Toast from '@/Components/shared/Toast.vue'
import { useToast } from '@/composables/useToast'

const page = usePage<PageProps>()
const toast = useToast()
const user = computed(() => (page.props as any).auth?.user)

watch(() => page.props.flash, (flash) => {
  if (flash?.success) toast.success('Success', flash.success)
  if (flash?.error)   toast.error('Error', flash.error)
}, { immediate: true, deep: true })

const initials = computed(() => {
  const name: string = user.value?.name ?? 'T'
  return name.split(' ').map((n: string) => n[0]).join('').slice(0, 2).toUpperCase()
})

const displayName = computed(() => user.value?.name ?? 'Teacher')

const navParentMenus = computed<NavParentMenu[]>(() => page.props.nav_parent_menus || [])

function resolveHref(to: string | null): string {
  if (!to) return '#'
  if (to.startsWith('http') || to.startsWith('/teacher/')) return to
  return '/teacher/' + to.replace(/^\/+/, '')
}

const isActive = (href: string | null) => {
  const resolved = resolveHref(href)
  return resolved !== '#' && (
    window.location.pathname === resolved ||
    window.location.pathname.startsWith(resolved + '/')
  )
}

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
  if (expandedMenus.value.has(menu.id)) expandedMenus.value.delete(menu.id)
  else expandedMenus.value.add(menu.id)
}
function isMenuExpanded(menu: NavMenu) {
  return expandedMenus.value.has(menu.id)
}
</script>

<template>
  <div class="tp-root">
    <aside class="tp-sidebar">
      <!-- Brand -->
      <div class="tp-brand">
        <div class="tp-brand-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>
        <span class="tp-brand-name">OpenBrain</span>
      </div>

      <!-- User block -->
      <div class="tp-user">
        <div class="tp-avatar-wrap">
          <div class="tp-avatar">{{ initials }}</div>
          <div class="tp-online"></div>
        </div>
        <div>
          <div class="tp-uname">{{ displayName }}</div>
          <span class="tp-role-badge">Teacher</span>
        </div>
      </div>

      <!-- Navigation (dynamic from DB) -->
      <nav class="tp-nav">
        <template v-for="parent in navParentMenus" :key="parent.id">
          <div class="tp-section">{{ parent.name }}</div>
          <template v-for="menu in parent.menus" :key="menu.id">
            <!-- Direct link -->
            <Link
              v-if="menu.to && (!menu.submenus || menu.submenus.length === 0)"
              :href="resolveHref(menu.to)"
              :class="['tp-link', { active: isActive(menu.to) }]"
            >
              <i v-if="menu.icon_key" :class="menu.icon_key" class="tp-icon"></i>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
              </svg>
              <span>{{ menu.name }}</span>
            </Link>
            <!-- Group menu with submenus -->
            <template v-else>
              <button
                @click="toggleMenu(menu)"
                :class="['tp-link', { active: hasActiveSubmenu(menu) }]"
              >
                <i v-if="menu.icon_key" :class="menu.icon_key" class="tp-icon"></i>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                </svg>
                <span>{{ menu.name }}</span>
                <span class="tp-chevron">{{ isMenuExpanded(menu) ? '▲' : '▼' }}</span>
              </button>
              <template v-if="isMenuExpanded(menu) && menu.submenus">
                <Link
                  v-for="sub in menu.submenus"
                  :key="sub.id"
                  :href="resolveHref(sub.to)"
                  :class="['tp-link', 'tp-link-sub', { active: sub.to && isActive(sub.to) }]"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>
                  <span>{{ sub.name }}</span>
                </Link>
              </template>
            </template>
          </template>
        </template>
      </nav>

      <!-- Footer -->
      <div class="tp-footer">
        <Link href="/logout" method="post" as="button" class="tp-footer-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span>Sign Out</span>
        </Link>
      </div>
    </aside>

    <!-- Main -->
    <div class="tp-main">
      <slot />
    </div>
    <Toast />
  </div>
</template>

<style>
:root {
  --ink: #0e0b07; --cream: #faf6ef; --warm: #f0e8d6; --warm2: #e8ddc8;
  --gold: #c9893c; --gold-light: #e8b96a; --rust: #b84b2f; --muted: #8a7f72;
  --border: #e0d8cc; --card: #fff;
  --green: #3a9e6f; --blue: #3a6b9e; --purple: #7a5cbf; --teal: #2a8a8a;
  --red: #c53030; --orange: #d97706;
  --sidebar-w: 236px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.tp-root { font-family: 'DM Sans', sans-serif; background: var(--cream); color: var(--ink); display: flex; min-height: 100vh; overflow-x: hidden; }

.tp-sidebar { width: var(--sidebar-w); flex-shrink: 0; background: var(--ink); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; overflow-y: auto; }
.tp-sidebar::before { content: ''; position: absolute; inset: 0; pointer-events: none; background: radial-gradient(ellipse 100% 40% at 50% 0%, rgba(201,137,60,.22), transparent 70%), radial-gradient(ellipse 80% 30% at 50% 100%, rgba(184,75,47,.18), transparent 70%); }
.tp-sidebar::after { content: ''; position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 40px 40px; }

.tp-brand { position: relative; z-index: 1; display: flex; align-items: center; gap: 10px; padding: 28px 22px 24px; border-bottom: 1px solid rgba(255,255,255,.08); }
.tp-brand-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 9px; display: flex; align-items: center; justify-content: center; }
.tp-brand-icon svg { width: 18px; height: 18px; }
.tp-brand-name { font-family: 'Playfair Display', serif; font-size: 1.15rem; color: var(--cream); letter-spacing: .02em; }

.tp-user { position: relative; z-index: 1; padding: 16px 22px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
.tp-avatar-wrap { position: relative; flex-shrink: 0; }
.tp-avatar { width: 36px; height: 36px; border-radius: 9px; background: linear-gradient(135deg,#1a3a5c,#2d5a87); display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-size: .9rem; color: #fff; border: 2px solid rgba(255,255,255,.15); }
.tp-online { position: absolute; bottom: -2px; right: -2px; width: 9px; height: 9px; background: var(--green); border-radius: 50%; border: 2px solid var(--ink); }
.tp-uname { font-size: .84rem; font-weight: 600; color: var(--cream); }
.tp-role-badge { background: rgba(58,107,158,.25); border: 1px solid rgba(58,107,158,.4); border-radius: 20px; padding: 1px 7px; font-size: .62rem; color: #7fb3e0; font-weight: 500; display: inline-block; margin-top: 2px; }

.tp-nav { position: relative; z-index: 1; flex: 1; padding: 12px 0; }
.tp-section { font-size: .62rem; letter-spacing: .16em; text-transform: uppercase; color: rgba(250,246,239,.3); padding: 14px 22px 6px; font-weight: 600; }
.tp-link {
  display: flex; align-items: center; gap: 11px; padding: 10px 22px;
  font-size: .84rem; font-weight: 500; color: rgba(250,246,239,.65);
  text-decoration: none; cursor: pointer; border-left: 3px solid transparent;
  transition: all .2s; position: relative; z-index: 1;
  width: 100%; background: none; border-top: none; border-right: none; border-bottom: none;
  font-family: 'DM Sans', sans-serif; text-align: left;
}
.tp-link svg { width: 16px; height: 16px; flex-shrink: 0; }
.tp-icon { width: 16px; flex-shrink: 0; font-size: 14px; text-align: center; display: inline-flex; align-items: center; justify-content: center; }
.tp-chevron { margin-left: auto; font-size: .65rem; opacity: .5; }
.tp-link:hover { color: var(--cream); background: rgba(255,255,255,.05); }
.tp-link.active { color: var(--gold-light); border-left-color: var(--gold); background: rgba(201,137,60,.1); }
.tp-link-sub { padding-left: 40px; font-size: .8rem; }

.tp-footer { position: relative; z-index: 1; padding: 10px 0; border-top: 1px solid rgba(255,255,255,.08); }
.tp-footer-link {
  display: flex; align-items: center; gap: 11px; padding: 10px 22px;
  font-size: .84rem; font-weight: 500; color: rgba(250,246,239,.45);
  text-decoration: none; transition: all .2s; border-left: 3px solid transparent;
  background: none; border-top: none; border-right: none; border-bottom: none;
  width: 100%; cursor: pointer; font-family: 'DM Sans', sans-serif;
}
.tp-footer-link:hover { color: var(--cream); background: rgba(255,255,255,.05); }
.tp-footer-link svg { width: 16px; height: 16px; flex-shrink: 0; }

.tp-main { margin-left: var(--sidebar-w); flex: 1; min-width: 0; display: flex; flex-direction: column; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 1024px) {
  :root { --sidebar-w: 64px; }
  .tp-brand-name, .tp-uname, .tp-role-badge, .tp-section, .tp-link span, .tp-footer-link span { display: none; }
  .tp-link { padding: 14px; justify-content: center; }
  .tp-link svg { width: 18px; height: 18px; }
  .tp-brand { justify-content: center; padding: 20px 0; }
  .tp-user { justify-content: center; }
}
@media (max-width: 768px) {
  .tp-main { margin-left: 0; }
  .tp-sidebar { display: none; }
}
</style>
