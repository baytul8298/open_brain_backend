import type { Module } from './Module'

export interface SubModule {
  id: number
  module_id: number
  name: string
  module?: Module
  permissions_count?: number
  created_at: string
  updated_at: string
}
