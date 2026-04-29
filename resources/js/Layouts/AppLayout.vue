<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'
import Sidebar from './components/Sidebar/Sidebar.vue'
import Header from './components/Header/Header.vue'
import Toast from '@/Components/shared/Toast.vue'
import { useToast } from '@/composables/useToast'
import type { PageProps } from '@/types/inertia'

const page = usePage<PageProps>()
const toast = useToast()

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.success) toast.success('Success', flash.success)
    if (flash?.error) toast.error('Error', flash.error)
  },
  { immediate: true, deep: true }
)
</script>

<template>
  <div style="min-height:100vh;background:#faf6ef;display:flex;font-family:'DM Sans',sans-serif;">
    <Sidebar />
    <div style="margin-left:248px;flex:1;display:flex;flex-direction:column;min-width:0;">
      <Header />
      <main style="flex:1;">
        <slot />
      </main>
    </div>
    <Toast />
  </div>
</template>
