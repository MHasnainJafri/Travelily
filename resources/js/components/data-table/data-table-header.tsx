"use client"

import { Search, Filter, Download, RotateCcw, Settings, Trash2, X, ChevronDown, Loader2 } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Badge } from "@/components/ui/badge"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuTrigger,
  DropdownMenuItem,
  DropdownMenuCheckboxItem,
} from "@/components/ui/dropdown-menu"
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { CardHeader, CardTitle } from "@/components/ui/card"
import { memo } from "react"
import type { Column, FilterConfig } from "@/types/data-table"

interface DataTableHeaderProps<T> {
  columns: Column<T>[]
  visibleColumns: string[]
  setVisibleColumns: (columns: string[]) => void
  filters: FilterConfig
  search: string
  isFilterLoading: boolean
  totalRecords: number
  selectedRows: T[]
  clearSelection: () => void
  handleSearch: (value: string) => void
  handleFilter: (key: string, value: any) => void
  clearFilters: () => void
  exportData: () => void
  refresh: () => void
  enableSearch?: boolean
  enableFiltering?: boolean
  enableColumnVisibility?: boolean
  enableSelection?: boolean
  searchPlaceholder?: string
}

function DataTableHeaderComponent<T>({
  columns,
  visibleColumns,
  setVisibleColumns,
  filters,
  search,
  isFilterLoading,
  totalRecords,
  selectedRows,
  clearSelection,
  handleSearch,
  handleFilter,
  clearFilters,
  exportData,
  refresh,
  enableSearch = true,
  enableFiltering = true,
  enableColumnVisibility = true,
  enableSelection = true,
  searchPlaceholder = "Search...",
}: DataTableHeaderProps<T>) {
  return (
    <CardHeader className="pb-4">
      <div className="flex flex-col gap-4">
        <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <CardTitle className="text-lg font-semibold">
            Data Table
            {totalRecords > 0 && (
              <Badge variant="secondary" className="ml-2">
                {totalRecords} records
              </Badge>
            )}
            {isFilterLoading && (
              <Badge variant="outline" className="ml-2">
                <Loader2 className="w-3 h-3 mr-1 animate-spin" />
                Filtering...
              </Badge>
            )}
          </CardTitle>
        </div>

        <div className="flex flex-wrap gap-2 items-center">
          {enableSearch && (
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
              {isFilterLoading && (
                <Loader2 className="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4 animate-spin" />
              )}
              <Input
                placeholder={searchPlaceholder}
                value={search}
                onChange={(e) => handleSearch(e.target.value)}
                className={`pl-9 w-64 ${isFilterLoading ? "pr-9" : ""}`}
                disabled={isFilterLoading}
              />
            </div>
          )}

          {enableSelection && selectedRows.length > 0 && (
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="outline" size="sm" disabled={isFilterLoading}>
                  Actions ({selectedRows.length})
                  <ChevronDown className="w-4 h-4 ml-2" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="start">
                <DropdownMenuItem
                  onClick={() => {
                    console.log("Delete selected:", selectedRows)
                    clearSelection()
                  }}
                >
                  <Trash2 className="w-4 h-4 mr-2" />
                  Delete Selected
                </DropdownMenuItem>
                <DropdownMenuItem
                  onClick={() => {
                    console.log("Export selected:", selectedRows)
                  }}
                >
                  <Download className="w-4 h-4 mr-2" />
                  Export Selected
                </DropdownMenuItem>
                <DropdownMenuItem onClick={clearSelection}>
                  <X className="w-4 h-4 mr-2" />
                  Clear Selection
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          )}

          {enableFiltering && columns.some((col) => col.filterable) && (
            <Popover>
              <PopoverTrigger asChild>
                <Button variant="outline" size="sm" type="button">
                  <Filter className="w-4 h-4 mr-2" />
                  Filters
                  {Object.keys(filters).length > 0 && (
                    <Badge variant="secondary" className="ml-2">
                      {Object.keys(filters).length}
                    </Badge>
                  )}
                </Button>
              </PopoverTrigger>
              <PopoverContent className="w-80" align="end" side="bottom">
                <div className="space-y-4">
                  <div className="flex items-center justify-between">
                    <h4 className="font-medium">Filters</h4>
                    <Button variant="ghost" size="sm" onClick={clearFilters}>
                      Clear All
                    </Button>
                  </div>
                  <div className="space-y-4">
                    {columns
                      .filter((col) => col.filterable)
                      .map((column) => (
                        <div key={column.key} className="space-y-2">
                          <label className="text-sm font-medium">{column.header}</label>
                          {column.filterType === "select" && column.filterOptions ? (
                            <Select
                              value={filters[column.key] || ""}
                              onValueChange={(value) => handleFilter(column.key, value)}
                            >
                              <SelectTrigger>
                                <SelectValue placeholder="Select..." />
                              </SelectTrigger>
                              <SelectContent>
                                {column.filterOptions.map((option) => (
                                  <SelectItem key={option.value} value={String(option.value)}>
                                    {option.label}
                                  </SelectItem>
                                ))}
                              </SelectContent>
                            </Select>
                          ) : column.filterType === "number" ? (
                            <div className="space-y-2">
                              <div className="grid grid-cols-2 gap-2">
                                <Input
                                  type="number"
                                  placeholder="Min"
                                  value={filters[`${column.key}_min`] || ""}
                                  onChange={(e) => handleFilter(`${column.key}_min`, e.target.value)}
                                />
                                <Input
                                  type="number"
                                  placeholder="Max"
                                  value={filters[`${column.key}_max`] || ""}
                                  onChange={(e) => handleFilter(`${column.key}_max`, e.target.value)}
                                />
                              </div>
                              <div className="text-xs text-muted-foreground">
                                Range filter for {column.header.toLowerCase()}
                              </div>
                            </div>
                          ) : column.filterType === "date" ? (
                            <div className="space-y-2">
                              <div className="grid grid-cols-2 gap-2">
                                <Input
                                  type="date"
                                  placeholder="From"
                                  value={filters[`${column.key}_from`] || ""}
                                  onChange={(e) => handleFilter(`${column.key}_from`, e.target.value)}
                                />
                                <Input
                                  type="date"
                                  placeholder="To"
                                  value={filters[`${column.key}_to`] || ""}
                                  onChange={(e) => handleFilter(`${column.key}_to`, e.target.value)}
                                />
                              </div>
                              <div className="text-xs text-muted-foreground">Date range filter</div>
                            </div>
                          ) : (
                            <Input
                              type={column.filterType || "text"}
                              value={filters[column.key] || ""}
                              onChange={(e) => handleFilter(column.key, e.target.value)}
                              placeholder={`Filter by ${column.header.toLowerCase()}...`}
                            />
                          )}
                        </div>
                      ))}
                  </div>
                </div>
              </PopoverContent>
            </Popover>
          )}

          {enableColumnVisibility && (
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="outline" size="sm" disabled={isFilterLoading}>
                  <Settings className="w-4 h-4 mr-2" />
                  Columns
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                {columns.map((column) => (
                  <DropdownMenuCheckboxItem
                    key={column.key}
                    checked={visibleColumns.includes(column.key)}
                    onCheckedChange={(checked) => {
                      if (checked) {
                        setVisibleColumns([...visibleColumns, column.key])
                      } else {
                        setVisibleColumns(visibleColumns.filter((key) => key !== column.key))
                      }
                    }}
                    disabled={isFilterLoading}
                  >
                    {column.header}
                  </DropdownMenuCheckboxItem>
                ))}
              </DropdownMenuContent>
            </DropdownMenu>
          )}

          <Button variant="outline" size="sm" onClick={exportData} disabled={isFilterLoading}>
            <Download className="w-4 h-4 mr-2" />
            Export
          </Button>

          <Button variant="outline" size="sm" onClick={refresh} disabled={isFilterLoading}>
            {isFilterLoading ? <Loader2 className="w-4 h-4 animate-spin" /> : <RotateCcw className="w-4 h-4" />}
          </Button>
        </div>
      </div>
    </CardHeader>
  )
}

export const DataTableHeader = memo(DataTableHeaderComponent) as typeof DataTableHeaderComponent
