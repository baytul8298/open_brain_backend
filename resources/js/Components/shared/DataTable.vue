<script setup lang="ts" generic="TData">
import { ref, computed, watch } from 'vue'
import {
  useVueTable,
  getCoreRowModel,
  getSortedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  type ColumnDef,
  type SortingState,
  type ColumnFiltersState,
  type VisibilityState,
  FlexRender,
} from '@tanstack/vue-table'
import { Search, SlidersHorizontal, ChevronLeft, ChevronRight, ChevronDown } from 'lucide-vue-next'
import Checkbox from './Checkbox.vue'

interface Props {
  data: TData[]
  columns: ColumnDef<TData>[]
  searchable?: boolean
  searchPlaceholder?: string
  selectable?: boolean
  filterable?: boolean
  loading?: boolean
  pageSize?: number
  pageSizeOptions?: number[]
}

const props = withDefaults(defineProps<Props>(), {
  searchable: true,
  searchPlaceholder: 'Search here...',
  selectable: true,
  filterable: true,
  loading: false,
  pageSize: 10,
  pageSizeOptions: () => [10, 15, 25, 50, 100],
})

const emit = defineEmits<{
  'row-select': [rows: TData[]]
  'row-click': [row: TData]
}>()

const globalFilter = ref('')
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const showFilters = ref(false)
const currentPageSize = ref(props.pageSize)

const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return props.columns
  },
  state: {
    get sorting() {
      return sorting.value
    },
    get columnFilters() {
      return columnFilters.value
    },
    get columnVisibility() {
      return columnVisibility.value
    },
    get rowSelection() {
      return rowSelection.value
    },
    get globalFilter() {
      return globalFilter.value
    },
  },
  enableRowSelection: props.selectable,
  onSortingChange: (updaterOrValue) => {
    sorting.value =
      typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue
  },
  onColumnFiltersChange: (updaterOrValue) => {
    columnFilters.value =
      typeof updaterOrValue === 'function' ? updaterOrValue(columnFilters.value) : updaterOrValue
  },
  onColumnVisibilityChange: (updaterOrValue) => {
    columnVisibility.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(columnVisibility.value)
        : updaterOrValue
  },
  onRowSelectionChange: (updaterOrValue) => {
    rowSelection.value =
      typeof updaterOrValue === 'function' ? updaterOrValue(rowSelection.value) : updaterOrValue
  },
  onGlobalFilterChange: (updaterOrValue) => {
    globalFilter.value =
      typeof updaterOrValue === 'function' ? updaterOrValue(globalFilter.value) : updaterOrValue
  },
  getCoreRowModel: getCoreRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  initialState: {
    pagination: {
      pageSize: props.pageSize,
    },
  },
})

// Watch page size changes
watch(currentPageSize, (newSize) => {
  table.setPageSize(newSize)
})

// Watch row selection and emit changes
watch(rowSelection, () => {
  const selectedRows = table.getFilteredSelectedRowModel().rows.map((row) => row.original)
  emit('row-select', selectedRows)
})

const paginationInfo = computed(() => {
  const currentPage = table.getState().pagination.pageIndex + 1
  const totalPages = table.getPageCount()
  const pageSize = table.getState().pagination.pageSize
  const totalRows = table.getFilteredRowModel().rows.length
  const startRow = (currentPage - 1) * pageSize + 1
  const endRow = Math.min(currentPage * pageSize, totalRows)

  return {
    currentPage,
    totalPages,
    totalRows,
    startRow,
    endRow,
    pageSize,
  }
})

function toggleFilters() {
  showFilters.value = !showFilters.value
}

function resetFilters() {
  globalFilter.value = ''
  columnFilters.value = []
}
</script>

<template>
  <div class="w-full space-y-4">
    <!-- Header: Search and Filters -->
    <div class="flex items-center justify-between gap-4">
      <!-- Search Input -->
      <div v-if="searchable" class="relative flex-1 max-w-md">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" :size="18" />
        <input
          v-model="globalFilter"
          type="text"
          :placeholder="searchPlaceholder"
          class="w-full pl-10 pr-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary transition-base"
        />
      </div>

      <!-- Filter Toggle Button -->
      <button
        v-if="filterable"
        @click="toggleFilters"
        class="btn-base btn-outline flex items-center gap-2"
      >
        <SlidersHorizontal :size="18" />
        <span>Filters</span>
      </button>
    </div>

    <!-- Table Container -->
    <div class="flex gap-4">
      <!-- Main Table -->
      <div class="flex-1 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="overflow-x-auto">
          <table class="table-base">
            <thead class="table-header">
              <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                <th
                  v-for="header in headerGroup.headers"
                  :key="header.id"
                  class="px-4 py-3 text-left text-sm font-semibold text-gray-700"
                  :style="{ width: header.getSize() !== 150 ? `${header.getSize()}px` : 'auto' }"
                >
                  <FlexRender
                    v-if="!header.isPlaceholder"
                    :render="header.column.columnDef.header"
                    :props="header.getContext()"
                  />
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-if="loading"
                class="table-row"
              >
                <td :colspan="table.getAllColumns().length" class="table-cell text-center py-8">
                  <div class="flex items-center justify-center gap-2 text-gray-500">
                    <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <span>Loading...</span>
                  </div>
                </td>
              </tr>
              <tr
                v-else-if="table.getRowModel().rows.length === 0"
                class="table-row"
              >
                <td :colspan="table.getAllColumns().length" class="table-cell text-center py-8 text-gray-500">
                  No data available
                </td>
              </tr>
              <tr
                v-else
                v-for="row in table.getRowModel().rows"
                :key="row.id"
                class="table-row"
                @click="emit('row-click', row.original)"
              >
                <td
                  v-for="cell in row.getVisibleCells()"
                  :key="cell.id"
                  class="table-cell"
                >
                  <FlexRender
                    :render="cell.column.columnDef.cell"
                    :props="cell.getContext()"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50">
          <!-- Left: Showing Info -->
          <div class="text-sm text-gray-600">
            Showing {{ paginationInfo.startRow }} to {{ paginationInfo.endRow }} of
            {{ paginationInfo.totalRows }} entries
          </div>

          <!-- Center: Page Size Selector -->
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Show</span>
            <select
              v-model="currentPageSize"
              class="px-2 py-1 border border-gray-300 rounded cursor-pointer focus:outline-none focus:border-primary text-sm"
            >
              <option v-for="size in pageSizeOptions" :key="size" :value="size">
                {{ size }}
              </option>
            </select>
            <span class="text-sm text-gray-600">entries</span>
          </div>

          <!-- Right: Pagination Controls -->
          <div class="flex items-center gap-1">
            <button
              @click="table.previousPage()"
              :disabled="!table.getCanPreviousPage()"
              class="p-2 rounded hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronLeft :size="18" />
            </button>

            <div class="flex items-center gap-1 px-3">
              <span class="text-sm text-gray-600">
                Page {{ paginationInfo.currentPage }} of {{ paginationInfo.totalPages }}
              </span>
            </div>

            <button
              @click="table.nextPage()"
              :disabled="!table.getCanNextPage()"
              class="p-2 rounded hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronRight :size="18" />
            </button>
          </div>
        </div>
      </div>

      <!-- Filters Sidebar -->
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
          class="w-64 bg-white rounded-lg border border-gray-200 p-4 space-y-4"
        >
          <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Filters</h3>
            <button
              @click="resetFilters"
              class="text-sm text-primary hover:underline"
            >
              Reset
            </button>
          </div>

          <slot name="filters" :table="table" />

          <div v-if="!$slots.filters" class="text-sm text-gray-500 text-center py-4">
            No filters available
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>
