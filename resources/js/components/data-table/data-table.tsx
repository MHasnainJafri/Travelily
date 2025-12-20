"use client"

import { useEffect, useState, useCallback, useMemo } from "react"
import { TableHeader } from "./table-header"
import { AppliedFilters } from "./applied-filters"
import { FilterModal } from "./filter-modal"
import { ColumnVisibilityModal } from "./column-visibility-modal"
import { TableContainer } from "./table-container"
import { TablePagination } from "./table-pagination"
import { useTableStore } from "@/lib/stores/table-store"
import { fetchTableData, processStaticData } from "@/lib/api"
import type { DataTableProps, TableData, ApiResponse } from "@/lib/types"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"

export function DataTable<T extends TableData>({
  apiEndpoint,
  dataFetcher,
  data: staticData,
  columns,
  bulkActions = [],
  searchable = true,
  exportable = true,
  onExport,
  className,
  rowActions,
  title = "Data Table",
  enablePagination = true,
  defaultPageSize = 10,
  pageSizeOptions = [10, 20, 30, 40, 50, 100],
  enableColumnResizing = false,
  onColumnResize,
}: DataTableProps<T>) {
  const {
    loading,
    setLoading,
    error,
    setError,
    globalSearch,
    filters,
    sortConfig,
    pagination,
    setPagination,
    initializeVisibleColumns,
    initializeColumnWidths,
    reset,
  } = useTableStore()

  const [data, setData] = useState<T[]>([])

  // Initialize visible columns and column widths
  useEffect(() => {
    initializeVisibleColumns(columns)
    initializeColumnWidths(columns)
  }, [columns, initializeVisibleColumns, initializeColumnWidths])

  // Set default page size
  useEffect(() => {
    setPagination({ pageSize: defaultPageSize })
  }, [defaultPageSize, setPagination])

  // Determine data source type
  const dataSourceType = useMemo(() => {
    if (staticData) return "static"
    if (dataFetcher) return "custom"
    if (apiEndpoint) return "api"
    return "none"
  }, [staticData, dataFetcher, apiEndpoint])

  // Fetch data function
  const fetchData = useCallback(async () => {
    if (dataSourceType === "static" && staticData) {
      // Handle static data with client-side processing
      setLoading(true)
      setError(null)

      try {
        const response = processStaticData(staticData, {
          page: enablePagination ? pagination.page : 1,
          pageSize: enablePagination ? pagination.pageSize : staticData.length,
          search: globalSearch || undefined,
          sort: sortConfig.length > 0 ? sortConfig : undefined,
          filters: filters.length > 0 ? filters : undefined,
        })

        setData(response.data)
        setPagination({
          page: response.page,
          pageSize: response.pageSize,
          total: response.total,
        })
      } catch (err) {
        setError(err instanceof Error ? err.message : "Failed to process data")
        setData([])
      } finally {
        setLoading(false)
      }
      return
    }

    if (dataSourceType === "none") {
      setError("No data source provided. Please provide either apiEndpoint, dataFetcher, or static data.")
      return
    }

    // Handle API or custom data fetcher
    setLoading(true)
    setError(null)

    try {
      let response: ApiResponse<T>

      if (dataFetcher) {
        response = await dataFetcher({
          page: enablePagination ? pagination.page : 1,
          pageSize: enablePagination ? pagination.pageSize : 1000,
          search: globalSearch || undefined,
          sort: sortConfig.length > 0 ? sortConfig : undefined,
          filters: filters.length > 0 ? filters : undefined,
        })
      } else if (apiEndpoint) {
        response = await fetchTableData(apiEndpoint, {
          page: enablePagination ? pagination.page : 1,
          pageSize: enablePagination ? pagination.pageSize : 1000,
          search: globalSearch || undefined,
          sort: sortConfig.length > 0 ? sortConfig : undefined,
          filters: filters.length > 0 ? filters : undefined,
        })
      } else {
        throw new Error("No valid data source")
      }
console.log("Fetched data:", response)
      setData(response.data)
      setPagination({
        page: response.page,
        pageSize: response.pageSize,
        total: response.total,
      })
    } catch (err) {
      setError(err instanceof Error ? err.message : "Failed to fetch data")
      setData([])
    } finally {
      setLoading(false)
    }
  }, [
    dataSourceType,
    staticData,
    dataFetcher,
    apiEndpoint,
    pagination.page,
    pagination.pageSize,
    globalSearch,
    sortConfig,
    filters,
    enablePagination,
    setLoading,
    setError,
    setPagination,
  ])

  // Initial data fetch
  useEffect(() => {
    fetchData()
  }, [fetchData])

  // Handle pagination change
  const handlePaginationChange = useCallback(
    (newPagination: any) => {
      setPagination(newPagination)
    },
    [setPagination],
  )

  // Handle search
  const handleSearch = useCallback(() => {
    if (enablePagination) {
      setPagination({ page: 1, pageSize: pagination.pageSize, total: pagination.total })
    }
  }, [enablePagination, pagination.pageSize, pagination.total, setPagination])

  // Handle filter apply
  const handleApplyFilters = useCallback(() => {
    if (enablePagination) {
      setPagination({ page: 1, pageSize: pagination.pageSize, total: pagination.total })
    }
  }, [enablePagination, pagination.pageSize, pagination.total, setPagination])

  // Handle export
  const handleExport = useCallback(() => {
    if (onExport) {
      onExport(data)
    }
  }, [data, onExport])

  // Handle refresh
  const handleRefresh = useCallback(() => {
    fetchData()
  }, [fetchData])

  // Handle column resize
  const handleColumnResize = useCallback(
    (columnId: string, width: number) => {
      onColumnResize?.(columnId, width)
    },
    [onColumnResize],
  )

  if (error) {
    return (
      <div className="bg-white border border-red-200 rounded-lg p-6 sm:p-8 mx-4">
        <div className="flex flex-col items-center justify-center space-y-4">
          <div className="bg-red-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center">
            <svg className="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div className="text-center">
            <h3 className="text-base sm:text-lg font-semibold text-gray-900 mb-2">Error Loading Data</h3>
            <p className="text-sm text-red-600 mb-4">{error}</p>
            <Button onClick={handleRefresh} className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
              Try Again
            </Button>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className={cn("space-y-4 sm:space-y-6 p-4 sm:p-6 bg-gray-50 min-h-screen", className)}>
      <div className="max-w-full mx-auto space-y-4 sm:space-y-6 overflow-hidden">
        <TableHeader
          title={title}
          totalRecords={pagination.total}
          searchable={searchable}
          exportable={exportable}
          bulkActions={bulkActions}
          onExport={handleExport}
          onRefresh={handleRefresh}
          onSearch={handleSearch}
        />

        <AppliedFilters columns={columns} />

        <FilterModal columns={columns} onApplyFilters={handleApplyFilters} />
        <ColumnVisibilityModal columns={columns} />

        {loading ? (
          <div className="bg-white border border-gray-200 rounded-lg p-8 sm:p-12">
            <div className="flex flex-col items-center justify-center space-y-4">
              <div className="animate-spin rounded-full h-8 w-8 sm:h-12 sm:w-12 border-4 border-[#ca8ba0] border-t-transparent"></div>
              <p className="text-sm sm:text-base text-gray-600 font-medium">Loading data...</p>
            </div>
          </div>
        ) : (
          <TableContainer
            columns={columns}
            data={data}
            enableColumnResizing={enableColumnResizing}
            onColumnResize={handleColumnResize}
            rowActions={rowActions}
          />
        )}

        {enablePagination && (
          <TablePagination
            pagination={pagination}
            onPaginationChange={handlePaginationChange}
            pageSizeOptions={pageSizeOptions}
          />
        )}
      </div>
    </div>
  )
}
