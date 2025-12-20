import type React from "react"

export interface TableColumn<T = any> {
  id: string
  header: string
  accessorKey: string
  cell?: (value: any, row: T) => React.ReactNode
  sortable?: boolean
  filterable?: boolean
  filterType?: "text" | "number" | "date" | "select"
  filterOptions?: { label: string; value: string }[]
  width?: string | number
  minWidth?: string | number
  maxWidth?: string | number
  resizable?: boolean
  align?: "left" | "center" | "right"
  visible?: boolean
}

export interface TableData {
  id: string | number
  [key: string]: any
}

export interface SortConfig {
  key: string
  direction: "asc" | "desc"
}

export interface FilterConfig {
  key: string
  value: any
  type: "text" | "number" | "date" | "select"
  operator?: "equals" | "contains" | "gt" | "lt" | "gte" | "lte"
  label?: string
}

export interface PaginationConfig {
  page: number
  pageSize: number
  total: number
}

export interface BulkAction {
  id: string
  label: string
  icon?: React.ReactNode
  action: (selectedIds: (string | number)[]) => void | Promise<void>
  variant?: "default" | "destructive"
}

// Custom row action interface
export interface RowAction<T = any> {
  name: string
  label: string
  icon?: React.ReactNode
  action: (row: T) => void | Promise<void>
  variant?: "default" | "destructive"
  disabled?: (row: T) => boolean
  hidden?: (row: T) => boolean
}

export interface ApiParams {
  page: number
  pageSize: number
  search?: string
  sort?: SortConfig[]
  filters?: FilterConfig[]
}

export interface ApiResponse<T> {
  data: T[]
  total: number
  page: number
  pageSize: number
}

// Custom data fetcher function type
export type DataFetcher<T> = (params: ApiParams) => Promise<ApiResponse<T>>

export interface DataTableProps<T extends TableData> {
  // Either provide an API endpoint or a custom data fetcher
  apiEndpoint?: string
  dataFetcher?: DataFetcher<T>

  // For APIs without pagination, provide static data
  data?: T[]

  columns: TableColumn<T>[]
  bulkActions?: BulkAction[]
  searchable?: boolean
  exportable?: boolean
  onExport?: (data: T[]) => void
  className?: string

  // Updated row actions - can be either custom actions or render function
  rowActions?: RowAction<T>[] | ((row: T) => React.ReactNode)

  title?: string

  // Pagination settings
  enablePagination?: boolean
  defaultPageSize?: number
  pageSizeOptions?: number[]

  // Column resizing
  enableColumnResizing?: boolean
  onColumnResize?: (columnId: string, width: number) => void
}
