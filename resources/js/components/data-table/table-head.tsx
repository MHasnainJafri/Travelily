"use client"

import { ChevronDown, ChevronUp, ChevronsUpDown } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Checkbox } from "@/components/ui/checkbox"
import { ColumnResizer } from "./column-resizer"
import type { TableColumn, TableData } from "@/lib/types"
import { useTableStore } from "@/lib/stores/table-store"
import { cn } from "@/lib/utils"

interface TableHeadProps<T extends TableData> {
  columns: TableColumn<T>[]
  data: T[]
  enableColumnResizing?: boolean
  onColumnResize?: (columnId: string, width: number) => void
}

export function TableHead<T extends TableData>({
  columns,
  data,
  enableColumnResizing = false,
  onColumnResize,
}: TableHeadProps<T>) {
  const { selectedRows, selectAll, toggleSelectAll, sortConfig, addSort, visibleColumns, getColumnWidth } =
    useTableStore()

  const allIds = data.map((row) => row.id)
  const visibleColumnsList = columns.filter((col) => visibleColumns.has(col.id))

  const getSortIcon = (columnId: string) => {
    const sort = sortConfig.find((s) => s.key === columnId)
    if (!sort) return <ChevronsUpDown className="h-4 w-4 text-gray-400" />
    return sort.direction === "asc" ? (
      <ChevronUp className="h-4 w-4 text-[#ca8ba0]" />
    ) : (
      <ChevronDown className="h-4 w-4 text-[#ca8ba0]" />
    )
  }

  const getSortOrder = (columnId: string) => {
    const index = sortConfig.findIndex((s) => s.key === columnId)
    return index >= 0 ? index + 1 : null
  }

  const getColumnStyle = (column: TableColumn<T>, isLast: boolean) => {
    const customWidth = getColumnWidth(column.id)
    const width = customWidth || column.width

    return {
      flex: width ? `0 0 ${typeof width === "string" ? width : `${width}px`}` : "1 1 0%",
      minWidth: column.minWidth
        ? typeof column.minWidth === "string"
          ? column.minWidth
          : `${column.minWidth}px`
        : "100px",
      maxWidth: column.maxWidth
        ? typeof column.maxWidth === "string"
          ? column.maxWidth
          : `${column.maxWidth}px`
        : undefined,
    }
  }

  return (
    <div className="bg-gray-50 border-b border-gray-200">
      <div className="flex items-center min-h-[52px]">
        {/* Select All Checkbox */}
        <div className="w-12 flex justify-center px-3 border-r border-gray-200 flex-shrink-0">
          <Checkbox
            checked={selectAll}
            onCheckedChange={() => toggleSelectAll(allIds)}
            className="border-gray-400 data-[state=checked]:bg-[#ca8ba0] data-[state=checked]:border-[#ca8ba0]"
          />
        </div>

        {/* Column Headers */}
        {visibleColumnsList.map((column, index) => (
          <div
            key={column.id}
            data-column-id={column.id}
            className={cn(
              "relative px-3 sm:px-4 py-3 text-left border-r border-gray-200",
              column.align === "center" && "text-center",
              column.align === "right" && "text-right",
            )}
            style={getColumnStyle(column, index === visibleColumnsList.length - 1)}
          >
            {column.sortable ? (
              <Button
                variant="ghost"
                size="sm"
                className="h-auto p-0 font-semibold text-gray-700 hover:text-gray-900 hover:bg-transparent -ml-1 text-xs sm:text-sm"
                onClick={() => addSort(column.accessorKey)}
              >
                <span className="flex items-center gap-1 sm:gap-2">
                  <span className="truncate">{column.header}</span>
                  {getSortIcon(column.accessorKey)}
                  {getSortOrder(column.accessorKey) && (
                    <span className="text-xs bg-[#ca8ba0] text-white rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-medium">
                      {getSortOrder(column.accessorKey)}
                    </span>
                  )}
                </span>
              </Button>
            ) : (
              <span className="font-semibold text-gray-700 text-xs sm:text-sm truncate block">{column.header}</span>
            )}

            {/* Column Resizer */}
            {enableColumnResizing && column.resizable !== false && (
              <ColumnResizer columnId={column.id} onResize={onColumnResize} />
            )}
          </div>
        ))}

        {/* Actions Column Header */}
        <div className="w-24 py-3 text-center flex-shrink-0">
          <span className="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
        </div>
      </div>
    </div>
  )
}
