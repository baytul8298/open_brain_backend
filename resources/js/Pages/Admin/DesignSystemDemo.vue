<script setup lang="ts">
import { ref, h } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import {
  FormInput,
  SearchableDropdown,
  Checkbox,
  DatePicker,
  TextArea,
  Button,
  Badge,
  DataTable,
} from '@/Components/shared'

// Form demo data
const form = ref({
  name: '',
  email: '',
  branch: '',
  date: null,
  description: '',
  agreed: false,
})

const branches = [
  { value: 1, label: 'Forkiva West Paxtonmouth Branch' },
  { value: 2, label: 'Forkiva Connshire Branch' },
  { value: 3, label: 'Forkiva DuBuquestad Branch' },
  { value: 4, label: 'Forkiva Reingersborough Branch' },
  { value: 5, label: 'Forkiva Elmoremouth Branch' },
]

const isSubmitting = ref(false)

// Table demo data
const customers = ref([
  {
    id: 1,
    name: 'Demo 2 Customer',
    phone: '+962 7 9299 2222',
    status: 'Active',
    created_at: '2025-12-25 05:50 PM',
    updated_at: '2025-12-25 05:50 PM',
  },
  {
    id: 2,
    name: 'Demo Customer',
    phone: '+962 7 9294 4444',
    status: 'Active',
    created_at: '2025-12-25 05:49 PM',
    updated_at: '2025-12-25 05:49 PM',
  },
  {
    id: 3,
    name: 'Test Customer',
    phone: '+962 7 8111 1111',
    status: 'Inactive',
    created_at: '2025-11-07 09:13 PM',
    updated_at: '2025-11-07 09:13 PM',
  },
])

const tableColumns = [
  {
    id: 'select',
    header: ({ table }: any) => {
      return h(Checkbox, {
        checked: table.getIsAllRowsSelected(),
        indeterminate: table.getIsSomeRowsSelected(),
        'onUpdate:modelValue': table.getToggleAllRowsSelectedHandler(),
      })
    },
    cell: ({ row }: any) => {
      return h(Checkbox, {
        checked: row.getIsSelected(),
        'onUpdate:modelValue': row.getToggleSelectedHandler(),
      })
    },
    size: 50,
  },
  {
    accessorKey: 'name',
    header: 'Name',
  },
  {
    accessorKey: 'phone',
    header: 'Phone',
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: ({ row }: any) => {
      const status = row.original.status
      return h(
        Badge,
        { variant: status === 'Active' ? 'success' : 'error' },
        () => status
      )
    },
  },
  {
    accessorKey: 'created_at',
    header: 'Created at',
  },
  {
    accessorKey: 'updated_at',
    header: 'Updated at',
  },
]

async function handleSubmit() {
  isSubmitting.value = true
  // Simulate API call
  await new Promise((resolve) => setTimeout(resolve, 2000))
  console.log('Form submitted:', form.value)
  isSubmitting.value = false
}

function handleRowClick(row: any) {
  console.log('Row clicked:', row)
}

function handleRowSelect(rows: any[]) {
  console.log('Selected rows:', rows)
}
</script>

<template>
  <AppLayout title="Design System Demo">
    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Design System Demo</h1>
          <p class="mt-2 text-gray-600">
            This page demonstrates all the available components in the design system.
          </p>
        </div>

        <!-- Buttons -->
        <section class="card-base">
          <div class="card-header">
            <h2 class="text-xl font-semibold">Buttons</h2>
          </div>
          <div class="card-body">
            <div class="flex flex-wrap gap-4">
              <Button variant="primary">Primary Button</Button>
              <Button variant="secondary">Secondary Button</Button>
              <Button variant="outline">Outline Button</Button>
              <Button variant="ghost">Ghost Button</Button>
              <Button variant="danger">Danger Button</Button>
              <Button variant="primary" size="sm">Small</Button>
              <Button variant="primary" size="lg">Large</Button>
              <Button variant="primary" :loading="true">Loading</Button>
              <Button variant="primary" disabled>Disabled</Button>
            </div>
          </div>
        </section>

        <!-- Badges -->
        <section class="card-base">
          <div class="card-header">
            <h2 class="text-xl font-semibold">Badges</h2>
          </div>
          <div class="card-body">
            <div class="flex flex-wrap gap-4">
              <Badge variant="success">Success</Badge>
              <Badge variant="error">Error</Badge>
              <Badge variant="warning">Warning</Badge>
              <Badge variant="info">Info</Badge>
              <Badge variant="default">Default</Badge>
              <Badge variant="success" icon>With Icon</Badge>
              <Badge variant="success" size="sm">Small</Badge>
              <Badge variant="success" size="lg">Large</Badge>
            </div>
          </div>
        </section>

        <!-- Form Components -->
        <section class="card-base">
          <div class="card-header">
            <h2 class="text-xl font-semibold">Form Components</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Text Inputs -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormInput
                  v-model="form.name"
                  label="Name (English)"
                  placeholder="Enter your name"
                  required
                />
                <FormInput
                  v-model="form.email"
                  label="Email Address"
                  type="email"
                  placeholder="your@email.com"
                  required
                />
              </div>

              <!-- Searchable Dropdown -->
              <SearchableDropdown
                v-model="form.branch"
                label="Branch"
                :options="branches"
                placeholder="Select a branch"
                required
              />

              <!-- Date Picker -->
              <DatePicker
                v-model="form.date"
                label="Select Date"
                placeholder="Choose a date"
                required
              />

              <!-- Text Area -->
              <TextArea
                v-model="form.description"
                label="Description"
                placeholder="Enter description..."
                :max-length="500"
                :rows="4"
              />

              <!-- Checkbox -->
              <Checkbox
                v-model="form.agreed"
                label="I agree to the terms and conditions"
              />

              <!-- Submit Buttons -->
              <div class="flex gap-4">
                <Button type="submit" variant="primary" :loading="isSubmitting">
                  Submit Form
                </Button>
                <Button type="button" variant="outline">Cancel</Button>
              </div>
            </form>
          </div>
        </section>

        <!-- Data Table -->
        <section class="card-base">
          <div class="card-header">
            <div class="flex items-center justify-between">
              <h2 class="text-xl font-semibold">Data Table</h2>
              <Button variant="primary" size="sm">Add Customer</Button>
            </div>
          </div>
          <div class="card-body">
            <DataTable
              :data="customers"
              :columns="tableColumns"
              search-placeholder="Search customers..."
              @row-click="handleRowClick"
              @row-select="handleRowSelect"
            >
              <template #filters>
                <SearchableDropdown
                  v-model="form.branch"
                  label="Status"
                  :options="[
                    { value: 'active', label: 'Active' },
                    { value: 'inactive', label: 'Inactive' },
                  ]"
                />
                <DatePicker v-model="form.date" label="From" />
                <DatePicker v-model="form.date" label="To" />
              </template>
            </DataTable>
          </div>
        </section>

        <!-- CSS Utilities -->
        <section class="card-base">
          <div class="card-header">
            <h2 class="text-xl font-semibold">CSS Utility Classes</h2>
          </div>
          <div class="card-body space-y-4">
            <div>
              <h3 class="font-semibold mb-2">Colors</h3>
              <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2">
                  <div class="w-12 h-12 bg-primary rounded"></div>
                  <span class="text-sm">Primary</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-12 h-12 bg-green-500 rounded"></div>
                  <span class="text-sm">Success</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-12 h-12 bg-red-500 rounded"></div>
                  <span class="text-sm">Error</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-12 h-12 bg-yellow-500 rounded"></div>
                  <span class="text-sm">Warning</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="w-12 h-12 bg-blue-500 rounded"></div>
                  <span class="text-sm">Info</span>
                </div>
              </div>
            </div>

            <div>
              <h3 class="font-semibold mb-2">Text Styles</h3>
              <div class="space-y-2">
                <p class="text-primary">Primary colored text</p>
                <p class="text-error">Error colored text</p>
                <p class="text-gray-600">Secondary text</p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
