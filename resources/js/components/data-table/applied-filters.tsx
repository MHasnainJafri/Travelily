"use client"

import { X, Filter } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { useTableStore } from "@/lib/stores/table-store"
import type { TableColumn } from "@/lib/types"

interface AppliedFiltersProps {
  columns: TableColumn[]
}

export function AppliedFilters({ columns }: AppliedFiltersProps) {
  const { filters, removeFilter, clearAllFilters } = useTableStore()

  if (filters.length === 0) return null

  const getColumnLabel = (key: string) => {
    const column = columns.find((col) => col.accessorKey === key)
    return column?.header || key
  }

  const getFilterLabel = (filter: any) => {
    const columnLabel = getColumnLabel(filter.key)
    const operator = filter.operator || "contains"

    let operatorText = ""
    switch (operator) {
      case "equals":
        operatorText = "is"
        break
      case "contains":
        operatorText = "contains"
        break
      case "gt":
        operatorText = ">"
        break
      case "lt":
        operatorText = "<"
        break
      case "gte":
        operatorText = "≥"
        break
      case "lte":
        operatorText = "≤"
        break
      default:
        operatorText = "contains"
    }

    return `${columnLabel} ${operatorText} "${filter.value}"`
  }

  return (
    <div className="bg-[#ca8ba0]/10 border border-[#ca8ba0]/20 rounded-lg p-4">
      <div className="flex items-start sm:items-center gap-3 flex-col sm:flex-row">
        <div className="flex items-center gap-2 text-[#ca8ba0]">
          <Filter className="h-4 w-4" />
          <span className="text-sm font-medium">Active Filters:</span>
        </div>

        <div className="flex items-center gap-2 flex-wrap">
          {filters.map((filter) => (
            <Badge
              key={filter.key}
              variant="secondary"
              className="bg-white border border-[#ca8ba0]/30 text-[#ca8ba0] hover:bg-[#ca8ba0]/5 px-3 py-1 text-xs"
            >
              <span className="truncate max-w-[200px]">{getFilterLabel(filter)}</span>
              <Button
                variant="ghost"
                size="sm"
                className="h-auto p-0 ml-2 hover:bg-transparent text-[#ca8ba0] hover:text-[#ca8ba0]/80"
                onClick={() => removeFilter(filter.key)}
              >
                <X className="h-3 w-3" />
              </Button>
            </Badge>
          ))}
        </div>

        <Button
          variant="ghost"
          size="sm"
          onClick={clearAllFilters}
          className="text-[#ca8ba0] hover:text-[#ca8ba0]/80 hover:bg-[#ca8ba0]/10 text-xs px-2 py-1 h-auto whitespace-nowrap"
        >
          Clear All
        </Button>
      </div>
    </div>
  )
}
