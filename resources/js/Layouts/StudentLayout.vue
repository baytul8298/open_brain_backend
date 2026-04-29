<script setup lang="ts">
import { ref, computed, watchEffect, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { PageProps, NavParentMenu, NavMenu } from '@/types/inertia'
import Toast from '@/Components/shared/Toast.vue'
import { useToast } from '@/composables/useToast'

const page = usePage<PageProps>()
const toast = useToast()
const user = computed(() => page.props.auth?.user)

watch(() => page.props.flash, (flash) => {
  if (flash?.success) toast.success('Success', flash.success)
  if (flash?.error)   toast.error('Error', flash.error)
}, { immediate: true, deep: true })

const initials = computed(() => {
  const name: string = user.value?.name ?? 'S'
  return name.split(' ').map((n: string) => n[0]).join('').slice(0, 2).toUpperCase()
})

const displayName = computed(() => user.value?.name ?? 'Student')

const navParentMenus = computed<NavParentMenu[]>(() => page.props.nav_parent_menus || [])

const isActive = (href: string | null) =>
  href ? (window.location.pathname === href || window.location.pathname.startsWith(href + '/')) : false

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
  <div class="sp-root">

    <!-- ══ SIDEBAR ══ -->
    <aside class="sp-sidebar">
      <!-- Brand -->
      <div class="sp-brand">
        <div class="sp-brand-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>
        <span class="sp-brand-name">OpenBrain</span>
      </div>

      <!-- User block -->
      <div class="sp-user">
        <div class="sp-avatar-wrap">
          <div class="sp-avatar">{{ initials }}</div>
          <div class="sp-online"></div>
        </div>
        <div class="sp-uname">{{ displayName }}</div>
        <div class="sp-class"><span class="sp-class-badge">Student</span></div>
      </div>

      <!-- Navigation (dynamic from DB) -->
      <nav class="sp-nav">
        <template v-for="parent in navParentMenus" :key="parent.id">
          <div class="sp-section">{{ parent.name }}</div>
          <template v-for="menu in parent.menus" :key="menu.id">
            <!-- Direct link -->
            <Link
              v-if="menu.to && (!menu.submenus || menu.submenus.length === 0)"
              :href="menu.to"
              :class="['sp-link', { active: isActive(menu.to) }]"
            >
              <i v-if="menu.icon_key" :class="menu.icon_key" class="sp-icon"></i>
              <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
              </svg>
              <span>{{ menu.name }}</span>
            </Link>
            <!-- Group menu with submenus -->
            <template v-else>
              <button
                @click="toggleMenu(menu)"
                :class="['sp-link', { active: hasActiveSubmenu(menu) }]"
              >
                <i v-if="menu.icon_key" :class="menu.icon_key" class="sp-icon"></i>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                </svg>
                <span>{{ menu.name }}</span>
                <span class="sp-chevron">{{ isMenuExpanded(menu) ? '▲' : '▼' }}</span>
              </button>
              <template v-if="isMenuExpanded(menu) && menu.submenus">
                <Link
                  v-for="sub in menu.submenus"
                  :key="sub.id"
                  :href="sub.to || '#'"
                  :class="['sp-link', 'sp-link-sub', { active: sub.to && isActive(sub.to) }]"
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
      <div class="sp-footer">
        <Link href="/logout" method="post" as="button" class="sp-footer-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span>Sign Out</span>
        </Link>
      </div>
    </aside>

    <!-- ══ MAIN ══ -->
    <div class="sp-main">
      <slot />
    </div>

    <Toast />
  </div>
</template>

<style>
/* ══ STUDENT PORTAL — LAYOUT ══ */
:root {
  --sp-ink: #0e0b07;
  --sp-cream: #faf6ef;
  --sp-warm: #f0e8d6;
  --sp-warm2: #e8ddc8;
  --sp-gold: #c9893c;
  --sp-gold-light: #e8b96a;
  --sp-gold-pale: rgba(201,137,60,.10);
  --sp-rust: #b84b2f;
  --sp-muted: #8a7f72;
  --sp-border: #e0d8cc;
  --sp-card: #ffffff;
  --sp-green: #3a9e6f;
  --sp-blue: #3a6b9e;
  --sp-purple: #7a5cbf;
  --sp-sidebar-w: 236px;
}

.sp-root {
  font-family: 'DM Sans', sans-serif;
  background: var(--sp-cream);
  color: var(--sp-ink);
  display: flex;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ══ SIDEBAR ══ */
.sp-sidebar {
  width: var(--sp-sidebar-w);
  flex-shrink: 0;
  background: var(--sp-ink);
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0; left: 0; bottom: 0;
  z-index: 100;
  overflow-y: auto;
}
.sp-sidebar::before {
  content: '';
  position: absolute;
  inset: 0;
  pointer-events: none;
  background:
    radial-gradient(ellipse 100% 40% at 50% 0%, rgba(201,137,60,.22) 0%, transparent 70%),
    radial-gradient(ellipse 80% 30% at 50% 100%, rgba(184,75,47,.18) 0%, transparent 70%);
}
.sp-sidebar::after {
  content: '';
  position: absolute;
  inset: 0;
  pointer-events: none;
  background-image:
    linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
  background-size: 40px 40px;
}

.sp-brand {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 28px 22px 24px;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.sp-brand-icon {
  width: 34px; height: 34px;
  background: var(--sp-gold);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sp-brand-icon svg { width: 18px; height: 18px; }
.sp-brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.15rem;
  color: var(--sp-cream);
  letter-spacing: .02em;
}

.sp-user {
  position: relative;
  z-index: 1;
  padding: 18px 22px;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.sp-avatar-wrap { position: relative; display: inline-block; margin-bottom: 10px; }
.sp-avatar {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--sp-gold), var(--sp-rust));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 1.1rem;
  color: #fff;
  border: 2px solid rgba(255,255,255,.15);
}
.sp-online {
  position: absolute;
  bottom: 1px; right: 1px;
  width: 10px; height: 10px;
  background: var(--sp-green);
  border-radius: 50%;
  border: 2px solid var(--sp-ink);
}
.sp-uname { font-size: .88rem; font-weight: 600; color: var(--sp-cream); margin-bottom: 2px; }
.sp-class { font-size: .7rem; color: rgba(250,246,239,.5); }
.sp-class-badge {
  background: rgba(201,137,60,.2);
  border: 1px solid rgba(201,137,60,.4);
  border-radius: 20px;
  padding: 2px 8px;
  font-size: .65rem;
  color: var(--sp-gold-light);
  font-weight: 500;
}

.sp-nav { position: relative; z-index: 1; flex: 1; padding: 12px 0; }
.sp-section {
  font-size: .62rem;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(250,246,239,.3);
  padding: 14px 22px 6px;
  font-weight: 600;
}
.sp-link {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 10px 22px;
  font-size: .84rem;
  font-weight: 500;
  color: rgba(250,246,239,.65);
  text-decoration: none;
  cursor: pointer;
  border-left: 3px solid transparent;
  transition: all .2s;
  position: relative;
  z-index: 1;
  width: 100%;
  background: none;
  border-top: none; border-right: none; border-bottom: none;
  font-family: 'DM Sans', sans-serif;
  text-align: left;
  box-sizing: border-box;
}
.sp-link svg { width: 16px; height: 16px; flex-shrink: 0; }
.sp-icon { width: 16px; flex-shrink: 0; font-size: 14px; text-align: center; display: inline-flex; align-items: center; justify-content: center; }
.sp-chevron { margin-left: auto; font-size: .65rem; opacity: .5; }
.sp-link:hover { color: var(--sp-cream); background: rgba(255,255,255,.05); }
.sp-link.active {
  color: var(--sp-gold-light);
  border-left-color: var(--sp-gold);
  background: rgba(201,137,60,.1);
}
.sp-link-sub { padding-left: 40px; font-size: .8rem; }

.sp-footer {
  position: relative;
  z-index: 1;
  padding: 10px 0;
  border-top: 1px solid rgba(255,255,255,.08);
}
.sp-footer-link {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 10px 22px;
  font-size: .84rem;
  font-weight: 500;
  color: rgba(250,246,239,.45);
  text-decoration: none;
  transition: all .2s;
  border-left: 3px solid transparent;
  background: none;
  border-top: none; border-right: none; border-bottom: none;
  width: 100%;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
}
.sp-footer-link:hover { color: var(--sp-cream); background: rgba(255,255,255,.05); }
.sp-footer-link svg { width: 16px; height: 16px; flex-shrink: 0; }

/* ══ MAIN ══ */
.sp-main {
  margin-left: var(--sp-sidebar-w);
  flex: 1;
  min-width: 0;
}

/* ══ RESPONSIVE ══ */
@media (max-width: 1024px) {
  :root { --sp-sidebar-w: 64px; }
  .sp-brand-name, .sp-uname, .sp-class, .sp-section,
  .sp-link span, .sp-footer-link span { display: none; }
  .sp-link { padding: 14px; justify-content: center; }
  .sp-link svg { width: 18px; height: 18px; }
  .sp-brand { justify-content: center; padding: 20px 0; }
  .sp-user { display: flex; justify-content: center; padding: 14px 0; }
  .sp-avatar { margin: 0; }
  .sp-footer { display: flex; justify-content: center; }
}
@media (max-width: 768px) {
  .sp-main { margin-left: 0; }
  .sp-sidebar { display: none; }
}
</style>
