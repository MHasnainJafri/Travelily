"use client"

import { Search, Filter, Settings, Download, RotateCcw } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { useTableStore } from "@/lib/stores/table-store"
import { BulkActionsDropdown } from "./bulk-actions-dropdown"
import type { BulkAction } from "@/lib/types"
import { useEffect, useState } from "react"

interface TableHeaderProps {
  title: string
  totalRecords: number
  searchable?: boolean
  exportable?: boolean
  bulkActions?: BulkAction[]
  onExport?: () => void
  onRefresh?: () => void
  onSearch?: (search: string) => void
}

export function TableHeader({
  title,
  totalRecords,
  searchable = true,
  exportable = true,
  bulkActions = [],
  onExport,
  onRefresh,
  onSearch,
}: TableHeaderProps) {
  const { globalSearch, setGlobalSearch, selectedRows, toggleFilters, toggleColumns, loading } = useTableStore()
  const [searchValue, setSearchValue] = useState(globalSearch)

  const selectedCount = selectedRows.size

  // Debounced search
  useEffect(() => {
    const timer = setTimeout(() => {
      if (searchValue !== globalSearch) {
        setGlobalSearch(searchValue)
        onSearch?.(searchValue)
      }
    }, 500)

    return () => clearTimeout(timer)
  }, [searchValue, globalSearch, setGlobalSearch, onSearch])

  return (
    <div className="bg-white border border-gray-200 rounded-lg shadow-sm">
      {/* Header Section */}
      <div className="px-4 sm:px-6 py-4 border-b border-gray-200">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div className="flex-1">
            <h1 className="text-xl sm:text-2xl font-semibold text-gray-900">{title}</h1>
            <p className="text-xs sm:text-sm text-gray-600 mt-1">
              {loading ? "Loading..." : `${totalRecords.toLocaleString()} records`}
            </p>
          </div>
        </div>
      </div>

      {/* Controls Section */}
      <div className="px-4 sm:px-6 py-4">
        <div className="flex flex-col space-y-4 lg:flex-row lg:items-center lg:space-y-0 lg:gap-4">
          {/* Left side - Search */}
          {searchable && (
            <div className="flex-1 max-w-full lg:max-w-md">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <Input
                  placeholder="Search records..."
                  value={searchValue}
                  onChange={(e) => setSearchValue(e.target.value)}
                  className="pl-10 h-10 border-gray-300 focus:border-[#ca8ba0] focus:ring-[#ca8ba0]"
                  disabled={loading}
                />
              </div>
            </div>
          )}

          {/* Right side - Actions */}
          <div className="flex items-center gap-2 flex-wrap">
            {/* Bulk Actions */}
            {bulkActions.length > 0 && (
              <BulkActionsDropdown
                actions={bulkActions}
                selectedCount={selectedCount}
                selectedIds={Array.from(selectedRows)}
              />
            )}

            {/* Filter Button */}
            <Button
              variant="outline"
              onClick={toggleFilters}
              disabled={loading}
              className="h-10 px-3 sm:px-4 border-gray-300 hover:bg-gray-50 text-sm"
            >
              <Filter className="h-4 w-4 sm:mr-2" />
              <span className="hidden sm:inline">Filters</span>
            </Button>

            {/* Columns Button */}
            <Button
              variant="outline"
              onClick={toggleColumns}
              disabled={loading}
              className="h-10 px-3 sm:px-4 border-gray-300 hover:bg-gray-50 text-sm"
            >
              <Settings className="h-4 w-4 sm:mr-2" />
              <span className="hidden sm:inline">Columns</span>
            </Button>

            {/* Export Button */}
            {exportable && onExport && (
              <Button
                variant="outline"
                onClick={onExport}
                disabled={loading}
                className="h-10 px-3 sm:px-4 border-gray-300 hover:bg-gray-50 text-sm"
              >
                <Download className="h-4 w-4 sm:mr-2" />
                <span className="hidden sm:inline">Export</span>
              </Button>
            )}

            {/* Refresh Button */}
            <Button
              variant="outline"
              size="icon"
              onClick={onRefresh}
              disabled={loading}
              className="h-10 w-10 border-gray-300 hover:bg-gray-50"
            >
              <RotateCcw className={`h-4 w-4 ${loading ? "animate-spin" : ""}`} />
            </Button>
          </div>
        </div>
      </div>
    </div>
  )
}
