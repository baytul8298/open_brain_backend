<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import type { PageProps } from '@/types/inertia'

const page = usePage<PageProps>()

const pageTitle = computed(() => {
  const path = window.location.pathname
  const segment = path.split('/').filter(Boolean)[0] || 'dashboard'
  const map: Record<string, string> = {
    dashboard: 'Platform <em>Dashboard</em>',
    analytics: 'Analytics & <em>Metrics</em>',
    users: 'All <em>Users</em>',
    teachers: 'Teachers & <em>Verification</em>',
    courses: 'Course <em>Management</em>',
    enrollments: 'Enrollments & <em>Subscriptions</em>',
    assignments: 'Assignments & <em>Submissions</em>',
    'live-sessions': 'Live <em>Sessions</em>',
    reviews: 'Course <em>Reviews</em>',
    subjects: 'Subjects & <em>Categories</em>',
    payments: 'Payment <em>Transactions</em>',
    payouts: 'Teacher <em>Payouts</em>',
    coupons: 'Coupons & <em>Promotions</em>',
    moderation: 'Reports & <em>Moderation</em>',
    notifications: 'Platform <em>Notifications</em>',
    settings: 'Platform <em>Settings</em>',
    'feature-flags': 'Feature <em>Flags</em>',
    'audit-log': 'Audit <em>Log</em>',
    roles: 'Roles & <em>Permissions</em>',
    permissions: 'System <em>Permissions</em>',
    modules: 'System <em>Modules</em>',
  }
  return map[segment] || segment.charAt(0).toUpperCase() + segment.slice(1)
})

const userInitials = computed(() => {
  const name = page.props.auth?.user?.name || 'SA'
  return name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2)
})
</script>

<template>
  <header class="topbar">
    <!-- Breadcrumb -->
    <div class="tb-bc">
      <span class="tb-r">Admin</span>
      <span class="tb-s">/</span>
      <span class="tb-cur" v-html="pageTitle"></span>
    </div>

    <!-- Search -->
    <div class="tb-search">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#8a7f72" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input type="text" placeholder="Search..." style="border:none;background:transparent;outline:none;font-family:'DM Sans',sans-serif;font-size:.78rem;color:#0e0b07;width:100%;" />
    </div>

    <!-- Notification button -->
    <button class="tb-btn" title="Notifications">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8a7f72" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      <span class="tb-nb">9</span>
    </button>

    <!-- Avatar -->
    <div class="tb-av" :title="page.props.auth?.user?.name || 'Admin'">
      {{ userInitials }}
    </div>
  </header>
</template>

<style scoped>
.topbar{position:sticky;top:0;z-index:150;height:58px;background:rgba(250,246,239,.96);backdrop-filter:blur(24px);border-bottom:1px solid #e0d8cc;display:flex;align-items:center;gap:9px;padding:0 24px}
.tb-bc{flex:1;display:flex;align-items:center;gap:5px}
.tb-r{font-size:.7rem;color:#8a7f72}
.tb-s{font-size:.7rem;color:#ddd0b8}
.tb-cur{font-family:'Playfair Display',serif;font-size:.92rem;color:#0e0b07}
.tb-cur em{font-style:italic;color:#c9893c}
.tb-search{display:flex;align-items:center;gap:7px;background:#f0e8d6;border:1.5px solid #e0d8cc;border-radius:10px;padding:6px 11px;width:220px;transition:all .18s}
.tb-search:focus-within{border-color:#e8b96a;width:260px}
.tb-btn{width:35px;height:35px;border-radius:9px;background:#f0e8d6;border:1.5px solid #e0d8cc;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all .16s;flex-shrink:0}
.tb-btn:hover{border-color:#e8b96a}
.tb-nb{position:absolute;top:-3px;right:-3px;background:#b83232;color:#fff;font-size:.53rem;font-weight:700;border-radius:20px;padding:1px 5px;border:2px solid #faf6ef}
.tb-av{width:35px;height:35px;border-radius:9px;background:linear-gradient(135deg,#b84b2f,#c9893c);display:flex;align-items:center;justify-content:center;cursor:pointer;font-family:'Playfair Display',serif;font-size:.72rem;color:#fff;flex-shrink:0}
</style>
