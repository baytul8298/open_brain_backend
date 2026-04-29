<script setup lang="ts">
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { User, KeyRound, LogOut, ChevronDown } from 'lucide-vue-next'
import type { PageProps } from '@/types/inertia'

const page = usePage<PageProps>()
const showDropdown = ref(false)

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

const logout = () => {
  router.post('/logout')
}

// Close dropdown when clicking outside
const closeDropdown = () => {
  showDropdown.value = false
}
</script>

<template>
  <div class="relative" v-click-away="closeDropdown">
    <!-- User Button -->
    <button
      @click="toggleDropdown"
      class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"
    >
      <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
        <span class="text-white font-semibold text-sm">
          {{ page.props.auth.user?.name?.charAt(0).toUpperCase() || 'A' }}
        </span>
      </div>
      <div class="text-left hidden md:block">
        <div class="text-sm font-semibold text-gray-700">
          {{ page.props.auth.user?.name || 'Admin' }}
        </div>
        <div class="text-xs text-gray-500">Admin</div>
      </div>
      <ChevronDown class="w-4 h-4 text-gray-500" />
    </button>

    <!-- Dropdown Menu -->
    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="showDropdown"
        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
      >
        <div class="px-4 py-3 border-b border-gray-100">
          <div class="text-sm font-semibold text-gray-700">
            {{ page.props.auth.user?.name || 'Admin' }}
          </div>
          <div class="text-xs text-gray-500 mt-0.5">
            {{ page.props.auth.user?.email || 'admin@forkiva.app' }}
          </div>
        </div>

        <Link
          href="/account"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
          @click="closeDropdown"
        >
          <User class="w-4 h-4" />
          <span>My Account</span>
        </Link>

        <Link
          href="/account/password"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
          @click="closeDropdown"
        >
          <KeyRound class="w-4 h-4" />
          <span>Update Password</span>
        </Link>

        <div class="border-t border-gray-100 my-1"></div>

        <button
          @click="logout"
          class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
        >
          <LogOut class="w-4 h-4" />
          <span>Logout</span>
        </button>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
/* Custom directive for click-away */
</style>
