<script setup lang="ts">
import { computed, ref } from 'vue'
import { Eye, EyeOff } from 'lucide-vue-next'

defineOptions({ inheritAttrs: false })

interface Props {
  label?: string
  modelValue: string
  placeholder?: string
  required?: boolean
  error?: string
  disabled?: boolean
  showStrength?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  label: 'Password',
  placeholder: 'Min. 8 characters',
  required: false,
  disabled: false,
  showStrength: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const isFocused = ref(false)
const showPassword = ref(false)

const strengthScore = computed(() => {
  const val = props.modelValue
  if (!val) return 0
  let score = 0
  if (val.length >= 8) score++
  if (/[A-Z]/.test(val)) score++
  if (/[0-9]/.test(val)) score++
  if (/[^A-Za-z0-9]/.test(val)) score++
  return score
})

const strengthLabel = computed(() => ['', 'Weak', 'Fair', 'Good', 'Strong'][strengthScore.value] ?? '')

const strengthClass = computed(() => {
  if (strengthScore.value <= 1) return 'weak'
  if (strengthScore.value <= 2) return 'medium'
  return 'strong'
})

function handleInput(event: Event) {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
}
</script>

<template>
  <div class="pw-field">
    <label class="pw-field__label">
      {{ label }}
      <span v-if="required" class="pw-field__required">*</span>
    </label>
    <div class="pw-field__wrap">
      <span class="pw-field__icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </span>
      <input
        v-bind="$attrs"
        :type="showPassword ? 'text' : 'password'"
        :value="modelValue"
        @input="handleInput"
        @focus="isFocused = true"
        @blur="isFocused = false"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        class="pw-field__input"
        :class="{
          'pw-field__input--focused': isFocused && !error,
          'pw-field__input--error': !!error,
          'pw-field__input--disabled': disabled,
        }"
      />
      <button
        type="button"
        class="pw-field__toggle"
        @click="showPassword = !showPassword"
        :aria-label="showPassword ? 'Hide password' : 'Show password'"
      >
        <EyeOff v-if="showPassword" class="pw-field__toggle-icon" />
        <Eye v-else class="pw-field__toggle-icon" />
      </button>
    </div>
    <p v-if="error" class="pw-field__error">{{ error }}</p>

    <div v-if="showStrength && modelValue" class="pw-strength">
      <div class="pw-strength__bars">
        <div
          v-for="i in 4"
          :key="i"
          class="pw-strength__bar"
          :class="i <= strengthScore ? `pw-strength__bar--${strengthClass}` : ''"
        />
      </div>
      <span class="pw-strength__text" :class="`pw-strength__text--${strengthClass}`">
        {{ strengthLabel }}
      </span>
    </div>
  </div>
</template>

<style scoped>
.pw-field {
  margin-bottom: 1rem;
}

.pw-field__label {
  display: block;
  font-size: 0.76rem;
  font-weight: 500;
  color: var(--auth-ink, #0e0b07);
  margin-bottom: 7px;
  letter-spacing: 0.04em;
}

.pw-field__required {
  color: var(--color-error, #ef4444);
  margin-left: 2px;
}

.pw-field__wrap {
  position: relative;
}

.pw-field__icon {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--auth-muted, #8a7f72);
  pointer-events: none;
  display: flex;
  align-items: center;
}

.pw-field__input {
  width: 100%;
  padding: 12px 40px 12px 38px;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--auth-ink, #0e0b07);
  background: var(--auth-warm, #f0e8d6);
  border: 1.5px solid transparent;
  border-radius: 11px;
  outline: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}

.pw-field__input::placeholder {
  color: var(--auth-muted, #8a7f72);
}

.pw-field__input--focused {
  border-color: var(--auth-gold, #c9893c);
  background: #fff;
  box-shadow: 0 0 0 4px rgba(201, 137, 60, 0.1);
}

.pw-field__input--error {
  border-color: var(--color-error, #ef4444);
  background: #fff;
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
}

.pw-field__input--disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.pw-field__toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--auth-muted, #8a7f72);
  padding: 0;
  display: flex;
  align-items: center;
  transition: color 0.2s;
}

.pw-field__toggle:hover {
  color: var(--auth-gold, #c9893c);
}

.pw-field__toggle-icon {
  width: 15px;
  height: 15px;
}

.pw-field__error {
  margin-top: 5px;
  font-size: 0.73rem;
  color: var(--color-error, #ef4444);
}

/* Strength meter */
.pw-strength {
  margin-top: 8px;
}

.pw-strength__bars {
  display: flex;
  gap: 4px;
  margin-bottom: 4px;
}

.pw-strength__bar {
  flex: 1;
  height: 3px;
  border-radius: 2px;
  background: var(--auth-border, #e0d8cc);
  transition: background 0.3s;
}

.pw-strength__bar--weak   { background: #e05c5c; }
.pw-strength__bar--medium { background: var(--auth-gold, #c9893c); }
.pw-strength__bar--strong { background: #5cb85c; }

.pw-strength__text {
  font-size: 0.7rem;
  color: var(--auth-muted, #8a7f72);
}

.pw-strength__text--weak   { color: #e05c5c; }
.pw-strength__text--medium { color: var(--auth-gold, #c9893c); }
.pw-strength__text--strong { color: #5cb85c; }
</style>
