<script setup lang="ts">
import { Check, ChevronDown } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface Option {
  value: string | number
  label: string
}

interface Props {
  label: string
  modelValue: string | number
  options: Option[]
  placeholder?: string
  required?: boolean
  error?: string
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select an option',
  required: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
}>()

const searchTerm = ref('')
const open = ref(false)
const touched = ref(false)

const filteredOptions = computed(() => {
  if (!searchTerm.value) return props.options

  return props.options.filter((option) =>
    option.label.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

const selectedLabel = computed(() => {
  const selected = props.options.find((opt) => opt.value === props.modelValue)
  return selected ? selected.label : ''
})

const hasValue = computed(() => {
  return props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined
})

const isActive = computed(() => {
  return open.value || hasValue.value
})

function toggleDropdown() {
  if (!props.disabled) {
    touched.value = true
    open.value = !open.value
    if (open.value) {
      searchTerm.value = ''
    }
  }
}

function handleSelect(value: string | number) {
  emit('update:modelValue', value)
  open.value = false
  searchTerm.value = ''
}

function closeDropdown() {
  open.value = false
  searchTerm.value = ''
}
</script>

<template>
  <div class="relative" v-click-away="closeDropdown">
    <div class="relative">
      <input
        v-if="open"
        v-model="searchTerm"
        type="text"
        placeholder="Search..."
        class="w-full px-4 py-3 border rounded-md transition-all duration-200 outline-none bg-white peer pr-10"
        :class="{
          'border-orange-500 border-2': touched && open && !error,
          'border-red-500 border-2': error,
          'border-gray-300': (!touched || !open) && !error,
          'opacity-60 cursor-not-allowed': disabled
        }"
        :disabled="disabled"
        autofocus
      />
      <div
        v-else
        @click="toggleDropdown"
        class="w-full px-4 py-3 border rounded-md transition-all duration-200 outline-none bg-white peer pr-10 cursor-pointer"
        :class="{
          'border-orange-500 border-2': touched && open && !error,
          'border-red-500 border-2': error,
          'border-gray-300': (!touched || !open) && !error,
          'opacity-60 cursor-not-allowed': disabled
        }"
      >
        <span v-if="hasValue" class="text-gray-900">{{ selectedLabel }}</span>
        <span v-else class="text-gray-400">{{ placeholder }}</span>
      </div>

      <label
        class="absolute left-4 transition-all duration-200 pointer-events-none bg-white px-1"
        :class="{
          '-top-2.5 text-xs font-medium': isActive,
          'top-1/2 -translate-y-1/2 text-base': !isActive,
          'text-red-500': error,
          'text-orange-500': touched && open && !error,
          'text-gray-500': (!touched || !open) && !error
        }"
      >
        {{ label }}
        <span v-if="required" class="text-red-500 ml-0.5">*</span>
      </label>

      <button
        type="button"
        @click="toggleDropdown"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition-transform duration-200"
        :class="{ 'rotate-180': open }"
        :disabled="disabled"
      >
        <ChevronDown :size="20" />
      </button>
    </div>

    <!-- Dropdown List -->
    <Transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="open"
        class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
      >
        <div v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-gray-500">
          No results found
        </div>
        <button
          v-for="option in filteredOptions"
          :key="option.value"
          type="button"
          @click="handleSelect(option.value)"
          class="relative w-full text-left px-4 py-2 hover:bg-gray-100 transition-colors duration-150"
          :class="{ 'bg-orange-50 text-orange-600': option.value === modelValue }"
        >
          <span>{{ option.label }}</span>
          <Check
            v-if="option.value === modelValue"
            :size="16"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-orange-500"
          />
        </button>
      </div>
    </Transition>

    <p v-if="error" class="mt-1 text-sm text-red-500">
      {{ error }}
    </p>
  </div>
</template>