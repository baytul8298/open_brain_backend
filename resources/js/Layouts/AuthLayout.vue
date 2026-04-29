<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'
import Toast from '@/Components/shared/Toast.vue'
import { useToast } from '@/composables/useToast'
import type { PageProps } from '@/types/inertia'

const page = usePage<PageProps>()
const toast = useToast()

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.success) toast.success('Success', flash.success)
    if (flash?.error) toast.error('Error', flash.error)
  },
  { immediate: true, deep: true },
)
</script>

<template>
  <div class="auth-root">
    <!-- ─── Left Panel ─────────────────────────── -->
    <aside class="auth-left">
      <div class="auth-brand">
        <div class="auth-brand__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>
        <span class="auth-brand__name">LearnForge</span>
      </div>

      <div class="auth-hero">
        <div class="auth-eyebrow">Premium Courses</div>
        <h1 class="auth-heading">
          Master skills that<br/><em>matter most</em><br/>in your career.
        </h1>
        <p class="auth-sub">
          Join thousands of learners growing with world-class instructors, hands-on projects, and lifetime access.
        </p>
      </div>

      <div class="auth-stats">
        <div class="auth-stat">
          <span class="auth-stat__num">42K+</span>
          <span class="auth-stat__lbl">Students</span>
        </div>
        <div class="auth-stat">
          <span class="auth-stat__num">380</span>
          <span class="auth-stat__lbl">Courses</span>
        </div>
        <div class="auth-stat">
          <span class="auth-stat__num">98%</span>
          <span class="auth-stat__lbl">Satisfaction</span>
        </div>
      </div>

      <!-- Floating card -->
      <div class="auth-float-card">
        <span class="auth-fc-badge">Trending</span>
        <p class="auth-fc-title">UI Design Mastery 2025</p>
        <div class="auth-fc-meta">
          <span class="auth-fc-rating"><span>★ 4.9</span> · 2.1k</span>
          <div class="auth-fc-avatars">
            <div class="auth-fc-avatar auth-fc-avatar--a1">A</div>
            <div class="auth-fc-avatar auth-fc-avatar--a2">R</div>
            <div class="auth-fc-avatar auth-fc-avatar--a3">M</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- ─── Right Panel ─────────────────────────── -->
    <main class="auth-right">
      <div class="auth-form-wrap">
        <slot />
      </div>
    </main>

    <Toast />
  </div>
</template>

<style scoped>
.auth-root {
  display: flex;
  height: 100vh;
  overflow: hidden;
  background-color: var(--auth-cream);
}

/* ─── Left Panel ─────────────────────────── */
.auth-left {
  position: relative;
  width: 52%;
  background: var(--auth-ink);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 52px 56px;
  overflow: hidden;
  flex-shrink: 0;
}

.auth-left::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 80% 60% at 110% 0%, rgba(201, 137, 60, 0.35) 0%, transparent 60%),
    radial-gradient(ellipse 60% 50% at -20% 100%, rgba(184, 75, 47, 0.28) 0%, transparent 60%);
  pointer-events: none;
}

.auth-left::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
  background-size: 56px 56px;
  pointer-events: none;
}

/* Brand */
.auth-brand {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 12px;
  animation: auth-fadeUp 0.7s ease both;
}

.auth-brand__icon {
  width: 38px;
  height: 38px;
  background: var(--auth-gold);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.auth-brand__name {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem;
  color: var(--auth-cream);
  letter-spacing: 0.02em;
}

/* Hero */
.auth-hero {
  position: relative;
  z-index: 1;
  animation: auth-fadeUp 0.7s 0.15s ease both;
}

.auth-eyebrow {
  font-size: 0.72rem;
  font-weight: 500;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--auth-gold-light);
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.auth-eyebrow::before {
  content: '';
  display: block;
  width: 28px;
  height: 1px;
  background: var(--auth-gold-light);
}

.auth-heading {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.2rem, 3.2vw, 3.1rem);
  line-height: 1.18;
  color: var(--auth-cream);
  margin-bottom: 22px;
}

.auth-heading em {
  font-style: italic;
  color: var(--auth-gold-light);
}

.auth-sub {
  font-size: 0.95rem;
  font-weight: 300;
  line-height: 1.7;
  color: rgba(250, 246, 239, 0.62);
  max-width: 360px;
  font-family: 'DM Sans', sans-serif;
}

/* Stats strip */
.auth-stats {
  position: relative;
  z-index: 1;
  display: flex;
  gap: 36px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 32px;
  animation: auth-fadeUp 0.7s 0.3s ease both;
}

.auth-stat {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.auth-stat__num {
  font-family: 'Playfair Display', serif;
  font-size: 1.65rem;
  color: var(--auth-cream);
  line-height: 1;
}

.auth-stat__lbl {
  font-size: 0.72rem;
  color: rgba(250, 246, 239, 0.5);
  letter-spacing: 0.06em;
}

/* Floating card */
.auth-float-card {
  position: absolute;
  right: -18px;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.07);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 18px;
  padding: 20px 22px;
  width: 196px;
  z-index: 2;
  animation: auth-floatCard 1s 0.4s ease both, auth-bob 4s 1.4s ease-in-out infinite;
}

.auth-fc-badge {
  font-size: 0.62rem;
  font-weight: 500;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--auth-gold-light);
  background: rgba(201, 137, 60, 0.18);
  border: 1px solid rgba(201, 137, 60, 0.35);
  border-radius: 20px;
  padding: 3px 9px;
  display: inline-block;
  margin-bottom: 12px;
}

.auth-fc-title {
  font-family: 'Playfair Display', serif;
  font-size: 0.95rem;
  color: var(--auth-cream);
  line-height: 1.3;
  margin-bottom: 14px;
}

.auth-fc-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.auth-fc-rating {
  font-size: 0.72rem;
  color: rgba(250, 246, 239, 0.65);
}

.auth-fc-rating span {
  color: var(--auth-gold-light);
}

.auth-fc-avatars {
  display: flex;
}

.auth-fc-avatar {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  border: 2px solid var(--auth-ink);
  margin-left: -6px;
  font-size: 0.6rem;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
}

.auth-fc-avatar:first-child { margin-left: 0; }
.auth-fc-avatar--a1 { background: #5c8de8; }
.auth-fc-avatar--a2 { background: #e85ca0; }
.auth-fc-avatar--a3 { background: #5ce8a0; }

/* ─── Right Panel ─────────────────────────── */
.auth-right {
  flex: 1;
  height: 100vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 52px 48px;
  background: var(--auth-cream);
  position: relative;
}

.auth-right::before {
  content: '';
  position: absolute;
  inset: 0;
  opacity: 0.035;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 150px;
  pointer-events: none;
}

.auth-form-wrap {
  width: 100%;
  max-width: 390px;
  position: relative;
  z-index: 1;
}

/* Animations */
@keyframes auth-fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes auth-floatCard {
  from { opacity: 0; transform: translateY(-50%) translateX(30px); }
  to   { opacity: 1; transform: translateY(-50%) translateX(0); }
}

@keyframes auth-bob {
  0%, 100% { transform: translateY(-50%) translateY(0); }
  50%       { transform: translateY(-50%) translateY(-10px); }
}

/* Responsive */
@media (max-width: 860px) {
  .auth-left { display: none; }
  .auth-right { padding: 40px 24px; }
}
</style>
