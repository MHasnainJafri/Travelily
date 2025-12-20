"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from "@/components/ui/dialog"
import { useTableStore } from "@/lib/stores/table-store"
import type { TableColumn, FilterConfig } from "@/lib/types"

interface FilterModalProps {
  columns: TableColumn[]
  onApplyFilters?: () => void
}

export function FilterModal({ columns, onApplyFilters }: FilterModalProps) {
  const { filters, addFilter, removeFilter, showFilters, toggleFilters } = useTableStore()
  const [tempFilters, setTempFilters] = useState<Record<string, any>>({})

  const filterableColumns = columns.filter((col) => col.filterable)

  const handleFilterChange = (columnId: string, value: any, operator = "contains") => {
    setTempFilters((prev) => ({
      ...prev,
      [columnId]: { value, operator },
    }))
  }

  const getFilterValue = (columnId: string) => {
    return tempFilters[columnId]?.value || filters.find((f) => f.key === columnId)?.value || ""
  }

  const getFilterOperator = (columnId: string) => {
    return tempFilters[columnId]?.operator || filters.find((f) => f.key === columnId)?.operator || "contains"
  }

  const applyFilters = () => {
    // Remove existing filters for columns being updated
    Object.keys(tempFilters).forEach((columnId) => {
      removeFilter(columnId)
    })

    // Add new filters
    Object.entries(tempFilters).forEach(([columnId, filterData]) => {
      if (filterData.value !== "" && filterData.value !== undefined) {
        const column = filterableColumns.find((col) => col.id === columnId)
        if (column) {
          const filter: FilterConfig = {
            key: columnId,
            value: filterData.value,
            type: column.filterType || "text",
            operator: filterData.operator,
          }
          addFilter(filter)
        }
      }
    })

    setTempFilters({})
    toggleFilters()
    onApplyFilters?.()
  }

  const clearFilters = () => {
    setTempFilters({})
    filterableColumns.forEach((col) => removeFilter(col.id))
  }

  return (
    <Dialog open={showFilters} onOpenChange={toggleFilters}>
      <DialogContent className="max-w-4xl max-h-[85vh] overflow-y-auto mx-4">
        <DialogHeader className="pb-4 border-b border-gray-200">
          <DialogTitle className="text-lg sm:text-xl font-semibold text-gray-900">Filter Data</DialogTitle>
          <p className="text-xs sm:text-sm text-gray-600 mt-1">Apply filters to narrow down your search results</p>
        </DialogHeader>

        <div className="py-6">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {filterableColumns.map((column) => (
              <div key={column.id} className="space-y-3">
                <Label htmlFor={`filter-${column.id}`} className="text-xs sm:text-sm font-semibold text-gray-700">
                  {column.header}
                </Label>

                <div className="space-y-3">
                  {column.filterType === "number" && (
                    <Select
                      value={getFilterOperator(column.id)}
                      onValueChange={(operator) => handleFilterChange(column.id, getFilterValue(column.id), operator)}
                    >
                      <SelectTrigger className="h-10 border-gray-300 focus:border-[#ca8ba0] focus:ring-[#ca8ba0] text-sm">
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="equals">Equals</SelectItem>
                        <SelectItem value="gt">Greater than</SelectItem>
                        <SelectItem value="lt">Less than</SelectItem>
                        <SelectItem value="gte">Greater than or equal</SelectItem>
                        <SelectItem value="lte">Less than or equal</SelectItem>
                      </SelectContent>
                    </Select>
                  )}

                  {column.filterType === "select" && column.filterOptions ? (
                    <Select
                      value={getFilterValue(column.id)}
                      onValueChange={(value) => handleFilterChange(column.id, value, "equals")}
                    >
                      <SelectTrigger className="h-10 border-gray-300 focus:border-[#ca8ba0] focus:ring-[#ca8ba0] text-sm">
                        <SelectValue placeholder={`Select ${column.header}`} />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="all">All</SelectItem>
                        {column.filterOptions.map((option) => (
                          <SelectItem key={option.value} value={option.value}>
                            {option.label}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  ) : (
                    <Input
                      id={`filter-${column.id}`}
                      type={column.filterType === "number" ? "number" : "text"}
                      placeholder={`Enter ${column.header.toLowerCase()}...`}
                      value={getFilterValue(column.id)}
                      onChange={(e) => handleFilterChange(column.id, e.target.value, getFilterOperator(column.id))}
                      className="h-10 border-gray-300 focus:border-[#ca8ba0] focus:ring-[#ca8ba0] text-sm"
                    />
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>

        <DialogFooter className="pt-4 border-t border-gray-200 gap-3 flex-col sm:flex-row">
          <Button
            variant="outline"
            onClick={clearFilters}
            className="border-gray-300 hover:bg-gray-50 w-full sm:w-auto"
          >
            Clear All
          </Button>
          <Button
            variant="outline"
            onClick={toggleFilters}
            className="border-gray-300 hover:bg-gray-50 w-full sm:w-auto"
          >
            Cancel
          </Button>
          <Button onClick={applyFilters} className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90 w-full sm:w-auto">
            Apply Filters
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  )
}
