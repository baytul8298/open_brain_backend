<script setup lang="ts" generic="TData">
import { ref, computed } from 'vue'
import { Search, SlidersHorizontal, X } from 'lucide-vue-next'

interface Column {
  key: string
  label: string
  sortable?: boolean
  class?: string
}

interface Props {
  data: TData[]
  columns: Column[]
  searchable?: boolean
  searchPlaceholder?: string
  filterable?: boolean
  loading?: boolean
  selectable?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  searchable: true,
  searchPlaceholder: 'Search here...',
  filterable: true,
  loading: false,
  selectable: false,
})

const emit = defineEmits<{
  'row-select': [rows: TData[]]
  'row-click': [row: TData]
}>()

const searchQuery = ref('')
const showFilters = ref(false)
const selectedRows = ref<Set<number>>(new Set())

const filteredData = computed(() => {
  if (!searchQuery.value) return props.data

  return props.data.filter((row: any) => {
    return Object.values(row).some((value) =>
      String(value).toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

function toggleFilters() {
  showFilters.value = !showFilters.value
}

function clearSearch() {
  searchQuery.value = ''
}

function toggleRowSelection(index: number) {
  if (selectedRows.value.has(index)) {
    selectedRows.value.delete(index)
  } else {
    selectedRows.value.add(index)
  }
  emitSelectedRows()
}

function toggleSelectAll() {
  if (selectedRows.value.size === filteredData.value.length) {
    selectedRows.value.clear()
  } else {
    selectedRows.value = new Set(filteredData.value.map((_, index) => index))
  }
  emitSelectedRows()
}

function emitSelectedRows() {
  const selected = filteredData.value.filter((_, index) => selectedRows.value.has(index))
  emit('row-select', selected)
}

function handleRowClick(row: TData) {
  emit('row-click', row)
}
</script>

<template>
  <div class="space-y-4">
    <!-- Header with Search and Filter -->
    <div class="flex items-center justify-between gap-4">
      <div class="flex-1">
        <slot name="header" />
      </div>

      <div class="flex items-center gap-3">
        <!-- Search Box -->
        <div v-if="searchable" class="relative w-80">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="searchPlaceholder"
            class="w-full pl-10 pr-10 py-2 border-2 border-gray-300 rounded-lg transition-base focus:outline-none focus:border-primary"
          />
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
          >
            <X :size="16" />
          </button>
        </div>

        <!-- Filter Button -->
        <button
          v-if="filterable"
          @click="toggleFilters"
          class="p-2 border-2 border-gray-300 rounded-lg hover:border-primary transition-base"
          :class="{ 'bg-primary border-primary text-white': showFilters }"
        >
          <SlidersHorizontal :size="20" />
        </button>
      </div>
    </div>

    <!-- Table and Filters Container -->
    <div class="flex gap-4">
      <!-- Main Table -->
      <div class="flex-1 card-base overflow-hidden">
        <div class="overflow-x-auto">
          <table class="table-base">
            <thead class="table-header">
              <tr>
                <th v-if="selectable" class="w-12">
                  <input
                    type="checkbox"
                    :checked="selectedRows.size === filteredData.length && filteredData.length > 0"
                    @change="toggleSelectAll"
                    class="checkbox-base"
                  />
                </th>
                <th
                  v-for="column in columns"
                  :key="column.key"
                  :class="column.class"
                >
                  {{ column.label }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td :colspan="columns.length + (selectable ? 1 : 0)" class="table-cell text-center py-8">
                  <div class="flex items-center justify-center gap-2 text-gray-500">
                    <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <span>Loading...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredData.length === 0">
                <td :colspan="columns.length + (selectable ? 1 : 0)" class="table-cell text-center py-8 text-gray-500">
                  No data available
                </td>
              </tr>
              <tr
                v-else
                v-for="(row, index) in filteredData"
                :key="index"
                class="table-row"
                @click="handleRowClick(row)"
              >
                <td v-if="selectable" class="table-cell">
                  <input
                    type="checkbox"
                    :checked="selectedRows.has(index)"
                    @change.stop="toggleRowSelection(index)"
                    class="checkbox-base"
                  />
                </td>
                <td
                  v-for="column in columns"
                  :key="column.key"
                  class="table-cell"
                  :class="column.class"
                >
                  <slot :name="`cell-${column.key}`" :row="row" :value="(row as any)[column.key]">
                    {{ (row as any)[column.key] }}
                  </slot>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
          <slot name="footer">
            <div class="text-sm text-gray-600">
              Showing {{ filteredData.length }} of {{ data.length }} entries
            </div>
          </slot>
        </div>
      </div>

      <!-- Filter Sidebar -->
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 translate-x-4"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-4"
      >
        <div
          v-if="showFilters"
          class="w-80 card-base p-4 space-y-4"
        >
          <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Filters</h3>
            <button
              @click="toggleFilters"
              class="text-gray-400 hover:text-gray-600"
            >
              <X :size="20" />
            </button>
          </div>

          <slot name="filters" />

          <div v-if="!$slots.filters" class="text-sm text-gray-500 text-center py-4">
            No filters available
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>
