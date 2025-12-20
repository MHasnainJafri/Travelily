import type React from "react"
export interface Column<T = any> {
  key: string
  header: string
  accessor?: keyof T | ((row: T) => any)
  sortable?: boolean
  filterable?: boolean
  searchable?: boolean
  width?: string
  minWidth?: string
  maxWidth?: string
  resizable?: boolean
  align?: "left" | "center" | "right"
  render?: (value: any, row: T) => React.ReactNode
  filterType?: "text" | "select" | "date" | "number"
  filterOptions?: Array<{ label: string; value: any }>
}

export interface DataTableProps<T = any> {
  data?: T[]
  columns: Column<T>[]
  loading?: boolean
  error?: string | null

  // API Configuration
  apiUrl?: string
  apiParams?: Record<string, any>
  onDataFetch?: (params: FetchParams) => Promise<ApiResponse<T>>

  // Table Configuration
  pageSize?: number
  pageSizeOptions?: number[]
  searchPlaceholder?: string
  emptyMessage?: string
  tableId?: string // Added for persisting column widths

  // Features
  enableSearch?: boolean
  enableFiltering?: boolean
  enableSorting?: boolean
  enablePagination?: boolean
  enableColumnVisibility?: boolean
  enableColumnResizing?: boolean // Added for column resizing

  // Styling
  className?: string
  variant?: "default" | "bordered" | "striped"
  size?: "sm" | "md" | "lg"

  // Callbacks
  onRowClick?: (row: T) => void
  onSelectionChange?: (selectedRows: T[]) => void
  enableSelection?: boolean
  presets?: FilterPreset[]
  onPresetSave?: (preset: FilterPreset) => void
  onPresetDelete?: (presetId: string) => void
  onPresetsClear?: () => void
}

export interface FetchParams {
  page: number
  pageSize: number
  search?: string
  sortBy?: string
  sortOrder?: "asc" | "desc"
  filters?: Record<string, any>
}

export interface ApiResponse<T> {
  data: T[]
  total: number
  page: number
  pageSize: number
}

export interface SortConfig {
  key: string
  direction: "asc" | "desc"
}

export interface FilterConfig {
  [key: string]: any
}

export interface FilterPreset {
  id: string
  name: string
  filters: FilterConfig
}

export interface ColumnSizingState {
  [key: string]: number
}
