import { Page, PageProps as InertiaPageProps } from '@inertiajs/core'
import { User } from './models/User'
import { Tenant } from './models/Tenant'

export interface NavButton {
  id: number
  submenu_id: number
  name: string
  key_value: string
  sort_order: number
  is_active: boolean
}

export interface NavSubmenu {
  id: number
  menu_id: number
  name: string
  search_key: string | null
  to: string | null
  sort_order: number
  is_active: boolean
  buttons: NavButton[]
}

export interface NavMenu {
  id: number
  parent_menu_id: number | null
  name: string
  type: 'admin' | 'teacher' | 'student'
  key: string
  icon_key: string | null
  to: string | null
  search_key: string | null
  guard: string
  sort_order: number
  is_active: boolean
  submenus: NavSubmenu[]
}

export interface NavParentMenu {
  id: number
  name: string
  type: 'admin' | 'teacher' | 'student'
  guard: string
  sort_order: number
  is_active: boolean
  menus: NavMenu[]
}

export interface PageProps extends InertiaPageProps {
  auth: {
    user: User | null
  }
  tenant: Tenant | null
  flash: {
    success?: string
    error?: string
    warning?: string
    info?: string
  }
  errors: Record<string, string>
  nav_parent_menus: NavParentMenu[]
  nav_menus: NavMenu[]
}

declare module '@inertiajs/vue3' {
  export interface PageProps extends InertiaPageProps {
    auth: {
      user: User | null
    }
    tenant: Tenant | null
    flash: {
      success?: string
      error?: string
      warning?: string
      info?: string
    }
    nav_parent_menus: NavParentMenu[]
    nav_menus: NavMenu[]
  }
}
