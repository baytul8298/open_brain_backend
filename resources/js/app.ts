import GlobalBadge from '@/Components/shared/GlobalBadge.vue'
import { pinia } from '@/stores'
import { useAuthStore } from '@/stores/auth'
import { useTenantStore } from '@/stores/tenant'
import type { PageProps } from '@/types/inertia'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h, type DefineComponent } from 'vue'
import './bootstrap'

// Click-away directive
const clickAwayDirective = {
  mounted(el: HTMLElement, binding: any) {
    el.clickAwayEvent = function(event: Event) {
      if (!(el === event.target || el.contains(event.target as Node))) {
        binding.value(event)
      }
    }
    document.addEventListener('click', el.clickAwayEvent)
  },
  unmounted(el: HTMLElement & { clickAwayEvent?: any }) {
    if (el.clickAwayEvent) {
      document.removeEventListener('click', el.clickAwayEvent)
    }
  }
}

createInertiaApp({
    title: (title) => `${title} | Open Brain Studio`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .directive('click-away', clickAwayDirective)
            .component('GlobalBadge', GlobalBadge)

        // Initialize stores with data from Inertia
        const authStore = useAuthStore()
        const tenantStore = useTenantStore()

        const pageProps = props.initialPage.props as PageProps

        if (pageProps.auth?.user) {
            authStore.setUser(pageProps.auth.user)
        }

        if (pageProps.tenant) {
            tenantStore.setCurrentTenant(pageProps.tenant)
        }

        return app.mount(el)
    },
    progress: {
        color: '#FF8C42',
    },
})
