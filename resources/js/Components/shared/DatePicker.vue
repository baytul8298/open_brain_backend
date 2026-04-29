<script setup lang="ts">
import {
  addMonths,
  eachDayOfInterval,
  endOfMonth,
  endOfWeek,
  format,
  isSameDay,
  isSameMonth,
  isToday,
  startOfMonth,
  startOfWeek,
  subMonths,
} from 'date-fns'
import { Calendar, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface Props {
  label: string
  modelValue: Date | string | null
  placeholder?: string
  required?: boolean
  error?: string
  disabled?: boolean
  minDate?: Date
  maxDate?: Date
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select date',
  required: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: Date | null]
}>()

const isOpen = ref(false)
const currentMonth = ref(new Date())

const selectedDate = computed(() => {
  if (!props.modelValue) return null
  return props.modelValue instanceof Date ? props.modelValue : new Date(props.modelValue)
})

const displayValue = computed(() => {
  if (!selectedDate.value) return ''
  return format(selectedDate.value, 'MMMM dd, yyyy')
})

const hasValue = computed(() => {
  return !!selectedDate.value
})

const isActive = computed(() => {
  return isOpen.value || hasValue.value
})

const monthYear = computed(() => {
  return format(currentMonth.value, 'MMMM yyyy')
})

const daysInMonth = computed(() => {
  const start = startOfMonth(currentMonth.value)
  const end = endOfMonth(currentMonth.value)

  const startWeek = startOfWeek(start, { weekStartsOn: 0 })
  const endWeek = endOfWeek(end, { weekStartsOn: 0 })

  return eachDayOfInterval({ start: startWeek, end: endWeek })
})

const weekDays = ['S', 'M', 'T', 'W', 'T', 'F', 'S']

function handleDateSelect(date: Date) {
  emit('update:modelValue', date)
  isOpen.value = false
}

function toggleCalendar() {
  if (!props.disabled) {
    isOpen.value = !isOpen.value
    if (isOpen.value && selectedDate.value) {
      currentMonth.value = selectedDate.value
    }
  }
}

function closeCalendar() {
  isOpen.value = false
}

function previousMonth() {
  currentMonth.value = subMonths(currentMonth.value, 1)
}

function nextMonth() {
  currentMonth.value = addMonths(currentMonth.value, 1)
}

function isSelected(date: Date) {
  return selectedDate.value ? isSameDay(date, selectedDate.value) : false
}

function isCurrentMonth(date: Date) {
  return isSameMonth(date, currentMonth.value)
}
</script>

<template>
  <div class="relative" v-click-away="closeCalendar">
    <!-- Input Field -->
    <div class="relative" @click="toggleCalendar">
      <input
        :value="displayValue"
        :placeholder="isActive ? placeholder : ''"
        :required="required"
        :disabled="disabled"
        readonly
        class="w-full px-4 py-3 border rounded-md transition-all duration-200 outline-none bg-white peer pr-10 cursor-pointer"
        :class="{
          'border-orange-500 border-2': isOpen && !error,
          'border-red-500 border-2': error,
          'border-gray-300': !isOpen && !error,
          'opacity-60 cursor-not-allowed': disabled
        }"
      />
      
      <label
        class="absolute left-4 transition-all duration-200 pointer-events-none bg-white px-1"
        :class="{
          '-top-2.5 text-xs font-medium': isActive,
          'top-1/2 -translate-y-1/2 text-base': !isActive,
          'text-red-500': error,
          'text-orange-500': isOpen && !error,
          'text-gray-500': !isOpen && !error
        }"
      >
        {{ label }}
        <span v-if="required" class="text-red-500 ml-0.5">*</span>
      </label>

      <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
        <Calendar :size="20" />
      </div>
    </div>

    <!-- Calendar Dropdown -->
    <Transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="absolute z-50 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden w-80"
      >
        <!-- Calendar Header -->
        <div class="bg-orange-500 text-white px-4 py-3">
          <div class="text-xs font-semibold uppercase tracking-wider">SELECT DATE</div>
          <div class="text-2xl font-bold mt-1">
            {{ displayValue || 'Enter date' }}
          </div>
        </div>

        <!-- Calendar Component -->
        <div class="p-4">
          <!-- Month Navigation -->
          <div class="flex items-center justify-between mb-4">
            <button
              type="button"
              @click.stop="previousMonth"
              class="p-1 rounded hover:bg-gray-100 transition-colors text-gray-600"
            >
              <ChevronLeft :size="20" />
            </button>

            <div class="text-sm font-semibold text-orange-500">{{ monthYear }}</div>

            <button
              type="button"
              @click.stop="nextMonth"
              class="p-1 rounded hover:bg-gray-100 transition-colors text-gray-600"
            >
              <ChevronRight :size="20" />
            </button>
          </div>

          <!-- Week Days Header -->
          <div class="grid grid-cols-7 gap-1 mb-2">
            <div
              v-for="day in weekDays"
              :key="day"
              class="w-9 h-9 flex items-center justify-center text-xs font-medium text-gray-500"
            >
              {{ day }}
            </div>
          </div>

          <!-- Calendar Days -->
          <div class="grid grid-cols-7 gap-1">
            <button
              v-for="(day, index) in daysInMonth"
              :key="index"
              type="button"
              @click.stop="handleDateSelect(day)"
              class="w-9 h-9 flex items-center justify-center text-sm rounded-full transition-colors"
              :class="[
                !isCurrentMonth(day) ? 'text-gray-300' : 'text-gray-700 hover:bg-gray-100',
                isSelected(day) ? 'bg-orange-500 text-white font-semibold hover:bg-orange-600' : '',
                isToday(day) && !isSelected(day) ? 'border-2 border-orange-500' : '',
              ]"
            >
              {{ format(day, 'd') }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <p v-if="error" class="mt-1 text-sm text-red-500">
      {{ error }}
    </p>
  </div>
</template>