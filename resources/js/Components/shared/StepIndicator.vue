<script setup lang="ts">
interface Props {
  steps: string[]
  currentStep: number // 0-indexed active step
}

defineProps<Props>()
</script>

<template>
  <div class="si-root">
    <template v-for="(step, i) in steps" :key="i">
      <div
        class="si-step"
        :class="{
          'si-step--done':   i < currentStep,
          'si-step--active': i === currentStep,
          'si-step--idle':   i > currentStep,
        }"
      >
        <div class="si-dot">
          <template v-if="i < currentStep">✓</template>
          <template v-else>{{ i + 1 }}</template>
        </div>
        <span class="si-label">{{ step }}</span>
      </div>
      <div
        v-if="i < steps.length - 1"
        class="si-line"
        :class="{ 'si-line--done': i < currentStep }"
      />
    </template>
  </div>
</template>

<style scoped>
.si-root {
  display: flex;
  align-items: center;
  margin-bottom: 36px;
  animation: si-fadeUp 0.6s ease both;
}

.si-step {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.75rem;
  font-weight: 500;
}

.si-dot {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 600;
  flex-shrink: 0;
  transition: all 0.3s;
}

.si-step--done   .si-dot { background: var(--auth-gold, #c9893c); color: #fff; }
.si-step--active .si-dot { background: var(--auth-ink, #0e0b07);  color: #fff; }
.si-step--idle   .si-dot {
  background: var(--auth-warm, #f0e8d6);
  color: var(--auth-muted, #8a7f72);
  border: 1.5px solid var(--auth-border, #e0d8cc);
}

.si-label { font-size: 0.73rem; }

.si-step--done   .si-label { color: var(--auth-gold, #c9893c); }
.si-step--active .si-label { color: var(--auth-ink, #0e0b07); font-weight: 600; }
.si-step--idle   .si-label { color: var(--auth-muted, #8a7f72); }

.si-line {
  flex: 1;
  height: 1.5px;
  background: var(--auth-border, #e0d8cc);
  margin: 0 10px;
  position: relative;
}

.si-line--done::after {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--auth-gold, #c9893c);
  transform-origin: left;
  animation: si-lineGrow 0.4s ease both;
}

@keyframes si-lineGrow {
  from { transform: scaleX(0); }
  to   { transform: scaleX(1); }
}

@keyframes si-fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
