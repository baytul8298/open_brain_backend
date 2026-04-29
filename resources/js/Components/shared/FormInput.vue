<script setup lang="ts">
import { ref } from 'vue'

defineOptions({ inheritAttrs: false })

interface Props {
  label: string
  modelValue: string | number
  type?: string
  placeholder?: string
  required?: boolean
  error?: string
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  required: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
}>()

const isFocused = ref(false)

function handleInput(event: Event) {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
}
</script>

<template>
  <div class="form-field">
    <label class="form-field__label">
      {{ label }}
      <span v-if="required" class="form-field__required">*</span>
    </label>
    <div class="form-field__wrap">
      <span v-if="$slots.icon" class="form-field__icon">
        <slot name="icon" />
      </span>
      <input
        v-bind="$attrs"
        :type="type"
        :value="modelValue"
        @input="handleInput"
        @focus="isFocused = true"
        @blur="isFocused = false"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        class="form-field__input"
        :class="{
          'form-field__input--icon': $slots.icon,
          'form-field__input--focused': isFocused && !error,
          'form-field__input--error': !!error,
          'form-field__input--disabled': disabled,
        }"
      />
    </div>
    <p v-if="error" class="form-field__error">{{ error }}</p>
  </div>
</template>

<style scoped>
.form-field {
  margin-bottom: 1rem;
}

.form-field__label {
  display: block;
  font-size: 0.76rem;
  font-weight: 500;
  color: var(--auth-ink, #0e0b07);
  margin-bottom: 7px;
  letter-spacing: 0.04em;
}

.form-field__required {
  color: var(--color-error, #ef4444);
  margin-left: 2px;
}

.form-field__wrap {
  position: relative;
}

.form-field__icon {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--auth-muted, #8a7f72);
  pointer-events: none;
  display: flex;
  align-items: center;
}

.form-field__input {
  width: 100%;
  padding: 12px 13px;
  font-family: 'DM Sans', 'Instrument Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--auth-ink, #0e0b07);
  background: var(--auth-warm, #f0e8d6);
  border: 1.5px solid transparent;
  border-radius: 11px;
  outline: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  appearance: none;
}

.form-field__input--icon {
  padding-left: 38px;
}

.form-field__input::placeholder {
  color: var(--auth-muted, #8a7f72);
}

.form-field__input--focused {
  border-color: var(--auth-gold, #c9893c);
  background: #fff;
  box-shadow: 0 0 0 4px rgba(201, 137, 60, 0.1);
}

.form-field__input--error {
  border-color: var(--color-error, #ef4444);
  background: #fff;
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
}

.form-field__input--disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.form-field__error {
  margin-top: 5px;
  font-size: 0.73rem;
  color: var(--color-error, #ef4444);
}
</style>
