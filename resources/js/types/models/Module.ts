import type { SubModule } from './SubModule'

export interface Module {
  id: number
  module_name: string
  sub_modules?: SubModule[]
  created_at: string
  updated_at: string
}
