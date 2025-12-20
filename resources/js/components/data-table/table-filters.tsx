"use client"

import { X } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { useTableStore } from "@/lib/stores/table-store"
import type { TableColumn, FilterConfig } from "@/lib/types"

interface TableFiltersProps {
  columns: TableColumn[]
}

export function TableFilters({ columns }: TableFiltersProps) {
  const { filters, addFilter, removeFilter, showFilters } = useTableStore()

  if (!showFilters) return null

  const filterableColumns = columns.filter((col) => col.filterable)

  const handleFilterChange = (columnId: string, value: any, operator = "contains") => {
    const column = filterableColumns.find((col) => col.id === columnId)
    if (!column) return

    if (value === "" || value === undefined) {
      removeFilter(columnId)
      return
    }

    const filter: FilterConfig = {
      key: columnId,
      value,
      type: column.filterType || "text",
      operator,
    }

    addFilter(filter)
  }

  const getFilterValue = (columnId: string) => {
    const filter = filters.find((f) => f.key === columnId)
    return filter?.value || ""
  }

  return (
    <div className="border rounded-lg p-4 space-y-4 bg-muted/50">
      <div className="flex items-center justify-between">
        <h3 className="text-sm font-medium">Filters</h3>
        <Button variant="ghost" size="sm" onClick={() => filters.forEach((f) => removeFilter(f.key))}>
          Clear All
        </Button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filterableColumns.map((column) => (
          <div key={column.id} className="space-y-2">
            <Label htmlFor={`filter-${column.id}`} className="text-xs">
              {column.header}
            </Label>

            {column.filterType === "select" && column.filterOptions ? (
              <Select
                value={getFilterValue(column.id)}
                onValueChange={(value) => handleFilterChange(column.id, value, "equals")}
              >
                <SelectTrigger>
                  <SelectValue placeholder={`Filter ${column.header}`} />
                </SelectTrigger>
                <SelectContent>
                  {column.filterOptions.map((option) => (
                    <SelectItem key={option.value} value={option.value}>
                      {option.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            ) : (
              <div className="relative">
                <Input
                  id={`filter-${column.id}`}
                  type={column.filterType === "number" ? "number" : "text"}
                  placeholder={`Filter ${column.header}`}
                  value={getFilterValue(column.id)}
                  onChange={(e) => handleFilterChange(column.id, e.target.value)}
                />
                {getFilterValue(column.id) && (
                  <Button
                    variant="ghost"
                    size="sm"
                    className="absolute right-1 top-1/2 -translate-y-1/2 h-6 w-6 p-0"
                    onClick={() => removeFilter(column.id)}
                  >
                    <X className="h-3 w-3" />
                  </Button>
                )}
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  )
}
