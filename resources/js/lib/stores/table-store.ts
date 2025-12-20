import { create } from "zustand"
import type { SortConfig, FilterConfig, PaginationConfig, TableColumn } from "../types"

interface TableState {
  // Data state
  loading: boolean
  error: string | null

  // Selection state
  selectedRows: Set<string | number>
  selectAll: boolean

  // Sorting state
  sortConfig: SortConfig[]

  // Filtering state
  filters: FilterConfig[]
  globalSearch: string

  // Pagination state
  pagination: PaginationConfig

  // UI state
  showFilters: boolean
  showColumns: boolean
  visibleColumns: Set<string>

  // Column widths
  columnWidths: Map<string, number>

  // Actions
  setLoading: (loading: boolean) => void
  setError: (error: string | null) => void

  toggleRowSelection: (id: string | number) => void
  toggleSelectAll: (allIds: (string | number)[]) => void
  clearSelection: () => void

  setSortConfig: (config: SortConfig[]) => void
  addSort: (key: string) => void
  removeSort: (key: string) => void

  setFilters: (filters: FilterConfig[]) => void
  addFilter: (filter: FilterConfig) => void
  removeFilter: (key: string) => void
  clearAllFilters: () => void
  setGlobalSearch: (search: string) => void

  setPagination: (pagination: Partial<PaginationConfig>) => void

  toggleFilters: () => void
  toggleColumns: () => void
  setVisibleColumns: (columns: Set<string>) => void
  toggleColumnVisibility: (columnId: string) => void
  initializeVisibleColumns: (columns: TableColumn[]) => void

  // Column width management
  setColumnWidth: (columnId: string, width: number) => void
  getColumnWidth: (columnId: string) => number | undefined
  initializeColumnWidths: (columns: TableColumn[]) => void

  reset: () => void
}

export const useTableStore = create<TableState>((set, get) => ({
  loading: false,
  error: null,
  selectedRows: new Set(),
  selectAll: false,
  sortConfig: [],
  filters: [],
  globalSearch: "",
  pagination: { page: 1, pageSize: 10, total: 0 },
  showFilters: false,
  showColumns: false,
  visibleColumns: new Set(),
  columnWidths: new Map(),

  setLoading: (loading) => set({ loading }),
  setError: (error) => set({ error }),

  toggleRowSelection: (id) => {
    const { selectedRows } = get()
    const newSelection = new Set(selectedRows)

    if (newSelection.has(id)) {
      newSelection.delete(id)
    } else {
      newSelection.add(id)
    }

    set({
      selectedRows: newSelection,
      selectAll: false,
    })
  },

  toggleSelectAll: (allIds) => {
    const { selectAll } = get()

    if (selectAll) {
      set({ selectedRows: new Set(), selectAll: false })
    } else {
      set({ selectedRows: new Set(allIds), selectAll: true })
    }
  },

  clearSelection: () => {
    set({ selectedRows: new Set(), selectAll: false })
  },

  setSortConfig: (config) => set({ sortConfig: config }),

  addSort: (key) => {
    const { sortConfig } = get()
    const existingIndex = sortConfig.findIndex((s) => s.key === key)

    if (existingIndex >= 0) {
      const newConfig = [...sortConfig]
      const existing = newConfig[existingIndex]

      if (existing.direction === "asc") {
        newConfig[existingIndex] = { ...existing, direction: "desc" }
      } else {
        newConfig.splice(existingIndex, 1)
      }

      set({ sortConfig: newConfig })
    } else {
      set({ sortConfig: [...sortConfig, { key, direction: "asc" }] })
    }
  },

  removeSort: (key) => {
    const { sortConfig } = get()
    set({ sortConfig: sortConfig.filter((s) => s.key !== key) })
  },

  setFilters: (filters) => set({ filters }),

  addFilter: (filter) => {
    const { filters } = get()
    const existingIndex = filters.findIndex((f) => f.key === filter.key)

    if (existingIndex >= 0) {
      const newFilters = [...filters]
      newFilters[existingIndex] = filter
      set({ filters: newFilters })
    } else {
      set({ filters: [...filters, filter] })
    }
  },

  removeFilter: (key) => {
    const { filters } = get()
    set({ filters: filters.filter((f) => f.key !== key) })
  },

  clearAllFilters: () => set({ filters: [] }),

  setGlobalSearch: (search) => set({ globalSearch: search }),

  setPagination: (pagination) => {
    const current = get().pagination
    set({ pagination: { ...current, ...pagination } })
  },

  toggleFilters: () => {
    const { showFilters } = get()
    set({ showFilters: !showFilters })
  },

  toggleColumns: () => {
    const { showColumns } = get()
    set({ showColumns: !showColumns })
  },

  setVisibleColumns: (columns) => set({ visibleColumns: columns }),

  toggleColumnVisibility: (columnId) => {
    const { visibleColumns } = get()
    const newVisible = new Set(visibleColumns)

    if (newVisible.has(columnId)) {
      newVisible.delete(columnId)
    } else {
      newVisible.add(columnId)
    }

    set({ visibleColumns: newVisible })
  },

  initializeVisibleColumns: (columns) => {
    const visibleColumns = new Set(columns.filter((col) => col.visible !== false).map((col) => col.id))
    set({ visibleColumns })
  },

  setColumnWidth: (columnId, width) => {
    const { columnWidths } = get()
    const newWidths = new Map(columnWidths)
    newWidths.set(columnId, width)
    set({ columnWidths: newWidths })
  },

  getColumnWidth: (columnId) => {
    const { columnWidths } = get()
    return columnWidths.get(columnId)
  },

  initializeColumnWidths: (columns) => {
    const { columnWidths } = get()
    const newWidths = new Map(columnWidths)

    columns.forEach((column) => {
      if (column.width && !newWidths.has(column.id)) {
        const width = typeof column.width === "string" ? Number.parseInt(column.width.replace("px", "")) : column.width
        newWidths.set(column.id, width)
      }
    })

    set({ columnWidths: newWidths })
  },

  reset: () => {
    set({
      selectedRows: new Set(),
      selectAll: false,
      sortConfig: [],
      filters: [],
      globalSearch: "",
      showFilters: false,
      showColumns: false,
      loading: false,
      error: null,
    })
  },
}))
