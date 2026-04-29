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
  <div class="rgl-root">
    <!-- ─── Left Panel ─────────────────────────── -->
    <aside class="rgl-left">
      <div class="rgl-brand">
        <div class="rgl-brand__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>
        <span class="rgl-brand__name">LearnForge</span>
      </div>

      <div class="rgl-showcase">
        <div class="rgl-eyebrow">Private Tutor Platform</div>
        <h1 class="rgl-heading">
          Every subject,<br/>every class — <em>your</em><br/>pace, your way.
        </h1>
        <p class="rgl-sub">
          Pick your subjects and class level. Get matched with expert tutors and structured courses built just for you.
        </p>

        <div class="rgl-subject-tags">
          <span class="rgl-stag rgl-stag--math">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M12 2v20M2 12h20"/></svg>
            Mathematics
          </span>
          <span class="rgl-stag rgl-stag--physics">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
            Physics
          </span>
          <span class="rgl-stag rgl-stag--chem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M9 3H7l-4 8 4 9h10l4-9-4-8h-2"/><path d="M9 3h6"/></svg>
            Chemistry
          </span>
          <span class="rgl-stag rgl-stag--bio">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Biology
          </span>
          <span class="rgl-stag rgl-stag--eng">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
            English
          </span>
          <span class="rgl-stag rgl-stag--hist">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            History
          </span>
          <span class="rgl-stag rgl-stag--cs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            Computer Sc.
          </span>
          <span class="rgl-stag rgl-stag--geo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            Geography
          </span>
        </div>
      </div>

      <div class="rgl-class-strip">
        <div class="rgl-class-badge"><span class="rgl-cn">6</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge"><span class="rgl-cn">7</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge rgl-class-badge--active"><span class="rgl-cn">8</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge rgl-class-badge--active"><span class="rgl-cn">9</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge"><span class="rgl-cn">10</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge"><span class="rgl-cn">11</span><span class="rgl-cl">Class</span></div>
        <div class="rgl-class-badge"><span class="rgl-cn">12</span><span class="rgl-cl">Class</span></div>
      </div>
    </aside>

    <!-- ─── Right Panel ─────────────────────────── -->
    <main class="rgl-right">
      <div class="rgl-form-wrap">
        <slot />
      </div>
    </main>

    <Toast />
  </div>
</template>

<style scoped>
.rgl-root {
  display: flex;
  height: 100vh;
  background-color: #faf6ef;
}

/* ─── Left Panel ─────────────────────────── */
.rgl-left {
  position: relative;
  width: 42%;
  background: #0e0b07;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 48px 52px;
  overflow: hidden;
  flex-shrink: 0;
}

.rgl-left::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 80% 60% at 110% 0%, rgba(201,137,60,.35) 0%, transparent 60%),
    radial-gradient(ellipse 60% 50% at -20% 100%, rgba(184,75,47,.28) 0%, transparent 60%);
  pointer-events: none;
}

.rgl-left::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
  background-size: 56px 56px;
  pointer-events: none;
}

/* Brand */
.rgl-brand {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  gap: 12px;
  animation: rgl-fadeUp .7s ease both;
}

.rgl-brand__icon {
  width: 38px;
  height: 38px;
  background: var(--auth-gold, #c9893c);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.rgl-brand__name {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem;
  color: #faf6ef;
  letter-spacing: .02em;
}

/* Showcase */
.rgl-showcase {
  position: relative;
  z-index: 1;
  animation: rgl-fadeUp .7s .12s ease both;
}

.rgl-eyebrow {
  font-size: .72rem;
  font-weight: 500;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: var(--auth-gold-light, #e8b96a);
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.rgl-eyebrow::before {
  content: '';
  display: block;
  width: 28px;
  height: 1px;
  background: var(--auth-gold-light, #e8b96a);
}

.rgl-heading {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.9rem, 2.8vw, 2.7rem);
  line-height: 1.18;
  color: #faf6ef;
  margin-bottom: 20px;
}

.rgl-heading em {
  font-style: italic;
  color: var(--auth-gold-light, #e8b96a);
}

.rgl-sub {
  font-size: .9rem;
  font-weight: 300;
  line-height: 1.7;
  color: rgba(250,246,239,.6);
  max-width: 320px;
  margin-bottom: 32px;
  font-family: 'DM Sans', sans-serif;
}

/* Subject tag cloud */
.rgl-subject-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 9px;
}

.rgl-stag {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 7px 14px;
  border-radius: 40px;
  font-size: .78rem;
  font-weight: 500;
  border: 1px solid;
  font-family: 'DM Sans', sans-serif;
  animation: rgl-fadeUp .7s ease both;
}

.rgl-stag--math    { color: #7ec8e3; border-color: rgba(126,200,227,.35); background: rgba(126,200,227,.08); animation-delay: .20s; }
.rgl-stag--physics { color: #f4a460; border-color: rgba(244,164,96,.35);  background: rgba(244,164,96,.08);  animation-delay: .28s; }
.rgl-stag--chem    { color: #90ee90; border-color: rgba(144,238,144,.35); background: rgba(144,238,144,.08); animation-delay: .35s; }
.rgl-stag--bio     { color: #ffb6c1; border-color: rgba(255,182,193,.35); background: rgba(255,182,193,.08); animation-delay: .42s; }
.rgl-stag--eng     { color: #dda0dd; border-color: rgba(221,160,221,.35); background: rgba(221,160,221,.08); animation-delay: .49s; }
.rgl-stag--hist    { color: #f0e68c; border-color: rgba(240,230,140,.35); background: rgba(240,230,140,.08); animation-delay: .56s; }
.rgl-stag--cs      { color: #87ceeb; border-color: rgba(135,206,235,.35); background: rgba(135,206,235,.08); animation-delay: .63s; }
.rgl-stag--geo     { color: #98fb98; border-color: rgba(152,251,152,.35); background: rgba(152,251,152,.08); animation-delay: .70s; }

/* Class strip */
.rgl-class-strip {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  position: relative;
  z-index: 1;
  border-top: 1px solid rgba(255,255,255,.1);
  padding-top: 28px;
  animation: rgl-fadeUp .7s .6s ease both;
}

.rgl-class-badge {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: rgba(255,255,255,.07);
  border: 1px solid rgba(255,255,255,.12);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.rgl-cn {
  font-family: 'Playfair Display', serif;
  font-size: .85rem;
  color: #faf6ef;
  line-height: 1;
}

.rgl-cl {
  font-size: .55rem;
  color: rgba(250,246,239,.45);
  letter-spacing: .04em;
}

.rgl-class-badge--active {
  background: rgba(201,137,60,.2);
  border-color: rgba(201,137,60,.5);
}

.rgl-class-badge--active .rgl-cn {
  color: var(--auth-gold-light, #e8b96a);
}

/* ─── Right Panel ─────────────────────────── */
.rgl-right {
  flex: 1;
  height: 100vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  padding: 40px 48px;
  background: #faf6ef;
  position: relative;
}

.rgl-right::before {
  content: '';
  position: absolute;
  inset: 0;
  opacity: .035;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 150px;
  pointer-events: none;
}

.rgl-form-wrap {
  width: 100%;
  max-width: 440px;
  position: relative;
  z-index: 1;
  padding-bottom: 32px;
}

/* Animations */
@keyframes rgl-fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 900px) {
  .rgl-left { display: none; }
  .rgl-right { padding: 32px 20px; }
}
</style>
