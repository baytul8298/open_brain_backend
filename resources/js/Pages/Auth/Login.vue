<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue'
import FormInput from '@/Components/shared/FormInput.vue'
import PasswordInput from '@/Components/shared/PasswordInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Mail } from 'lucide-vue-next'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Login" />

  <AuthLayout>
    <!-- Form header -->
    <div class="auth-page-header">
      <div class="auth-page-tag">
        <span class="auth-page-tag__dot"></span>
        Welcome back
      </div>
      <h2 class="auth-page-title">Sign in to your<br/>account</h2>
      <p class="auth-page-sub">
        New here? <Link href="/register" class="auth-page-link">Create a free account</Link>
      </p>
    </div>

    <form @submit.prevent="submit" class="auth-form">
      <FormInput
        label="Email address"
        v-model="form.email"
        type="email"
        placeholder="you@example.com"
        :required="true"
        :error="form.errors.email"
        autofocus
      >
        <template #icon>
          <Mail :size="15" />
        </template>
      </FormInput>

      <PasswordInput
        label="Password"
        v-model="form.password"
        placeholder="Enter your password"
        :required="true"
        :error="form.errors.password"
      />

      <!-- Forgot password -->
      <div class="auth-field-footer">
        <Link href="/forgot-password" class="auth-forgot">Forgot password?</Link>
      </div>

      <!-- Remember me -->
      <div class="auth-remember-row">
        <input
          v-model="form.remember"
          type="checkbox"
          id="remember"
          class="auth-check__input"
        />
        <label for="remember" class="auth-check__label">Keep me signed in</label>
      </div>

      <button
        type="submit"
        :disabled="form.processing"
        class="auth-submit-btn"
      >
        {{ form.processing ? 'Signing in...' : 'Continue to Dashboard →' }}
      </button>

      <!-- Divider -->
      <div class="auth-divider"><span>or sign in with</span></div>

      <!-- Social buttons -->
      <div class="auth-socials">
        <button class="auth-btn-social" type="button">
          <svg viewBox="0 0 24 24" width="18" height="18">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          Google
        </button>
        <button class="auth-btn-social" type="button">
          <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
            <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z"/>
          </svg>
          GitHub
        </button>
      </div>
    </form>
  </AuthLayout>
</template>

<style scoped>
/* Form header */
.auth-page-header {
  margin-bottom: 38px;
  animation: auth-fadeUp 0.7s 0.1s ease both;
}

.auth-page-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--auth-rust, #b84b2f);
  margin-bottom: 14px;
}

.auth-page-tag__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--auth-rust, #b84b2f);
}

.auth-page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.1rem;
  color: var(--auth-ink, #0e0b07);
  line-height: 1.2;
  margin-bottom: 8px;
}

.auth-page-sub {
  font-size: 0.88rem;
  color: var(--auth-muted, #8a7f72);
  font-weight: 300;
}

.auth-page-link {
  color: var(--auth-gold, #c9893c);
  font-weight: 500;
  text-decoration: none;
  border-bottom: 1px solid var(--auth-gold-light, #e8b96a);
  transition: color 0.2s;
}

.auth-page-link:hover {
  color: var(--auth-rust, #b84b2f);
}

/* Form */
.auth-form {
  display: flex;
  flex-direction: column;
  animation: auth-fadeUp 0.7s 0.2s ease both;
}

/* Forgot password */
.auth-field-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 6px;
  margin-bottom: 20px;
}

.auth-forgot {
  font-size: 0.78rem;
  color: var(--auth-muted, #8a7f72);
  text-decoration: none;
  transition: color 0.2s;
}

.auth-forgot:hover {
  color: var(--auth-gold, #c9893c);
}

/* Remember me row */
.auth-remember-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 28px;
  animation: auth-fadeUp 0.7s 0.4s ease both;
}

.auth-check__input {
  width: 18px;
  height: 18px;
  border-radius: 5px;
  border: 1.5px solid #cfc5b5;
  background: var(--auth-warm, #f0e8d6);
  cursor: pointer;
  appearance: none;
  position: relative;
  flex-shrink: 0;
  transition: border-color 0.2s, background 0.2s;
}

.auth-check__input:checked {
  background: var(--auth-gold, #c9893c);
  border-color: var(--auth-gold, #c9893c);
}

.auth-check__input:checked::after {
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

.auth-check__label {
  font-size: 0.84rem;
  color: var(--auth-muted, #8a7f72);
  cursor: pointer;
}

/* Submit button */
.auth-submit-btn {
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
  animation: auth-fadeUp 0.7s 0.48s ease both;
}

.auth-submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, transparent 60%);
  pointer-events: none;
}

.auth-submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(184, 75, 47, 0.35);
}

.auth-submit-btn:active:not(:disabled) {
  transform: translateY(0);
}

.auth-submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Divider */
.auth-divider {
  display: flex;
  align-items: center;
  gap: 14px;
  margin: 26px 0;
  animation: auth-fadeUp 0.7s 0.56s ease both;
}

.auth-divider::before,
.auth-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #e0d8cc;
}

.auth-divider span {
  font-size: 0.75rem;
  color: var(--auth-muted, #8a7f72);
  letter-spacing: 0.06em;
  text-transform: uppercase;
}

/* Social buttons */
.auth-socials {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  animation: auth-fadeUp 0.7s 0.62s ease both;
}

.auth-btn-social {
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
  border: 1.5px solid #e0d8cc;
  border-radius: 11px;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
}

.auth-btn-social:hover {
  background: #fff;
  border-color: var(--auth-gold-light, #e8b96a);
  transform: translateY(-1px);
}

@keyframes auth-fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
