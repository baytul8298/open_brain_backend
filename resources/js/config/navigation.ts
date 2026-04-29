import type { Component } from 'vue'
import {
  LayoutDashboard,
  ShoppingCart,
  FileText,
  CreditCard,
  MessageSquare,
  Store,
  Utensils,
  Building2,
  Armchair,
  Package,
  Tag,
  Heart,
  Image,
  Printer,
  Users,
} from 'lucide-vue-next'

export interface NavigationItem {
  name: string
  href?: string
  icon?: Component
  permission?: string
  children?: NavigationItem[]
  badge?: string
}

export const navigationConfig: NavigationItem[] = [
  {
    name: 'Dashboard',
    href: '/dashboard',
    icon: LayoutDashboard,
  },
  {
    name: 'Sales',
    icon: ShoppingCart,
    children: [
      {
        name: 'Orders',
        href: '/orders',
      },
      {
        name: 'Invoices',
        href: '/invoices',
      },
      {
        name: 'Payments',
        href: '/payments',
      },
      {
        name: 'Reasons',
        href: '/reasons',
      },
    ],
  },
  {
    name: 'POS',
    href: '/pos',
    icon: Store,
  },
  {
    name: 'Menus',
    href: '/menus',
    icon: Utensils,
  },
  {
    name: 'Branches',
    href: '/branches',
    icon: Building2,
  },
  {
    name: 'Seating Plan',
    href: '/seating-plan',
    icon: Armchair,
  },
  {
    name: 'Inventory',
    icon: Package,
    children: [
      {
        name: 'Products',
        href: '/inventory/products',
      },
      {
        name: 'Categories',
        href: '/inventory/categories',
      },
      {
        name: 'Stock',
        href: '/inventory/stock',
      },
    ],
  },
  {
    name: 'Promotions',
    href: '/promotions',
    icon: Tag,
  },
  {
    name: 'Loyalty',
    href: '/loyalty',
    icon: Heart,
  },
  {
    name: 'Media',
    href: '/media',
    icon: Image,
  },
  {
    name: 'Manage Printers',
    href: '/printers',
    icon: Printer,
  },
]

export const systemNavigationConfig: NavigationItem[] = [
  {
    name: 'Users',
    icon: Users,
    children: [
      {
        name: 'All Users',
        href: '/users',
      },
      {
        name: 'Roles',
        href: '/roles',
      },
      {
        name: 'Permissions',
        href: '/permissions',
      },
      {
        name: 'Modules',
        href: '/modules',
      },
      {
        name: 'Sub-Modules',
        href: '/sub-modules',
      },
    ],
  },
]
