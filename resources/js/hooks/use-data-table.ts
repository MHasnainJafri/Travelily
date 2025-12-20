"use client"

import { useState, useEffect, useCallback, useMemo } from "react"
import type { FetchParams, ApiResponse, SortConfig, FilterConfig } from "@/types/data-table"

interface UseDataTableProps<T> {
  apiUrl?: string
  apiParams?: Record<string, any>
  onDataFetch?: (params: FetchParams) => Promise<ApiResponse<T>>
  initialData?: T[]
  pageSize?: number
}

export function useDataTable<T>({
  apiUrl,
  apiParams = {},
  onDataFetch,
  initialData = [],
  pageSize = 10,
}: UseDataTableProps<T>) {
  const [data, setData] = useState<T[]>(initialData)
  const [loading, setLoading] = useState(false)
  const [filterLoading, setFilterLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [total, setTotal] = useState(0)
  const [currentPage, setCurrentPage] = useState(1)
  const [currentPageSize, setCurrentPageSize] = useState(pageSize)
  const [search, setSearch] = useState("")
  const [sortConfig, setSortConfig] = useState<SortConfig | null>(null)
  const [filters, setFilters] = useState<FilterConfig>({})

  const fetchData = useCallback(
    async (isFilterOperation = false) => {
      if (!apiUrl && !onDataFetch) return

      if (isFilterOperation) {
        setFilterLoading(true)
      } else {
        setLoading(true)
      }
      setError(null)

      try {
        const params: FetchParams = {
          page: currentPage,
          pageSize: currentPageSize,
          search: search || undefined,
          sortBy: sortConfig?.key,
          sortOrder: sortConfig?.direction,
          filters: Object.keys(filters).length > 0 ? filters : undefined,
          ...apiParams,
        }

        let response: ApiResponse<T>

        if (onDataFetch) {
          response = await onDataFetch(params)
        } else if (apiUrl) {
          const queryParams = new URLSearchParams()
          Object.entries(params).forEach(([key, value]) => {
            if (value !== undefined && value !== null) {
              if (typeof value === "object") {
                queryParams.append(key, JSON.stringify(value))
              } else {
                queryParams.append(key, String(value))
              }
            }
          })

          const res = await fetch(`${apiUrl}?${queryParams}`)
          if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`)
          response = await res.json()
        } else {
          throw new Error("No data source provided")
        }

        setData(response.data)
        setTotal(response.total)
      } catch (err) {
        setError(err instanceof Error ? err.message : "An error occurred")
        setData([])
        setTotal(0)
      } finally {
        setLoading(false)
        setFilterLoading(false)
      }
    },
    [apiUrl, apiParams, onDataFetch, currentPage, currentPageSize, search, sortConfig, filters],
  )

  useEffect(() => {
    fetchData()
  }, [fetchData])

  const handleSort = useCallback((key: string) => {
    setSortConfig((prev) => {
      if (prev?.key === key) {
        return prev.direction === "asc" ? { key, direction: "desc" } : null
      }
      return { key, direction: "asc" }
    })
    setCurrentPage(1)
  }, [])

  const handleFilter = useCallback((key: string, value: any) => {
    setFilters((prev) => {
      const newFilters = { ...prev }
      if (value === undefined || value === null || value === "") {
        delete newFilters[key]
      } else {
        newFilters[key] = value
      }
      return newFilters
    })
    setCurrentPage(1)
  }, [])

  const handleSearch = useCallback((searchTerm: string) => {
    setSearch(searchTerm)
    setCurrentPage(1)
  }, [])

  const handlePageChange = useCallback((page: number) => {
    setCurrentPage(page)
  }, [])

  const handlePageSizeChange = useCallback((size: number) => {
    setCurrentPageSize(size)
    setCurrentPage(1)
  }, [])

  const clearFilters = useCallback(() => {
    setFilters({})
    setSearch("")
    setSortConfig(null)
    setCurrentPage(1)
  }, [])

  const refresh = useCallback(() => {
    fetchData()
  }, [fetchData])

  const totalPages = useMemo(() => Math.ceil(total / currentPageSize), [total, currentPageSize])

  return {
    data,
    loading,
    filterLoading,
    error,
    total,
    currentPage,
    currentPageSize,
    totalPages,
    search,
    sortConfig,
    filters,
    handleSort,
    handleFilter,
    handleSearch,
    handlePageChange,
    handlePageSizeChange,
    clearFilters,
    refresh,
  }
}
