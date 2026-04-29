import type { Module } from './Module'
import type { SubModule } from './SubModule'

export type UserType = 'admin' | 'manager' | 'cashier' | 'waiter' | 'user'

export interface User {
  id: number
  name: string
  username: string
  user_type: UserType
  email: string
  email_verified_at: string | null
  language: string
  contact_no: string | null
  address: string | null
  business_id: number | null
  status: boolean
  created_at: string
  updated_at: string
  roles?: Role[]
  permissions?: Permission[]
}

export interface Role {
  id: number
  name: string
  guard_name: string
  role_type: string | null
  display_name: string
  description: string | null
  permissions?: Permission[]
  permissions_count?: number
  created_at: string
  updated_at: string
}

export interface Permission {
  id: number
  name: string
  guard_name: string
  display_name: string | null
  description: string | null
  module_id: number
  sub_module: string | null
  sub_module_id: number | null
  module?: Module
  sub_module_relation?: SubModule
  created_at: string
  updated_at: string
}
