"use client"

import { memo } from "react"
import { Button } from "@/components/ui/button"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"

interface DataTablePaginationProps {
  currentPage: number
  totalPages: number
  pageSize: number
  totalRecords: number
  pageSizeOptions: number[]
  isLoading: boolean
  onPageChange: (page: number) => void
  onPageSizeChange: (size: number) => void
}

function DataTablePaginationComponent({
  currentPage,
  totalPages,
  pageSize,
  totalRecords,
  pageSizeOptions,
  isLoading,
  onPageChange,
  onPageSizeChange,
}: DataTablePaginationProps) {
  if (totalPages <= 1) return null

  return (
    <div className="flex flex-col sm:flex-row items-center justify-between gap-4 mt-4">
      <div className="flex items-center gap-2">
        <span className="text-sm text-muted-foreground">Rows per page:</span>
        <Select
          value={pageSize.toString()}
          onValueChange={(value) => onPageSizeChange(Number(value))}
          disabled={isLoading}
        >
          <SelectTrigger className="w-20">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            {pageSizeOptions.map((size) => (
              <SelectItem key={size} value={size.toString()}>
                {size}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      <div className="flex items-center gap-2">
        <span className="text-sm text-muted-foreground">
          Page {currentPage} of {totalPages} ({totalRecords} total)
        </span>
      </div>

      <div className="flex items-center gap-1">
        <Button variant="outline" size="sm" onClick={() => onPageChange(1)} disabled={currentPage === 1 || isLoading}>
          First
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(currentPage - 1)}
          disabled={currentPage === 1 || isLoading}
        >
          Previous
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(currentPage + 1)}
          disabled={currentPage === totalPages || isLoading}
        >
          Next
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(totalPages)}
          disabled={currentPage === totalPages || isLoading}
        >
          Last
        </Button>
      </div>
    </div>
  )
}

export const DataTablePagination = memo(DataTablePaginationComponent)
