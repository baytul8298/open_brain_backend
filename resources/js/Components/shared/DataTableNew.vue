<script setup lang="ts" generic="TData">
import { MoreVertical, Search, SlidersHorizontal } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ProgressBar from './ProgressBar.vue';

interface Column {
  key: string
  label: string
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
  showActions?: boolean
  itemsPerPage?: number
  itemsPerPageOptions?: number[]
}

const props = withDefaults(defineProps<Props>(), {
  searchable: true,
  searchPlaceholder: 'Search here...',
  filterable: true,
  loading: false,
  selectable: true,
  showActions: true,
  itemsPerPage: 10,
  itemsPerPageOptions: () => [10, 25, 50, 100],
})

const emit = defineEmits<{
  'row-select': [rows: TData[]]
  'row-click': [row: TData]
  'action-edit': [row: TData]
  'action-delete': [row: TData]
  'apply-filters': []
  'reset-filters': []
}>()

const searchQuery = ref('')
const showFilters = ref(false)
const selectedRows = ref<Set<number>>(new Set())
const activeActionMenu = ref<number | null>(null)
const isApplyingFilters = ref(false)
const filterButtonRef = ref<HTMLButtonElement | null>(null)
const filterDropdownStyle = ref<Record<string, string>>({})
const actionMenuRefs = ref<Map<number, HTMLElement>>(new Map())
const actionMenuStyle = ref<Record<string, string>>({})
const currentPage = ref(1)
const perPage = ref(props.itemsPerPage)

const filteredData = computed(() => {
  if (!searchQuery.value) return props.data

  return props.data.filter((row: any) => {
    return Object.values(row).some((value) =>
      String(value).toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  const end = start + perPage.value
  return filteredData.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredData.value.length / perPage.value)
})

const paginationRange = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const delta = 2 // Number of pages to show on each side of current page
  const range: (number | string)[] = []

  if (total <= 10) {
    // Show all pages if 10 or fewer
    for (let i = 1; i <= total; i++) {
      range.push(i)
    }
  } else {
    // Show first page
    range.push(1)

    // Calculate start and end of middle range
    let start = Math.max(2, current - delta)
    let end = Math.min(total - 1, current + delta)

    // Add ellipsis after first page if needed
    if (start > 2) {
      range.push('...')
    }

    // Add middle pages
    for (let i = start; i <= end; i++) {
      range.push(i)
    }

    // Add ellipsis before last page if needed
    if (end < total - 1) {
      range.push('...')
    }

    // Show last page
    range.push(total)
  }

  return range
})

const paginationInfo = computed(() => {
  const start = filteredData.value.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1
  const end = Math.min(currentPage.value * perPage.value, filteredData.value.length)
  return {
    start,
    end,
    total: filteredData.value.length
  }
})

const allSelected = computed(() => {
  return paginatedData.value.length > 0 && selectedRows.value.size === paginatedData.value.length
})

function toggleFilters() {
  showFilters.value = !showFilters.value
  if (showFilters.value && filterButtonRef.value) {
    updateDropdownPosition()
  }
}

function updateDropdownPosition() {
  if (!filterButtonRef.value) return

  const buttonRect = filterButtonRef.value.getBoundingClientRect()
  const dropdownWidth = 320 // 80 * 4 = 320px (w-80)

  // Position dropdown below the button, aligned to the right
  filterDropdownStyle.value = {
    top: `${buttonRect.bottom + 8}px`,
    left: `${buttonRect.right - dropdownWidth}px`,
  }
}

function closeFilters() {
  showFilters.value = false
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
  if (allSelected.value) {
    selectedRows.value.clear()
  } else {
    selectedRows.value = new Set(paginatedData.value.map((_, index) => index))
  }
  emitSelectedRows()
}

function emitSelectedRows() {
  const selected = paginatedData.value.filter((_, index) => selectedRows.value.has(index))
  emit('row-select', selected)
}

function handleRowClick(row: TData, event: Event) {
  const target = event.target as HTMLElement
  if (!target.closest('input[type="checkbox"]') && !target.closest('.action-menu')) {
    emit('row-click', row)
  }
}

function setActionMenuRef(index: number, el: any) {
  if (el) {
    actionMenuRefs.value.set(index, el)
  }
}

function updateActionMenuPosition(index: number) {
  const buttonEl = actionMenuRefs.value.get(index)
  if (!buttonEl) return

  const buttonRect = buttonEl.getBoundingClientRect()
  const dropdownWidth = 192 // 48 * 4 = 192px (w-48)
  const dropdownHeight = 80 // Approximate height

  // Calculate position
  let top = buttonRect.bottom + 4
  let left = buttonRect.right - dropdownWidth

  // Check if dropdown would go off bottom of screen
  if (top + dropdownHeight > window.innerHeight) {
    top = buttonRect.top - dropdownHeight - 4
  }

  // Check if dropdown would go off left of screen
  if (left < 8) {
    left = 8
  }

  actionMenuStyle.value = {
    top: `${top}px`,
    left: `${left}px`,
  }
}

function toggleActionMenu(index: number) {
  if (activeActionMenu.value === index) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = index
    updateActionMenuPosition(index)
  }
}

function closeActionMenu() {
  activeActionMenu.value = null
}

function handleApplyFilters() {
  isApplyingFilters.value = true
  emit('apply-filters')
  // Hide progress bar after a delay to simulate loading
  setTimeout(() => {
    isApplyingFilters.value = false
  }, 1500)
}

function handleResetFilters() {
  isApplyingFilters.value = true
  emit('reset-filters')
  // Hide progress bar after a delay to simulate loading
  setTimeout(() => {
    isApplyingFilters.value = false
  }, 1500)
}

function goToPage(page: number) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

function goToFirstPage() {
  currentPage.value = 1
}

function goToLastPage() {
  currentPage.value = totalPages.value
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

function previousPage() {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

function changePerPage(newPerPage: number) {
  perPage.value = newPerPage
  currentPage.value = 1 // Reset to first page when changing items per page
}
</script>

<template>
  <div class="card-base overflow-hidden">
    <!-- Table Header with Search and Filter -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white">
      <div class="flex-1">
        <slot name="header" />
      </div>

      <div class="flex items-center gap-3">
        <!-- Search Box -->
        <div v-if="searchable" class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="searchPlaceholder"
            class="w-80 pl-10 pr-4 py-2 border-2 border-gray-300 rounded-lg transition-base focus:outline-none focus:border-primary"
          />
        </div>

        <!-- Filter Button -->
        <div v-if="filterable" class="relative">
          <button
            ref="filterButtonRef"
            @click="toggleFilters"
            class="p-2 border-2 border-gray-300 rounded-lg hover:border-primary transition-base"
            :class="{ 'bg-primary border-primary text-white': showFilters }"
          >
            <SlidersHorizontal :size="20" />
          </button>

          <!-- Filter Dropdown Panel - Teleported to body -->
          <Teleport to="body">
            <!-- Backdrop -->
            <Transition
              enter-active-class="transition-opacity duration-200"
              enter-from-class="opacity-0"
              enter-to-class="opacity-100"
              leave-active-class="transition-opacity duration-150"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <div
                v-if="showFilters"
                class="fixed inset-0 bg-black/20 z-[9998]"
                @click="closeFilters"
              />
            </Transition>

            <!-- Dropdown Panel -->
            <Transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="opacity-0 scale-95 translate-y-2"
              enter-to-class="opacity-100 scale-100 translate-y-0"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100 scale-100 translate-y-0"
              leave-to-class="opacity-0 scale-95 translate-y-2"
            >
              <div
                v-if="showFilters"
                :style="filterDropdownStyle"
                class="fixed w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-[9999]"
                @click.stop
              >
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                  <h3 class="font-semibold text-gray-900">Filters</h3>
                  <button
                    @click="handleResetFilters()"
                    class="text-sm text-primary hover:underline"
                  >
                    Reset
                  </button>
                </div>

                <div class="p-4 space-y-4">
                  <slot name="filters" />

                  <div v-if="!$slots.filters" class="text-sm text-gray-500 text-center py-4">
                    No filters available
                  </div>
                </div>

                <div class="p-4 border-t border-gray-200 flex items-center justify-end gap-3">
                  <button
                    @click="closeFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-base"
                  >
                    Cancel
                  </button>
                  <button
                    @click="handleApplyFilters(); closeFilters()"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition-base"
                  >
                    Apply Filters
                  </button>
                </div>
              </div>
            </Transition>
          </Teleport>
        </div>
      </div>
    </div>

    <!-- Progress Bar -->
    <ProgressBar :show="isApplyingFilters" color="primary" height="4px" />

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="table-base">
        <thead class="table-header">
          <tr>
            <th v-if="selectable" class="w-12 px-4 py-3">
              <input
                type="checkbox"
                :checked="allSelected"
                @change="toggleSelectAll"
                class="checkbox-base"
              />
            </th>
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-4 py-3 text-left text-sm font-semibold text-gray-700"
              :class="column.class"
            >
              {{ column.label }}
            </th>
            <th v-if="showActions" class="w-12 px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="bg-white">
          <tr v-if="loading">
            <td :colspan="columns.length + (selectable ? 1 : 0) + (showActions ? 1 : 0)" class="table-cell text-center py-8">
              <div class="flex items-center justify-center gap-2 text-gray-500">
                <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span>Loading...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="paginatedData.length === 0">
            <td :colspan="columns.length + (selectable ? 1 : 0) + (showActions ? 1 : 0)" class="table-cell text-center py-8 text-gray-500">
              No data available
            </td>
          </tr>
          <tr
            v-else
            v-for="(row, index) in paginatedData"
            :key="index"
            class="table-row cursor-pointer"
            @click="handleRowClick(row, $event)"
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
            <td v-if="showActions" class="table-cell">
              <div class="action-menu">
                <button
                  :ref="(el) => setActionMenuRef(index, el)"
                  @click.stop="toggleActionMenu(index)"
                  class="p-1 hover:bg-gray-100 rounded transition-base"
                >
                  <MoreVertical :size="20" class="text-gray-400" />
                </button>

                <!-- Action Menu Dropdown - Teleported to body -->
                <Teleport to="body">
                  <!-- Backdrop -->
                  <Transition
                    enter-active-class="transition-opacity duration-100"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition-opacity duration-75"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                  >
                    <div
                      v-if="activeActionMenu === index"
                      class="fixed inset-0 z-[9998]"
                      @click="closeActionMenu"
                    />
                  </Transition>

                  <!-- Dropdown -->
                  <Transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                  >
                    <div
                      v-if="activeActionMenu === index"
                      :style="actionMenuStyle"
                      class="fixed w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-[9999]"
                      @click.stop
                    >
                      <slot name="actions" :row="row" :index="index" :close="closeActionMenu">
                        <div class="py-1">
                          <button
                            @click.stop="$emit('action-edit', row); closeActionMenu()"
                            class="dropdown-item w-full text-left"
                          >
                            Edit
                          </button>
                          <button
                            @click.stop="$emit('action-delete', row); closeActionMenu()"
                            class="dropdown-item w-full text-left text-error"
                          >
                            Delete
                          </button>
                        </div>
                      </slot>
                    </div>
                  </Transition>
                </Teleport>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
      <slot name="footer">
        <div class="flex items-center justify-between">
          <!-- Left: Showing Info -->
          <div class="text-sm text-gray-600">
            Showing {{ paginationInfo.start }} to {{ paginationInfo.end }} of {{ paginationInfo.total }} entries
          </div>

          <!-- Center: Items Per Page Selector -->
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <span>Show</span>
            <select
              :value="perPage"
              @change="changePerPage(Number(($event.target as HTMLSelectElement).value))"
              class="px-3 py-1 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 cursor-pointer"
            >
              <option v-for="option in itemsPerPageOptions" :key="option" :value="option">
                {{ option }}
              </option>
            </select>
            <span>entries</span>
          </div>

          <!-- Right: Pagination Controls -->
          <div class="flex items-center gap-1">
            <!-- First Page -->
            <button
              @click="goToFirstPage"
              :disabled="currentPage === 1"
              class="px-2 py-1 hover:bg-orange-50 rounded transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              title="First Page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="11 17 6 12 11 7"></polyline>
                <polyline points="18 17 13 12 18 7"></polyline>
              </svg>
            </button>

            <!-- Previous Page -->
            <button
              @click="previousPage"
              :disabled="currentPage === 1"
              class="px-2 py-1 hover:bg-orange-50 rounded transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              title="Previous Page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>

            <!-- Page Numbers -->
            <template v-for="page in paginationRange" :key="page">
              <button
                v-if="typeof page === 'number'"
                @click="goToPage(page)"
                :class="[
                  'px-3 py-1 rounded transition-all duration-200 min-w-[36px]',
                  currentPage === page
                    ? 'bg-orange-500 text-white font-semibold'
                    : 'text-gray-600 hover:bg-orange-50'
                ]"
              >
                {{ page }}
              </button>
              <span v-else class="px-2 text-gray-400">{{ page }}</span>
            </template>

            <!-- Next Page -->
            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="px-2 py-1 hover:bg-orange-50 rounded transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              title="Next Page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>

            <!-- Last Page -->
            <button
              @click="goToLastPage"
              :disabled="currentPage === totalPages"
              class="px-2 py-1 hover:bg-orange-50 rounded transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              title="Last Page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="13 17 18 12 13 7"></polyline>
                <polyline points="6 17 11 12 6 7"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </slot>
    </div>
  </div>
</template>
