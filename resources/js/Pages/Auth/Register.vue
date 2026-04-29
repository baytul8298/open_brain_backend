<script setup lang="ts">
import RegisterLayout from '@/Layouts/RegisterLayout.vue'
import FormInput from '@/Components/shared/FormInput.vue'
import PasswordInput from '@/Components/shared/PasswordInput.vue'
import StepIndicator from '@/Components/shared/StepIndicator.vue'
import SectionLabel from '@/Components/shared/SectionLabel.vue'
import ChipSelector from '@/Components/shared/ChipSelector.vue'
import GridSelector from '@/Components/shared/GridSelector.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import {
  User, Mail, Phone,
  Plus, Sun, FlaskConical, Shield,
  AlignLeft, Clock, Code2, Globe,
  BookMarked, Monitor,
} from 'lucide-vue-next'

const steps = ['Account', 'Preferences', 'Confirm']

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  class_level: '',
  subjects: [] as string[],
  agreed: false,
})

const classOptions = [
  { value: '6',  label: '6',  sublabel: 'Class' },
  { value: '7',  label: '7',  sublabel: 'Class' },
  { value: '8',  label: '8',  sublabel: 'Class' },
  { value: '9',  label: '9',  sublabel: 'Class' },
  { value: '10', label: '10', sublabel: 'Class' },
  { value: '11', label: '11', sublabel: 'Class' },
  { value: '12', label: '12', sublabel: 'Class' },
  { value: 'O',  label: 'O',  sublabel: 'Level', small: true },
  { value: 'A',  label: 'A',  sublabel: 'Level', small: true },
]

const subjectOptions = [
  { value: 'math',      label: 'Mathematics',  icon: Plus },
  { value: 'physics',   label: 'Physics',       icon: Sun },
  { value: 'chemistry', label: 'Chemistry',     icon: FlaskConical },
  { value: 'biology',   label: 'Biology',       icon: Shield },
  { value: 'english',   label: 'English',       icon: AlignLeft },
  { value: 'history',   label: 'History',       icon: Clock },
  { value: 'cs',        label: 'Computer Sc.',  icon: Code2 },
  { value: 'geography', label: 'Geography',     icon: Globe },
  { value: 'bangla',    label: 'Bangla',        icon: BookMarked },
  { value: 'ict',       label: 'ICT',           icon: Monitor },
]

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      name: `${data.first_name} ${data.last_name}`.trim(),
    }))
    .post('/register', {
      onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
  <Head title="Register" />

  <RegisterLayout>
    <!-- Step Indicator -->
    <StepIndicator :steps="steps" :current-step="1" />

    <!-- Form Header -->
    <div class="rg-header">
      <div class="rg-tag">
        <span class="rg-tag__dot"></span>
        Free Account
      </div>
      <h2 class="rg-title">Create your<br/>student profile</h2>
      <p class="rg-sub">
        Already have an account?
        <Link href="/login" class="rg-link">Sign in here</Link>
      </p>
    </div>

    <form @submit.prevent="submit">

      <!-- ── Personal Info ─────────────────────── -->
      <SectionLabel label="Personal Info" />

      <div class="rg-grid-2">
        <FormInput
          label="First Name"
          v-model="form.first_name"
          type="text"
          placeholder="Rahim"
          :required="true"
          :error="form.errors.first_name"
          autofocus
        >
          <template #icon><User :size="15" /></template>
        </FormInput>
        <FormInput
          label="Last Name"
          v-model="form.last_name"
          type="text"
          placeholder="Uddin"
          :required="true"
          :error="form.errors.last_name"
        >
          <template #icon><User :size="15" /></template>
        </FormInput>
      </div>

      <FormInput
        label="Email Address"
        v-model="form.email"
        type="email"
        placeholder="student@example.com"
        :required="true"
        :error="form.errors.email"
      >
        <template #icon><Mail :size="15" /></template>
      </FormInput>

      <FormInput
        label="Phone / WhatsApp"
        v-model="form.phone"
        type="tel"
        placeholder="+880 1X XX-XXXXXX"
        :error="form.errors.phone"
      >
        <template #icon><Phone :size="15" /></template>
      </FormInput>

      <PasswordInput
        label="Create Password"
        v-model="form.password"
        placeholder="Min. 8 characters"
        :required="true"
        :error="form.errors.password"
        :show-strength="true"
      />

      <!-- ── Your Class ──────────────────────────── -->
      <SectionLabel label="Your Class" />

      <span class="rg-sublabel">Select your current class</span>
      <GridSelector
        v-model="form.class_level"
        :options="classOptions"
        :columns="6"
      />
      <p class="rg-hint">You can change this later in your profile.</p>

      <!-- ── Preferred Subjects ─────────────────── -->
      <SectionLabel label="Preferred Subjects" />

      <span class="rg-sublabel">Choose subjects you want to study</span>
      <ChipSelector
        v-model="form.subjects"
        :options="subjectOptions"
      />
      <p class="rg-hint">Select one or more subjects. You can add more later.</p>

      <!-- ── Terms ─────────────────────────────── -->
      <div class="rg-terms">
        <input
          id="agreed"
          v-model="form.agreed"
          type="checkbox"
          class="rg-terms__check"
        />
        <p class="rg-terms__text">
          <label for="agreed">
            I agree to the
            <a href="#" class="rg-terms__link">Terms of Service</a>
            and
            <a href="#" class="rg-terms__link">Privacy Policy</a>.
            I confirm I am a student or guardian registering on behalf of a student.
          </label>
        </p>
      </div>

      <!-- ── Submit ─────────────────────────────── -->
      <button
        type="submit"
        :disabled="form.processing || !form.agreed"
        class="rg-submit"
      >
        {{ form.processing ? 'Creating Account...' : 'Create My Free Account →' }}
      </button>

      <!-- ── Divider ────────────────────────────── -->
      <div class="rg-divider">
        <span>or sign up with</span>
      </div>

      <!-- ── Social Buttons ─────────────────────── -->
      <div class="rg-socials">
        <button class="rg-social-btn" type="button">
          <svg viewBox="0 0 24 24" width="18" height="18">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          Google
        </button>
        <button class="rg-social-btn" type="button">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z"/>
          </svg>
          GitHub
        </button>
      </div>

    </form>
  </RegisterLayout>
</template>

<style scoped>
/* ── Form Header ─────────────────────────── */
.rg-header {
  margin-bottom: 30px;
  animation: rg-fadeUp 0.7s 0.1s ease both;
}

.rg-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--auth-rust, #b84b2f);
  margin-bottom: 12px;
}

.rg-tag__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--auth-rust, #b84b2f);
}

.rg-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.9rem;
  color: var(--auth-ink, #0e0b07);
  line-height: 1.2;
  margin-bottom: 7px;
}

.rg-sub {
  font-size: 0.86rem;
  color: var(--auth-muted, #8a7f72);
  font-weight: 300;
}

.rg-link {
  color: var(--auth-gold, #c9893c);
  font-weight: 500;
  text-decoration: none;
  border-bottom: 1px solid var(--auth-gold-light, #e8b96a);
  transition: color 0.2s;
}

.rg-link:hover { color: var(--auth-rust, #b84b2f); }

/* ── Two-column grid ─────────────────────── */
.rg-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

/* ── Sub-label ───────────────────────────── */
.rg-sublabel {
  display: block;
  font-size: 0.76rem;
  font-weight: 500;
  color: var(--auth-ink, #0e0b07);
  margin-bottom: 10px;
  letter-spacing: 0.04em;
}

/* ── Hint text ───────────────────────────── */
.rg-hint {
  font-size: 0.73rem;
  color: var(--auth-muted, #8a7f72);
  margin-top: 5px;
  margin-bottom: 0;
}

/* ── Terms row ───────────────────────────── */
.rg-terms {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin: 20px 0;
  animation: rg-fadeUp 0.7s 0.45s ease both;
}

.rg-terms__check {
  appearance: none;
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  border: 1.5px solid #cfc5b5;
  border-radius: 5px;
  background: var(--auth-warm, #f0e8d6);
  cursor: pointer;
  position: relative;
  margin-top: 1px;
  transition: border-color 0.2s, background 0.2s;
}

.rg-terms__check:checked {
  background: var(--auth-gold, #c9893c);
  border-color: var(--auth-gold, #c9893c);
}

.rg-terms__check:checked::after {
  content: '';
  position: absolute;
  left: 4px;
  top: 1px;
  width: 6px;
  height: 10px;
  border: 2px solid #fff;
  border-top: none;
  border-left: none;
  transform: rotate(45deg);
}

.rg-terms__text {
  font-size: 0.82rem;
  color: var(--auth-muted, #8a7f72);
  line-height: 1.5;
}

.rg-terms__link {
  color: var(--auth-gold, #c9893c);
  text-decoration: none;
  border-bottom: 1px solid var(--auth-gold-light, #e8b96a);
}

/* ── Submit button ───────────────────────── */
.rg-submit {
  width: 100%;
  padding: 14px;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
  font-size: 0.95rem;
  font-weight: 500;
  letter-spacing: 0.03em;
  color: #fff;
  background: linear-gradient(135deg, var(--auth-gold, #c9893c) 0%, var(--auth-rust, #b84b2f) 100%);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: transform 0.18s, box-shadow 0.18s;
  animation: rg-fadeUp 0.7s 0.5s ease both;
}

.rg-submit::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, transparent 60%);
  pointer-events: none;
}

.rg-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(184, 75, 47, 0.35);
}

.rg-submit:active:not(:disabled) { transform: translateY(0); }

.rg-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ── Divider ─────────────────────────────── */
.rg-divider {
  display: flex;
  align-items: center;
  gap: 14px;
  margin: 22px 0;
  animation: rg-fadeUp 0.7s 0.56s ease both;
}

.rg-divider::before,
.rg-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--auth-border, #e0d8cc);
}

.rg-divider span {
  font-size: 0.73rem;
  color: var(--auth-muted, #8a7f72);
  letter-spacing: 0.06em;
  text-transform: uppercase;
  white-space: nowrap;
}

/* ── Social buttons ──────────────────────── */
.rg-socials {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  animation: rg-fadeUp 0.7s 0.62s ease both;
}

.rg-social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
  padding: 11px;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
  font-size: 0.84rem;
  font-weight: 500;
  color: var(--auth-ink, #0e0b07);
  background: var(--auth-warm, #f0e8d6);
  border: 1.5px solid var(--auth-border, #e0d8cc);
  border-radius: 11px;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
}

.rg-social-btn:hover {
  background: #fff;
  border-color: var(--auth-gold-light, #e8b96a);
  transform: translateY(-1px);
}

/* ── Animation ───────────────────────────── */
@keyframes rg-fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ──────────────────────────── */
@media (max-width: 480px) {
  .rg-grid-2 { grid-template-columns: 1fr; }
}
</style>
