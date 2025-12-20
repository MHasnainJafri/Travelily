"use client"

import { Button } from "@/components/ui/button"
import { Checkbox } from "@/components/ui/checkbox"
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from "@/components/ui/dialog"
import { useTableStore } from "@/lib/stores/table-store"
import type { TableColumn } from "@/lib/types"

interface ColumnVisibilityModalProps {
  columns: TableColumn[]
}

export function ColumnVisibilityModal({ columns }: ColumnVisibilityModalProps) {
  const { visibleColumns, toggleColumnVisibility, showColumns, toggleColumns, setVisibleColumns } = useTableStore()

  const handleSelectAll = () => {
    const allColumnIds = new Set(columns.map((col) => col.id))
    setVisibleColumns(allColumnIds)
  }

  const handleDeselectAll = () => {
    setVisibleColumns(new Set())
  }

  return (
    <Dialog open={showColumns} onOpenChange={toggleColumns}>
      <DialogContent className="max-w-md mx-4">
        <DialogHeader className="pb-4 border-b border-gray-200">
          <DialogTitle className="text-lg sm:text-xl font-semibold text-gray-900">Manage Columns</DialogTitle>
          <p className="text-xs sm:text-sm text-gray-600 mt-1">Choose which columns to display in the table</p>
        </DialogHeader>

        <div className="py-6">
          <div className="flex gap-3 mb-6 flex-col sm:flex-row">
            <Button
              variant="outline"
              size="sm"
              onClick={handleSelectAll}
              className="border-gray-300 hover:bg-gray-50 w-full sm:w-auto"
            >
              Select All
            </Button>
            <Button
              variant="outline"
              size="sm"
              onClick={handleDeselectAll}
              className="border-gray-300 hover:bg-gray-50 w-full sm:w-auto"
            >
              Deselect All
            </Button>
          </div>

          <div className="space-y-4 max-h-80 overflow-y-auto">
            {columns.map((column) => (
              <div key={column.id} className="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50">
                <Checkbox
                  id={`column-${column.id}`}
                  checked={visibleColumns.has(column.id)}
                  onCheckedChange={() => toggleColumnVisibility(column.id)}
                  className="border-gray-400 data-[state=checked]:bg-[#ca8ba0] data-[state=checked]:border-[#ca8ba0]"
                />
                <label
                  htmlFor={`column-${column.id}`}
                  className="text-sm font-medium text-gray-700 cursor-pointer flex-1"
                >
                  {column.header}
                </label>
              </div>
            ))}
          </div>
        </div>

        <DialogFooter className="pt-4 border-t border-gray-200">
          <Button onClick={toggleColumns} className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90 w-full">
            Apply Changes
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  )
}
