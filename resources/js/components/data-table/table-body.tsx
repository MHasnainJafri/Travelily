"use client"

import type React from "react"

import { memo } from "react"
import { Checkbox } from "@/components/ui/checkbox"
import { Button } from "@/components/ui/button"
import { MoreHorizontal, Eye, Edit, Trash2 } from "lucide-react"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"
import type { TableColumn, TableData, RowAction } from "@/lib/types"
import { useTableStore } from "@/lib/stores/table-store"
import { cn } from "@/lib/utils"

interface TableBodyProps<T extends TableData> {
  data: T[]
  columns: TableColumn<T>[]
  rowActions?: RowAction<T>[] | ((row: T) => React.ReactNode)
}

export const TableBody = memo(function TableBody<T extends TableData>({
  data,
  columns,
  rowActions,
}: TableBodyProps<T>) {
  const { selectedRows, toggleRowSelection, visibleColumns, getColumnWidth } = useTableStore()

  const visibleColumnsList = columns.filter((col) => visibleColumns.has(col.id))

  const renderCell = (column: TableColumn<T>, row: T) => {
    const value = row[column.accessorKey]

    if (column.cell) {
      return column.cell(value, row)
    }

    return value?.toString() || ""
  }

  const getColumnStyle = (column: TableColumn<T>) => {
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

  // Default row actions
  const defaultRowActions: RowAction<T>[] = [
    {
      name: "view",
      label: "View Details",
      icon: <Eye className="h-4 w-4" />,
      action: (row) => console.log("View", row.id),
    },
    {
      name: "edit",
      label: "Edit",
      icon: <Edit className="h-4 w-4" />,
      action: (row) => console.log("Edit", row.id),
    },
    {
      name: "delete",
      label: "Delete",
      icon: <Trash2 className="h-4 w-4" />,
      action: (row) => console.log("Delete", row.id),
      variant: "destructive" as const,
    },
  ]

  const renderRowActions = (row: T) => {
    // If rowActions is a function, use it directly
    if (typeof rowActions === "function") {
      return rowActions(row)
    }

    // If rowActions is an array, render custom actions
    if (Array.isArray(rowActions)) {
      return rowActions
        .filter((action) => !action.hidden?.(row))
        .map((action) => (
          <DropdownMenuItem
            key={action.name}
            onClick={() => action.action(row)}
            disabled={action.disabled?.(row)}
            className={cn(
              "flex items-center px-3 py-2 text-sm cursor-pointer",
              action.variant === "destructive"
                ? "text-red-600 hover:bg-red-50 hover:text-red-700"
                : "text-gray-700 hover:bg-gray-50",
            )}
          >
            {action.icon && <span className="mr-3">{action.icon}</span>}
            {action.label}
          </DropdownMenuItem>
        ))
    }

    // Default actions if none provided
    return defaultRowActions.map((action) => (
      <DropdownMenuItem
        key={action.name}
        onClick={() => action.action(row)}
        className={cn(
          "flex items-center px-3 py-2 text-sm cursor-pointer",
          action.variant === "destructive"
            ? "text-red-600 hover:bg-red-50 hover:text-red-700"
            : "text-gray-700 hover:bg-gray-50",
        )}
      >
        {action.icon && <span className="mr-3">{action.icon}</span>}
        {action.label}
      </DropdownMenuItem>
    ))
  }

  if (data.length === 0) {
    return (
      <div className="p-8 sm:p-12 text-center">
        <div className="max-w-sm mx-auto">
          <div className="bg-gray-100 rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center mx-auto mb-4">
            <svg className="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
          </div>
          <h3 className="text-base sm:text-lg font-medium text-gray-900 mb-2">No data found</h3>
          <p className="text-sm text-gray-500">
            Try adjusting your search or filter criteria to find what you're looking for.
          </p>
        </div>
      </div>
    )
  }

  return (
    <div>
      {data.map((row, index) => (
        <div
          key={row.id}
          className={cn(
            "flex items-center border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors min-h-[60px]",
            selectedRows.has(row.id) && "bg-[#ca8ba0]/10 hover:bg-[#ca8ba0]/15",
          )}
        >
          {/* Selection Checkbox */}
          <div className="w-12 flex justify-center px-3 border-r border-gray-200 flex-shrink-0">
            <Checkbox
              checked={selectedRows.has(row.id)}
              onCheckedChange={() => toggleRowSelection(row.id)}
              className="border-gray-400 data-[state=checked]:bg-[#ca8ba0] data-[state=checked]:border-[#ca8ba0]"
            />
          </div>

          {/* Data Columns */}
          {visibleColumnsList.map((column, colIndex) => (
            <div
              key={column.id}
              className={cn(
                "px-3 sm:px-4 py-4 text-xs sm:text-sm text-gray-900 overflow-hidden border-r border-gray-200",
                column.align === "center" && "text-center",
                column.align === "right" && "text-right",
              )}
              style={getColumnStyle(column)}
            >
              <div className="truncate">{renderCell(column, row)}</div>
            </div>
          ))}

          {/* Row Actions */}
          <div className="w-24 flex justify-center px-3 flex-shrink-0">
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="sm" className="h-8 w-8 p-0 hover:bg-gray-200">
                  <MoreHorizontal className="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" className="w-48">
                {renderRowActions(row)}
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </div>
      ))}
    </div>
  )
})
