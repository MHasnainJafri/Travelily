"use client"

import { cn } from "@/lib/utils"
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { useTableStore } from "@/lib/stores/table-store"
import type { PaginationConfig } from "@/lib/types"

interface TablePaginationProps {
  pagination: PaginationConfig
  onPaginationChange?: (pagination: PaginationConfig) => void
  pageSizeOptions?: number[]
}

export function TablePagination({
  pagination,
  onPaginationChange,
  pageSizeOptions = [10, 20, 30, 40, 50, 100],
}: TablePaginationProps) {
  const { selectedRows } = useTableStore()

  const totalPages = Math.ceil(pagination.total / pagination.pageSize)
  const startRecord = (pagination.page - 1) * pagination.pageSize + 1
  const endRecord = Math.min(pagination.page * pagination.pageSize, pagination.total)

  const handlePageChange = (newPage: number) => {
    const newPagination = { ...pagination, page: newPage }
    onPaginationChange?.(newPagination)
  }

  const handlePageSizeChange = (newPageSize: string) => {
    const newPagination = {
      ...pagination,
      pageSize: Number.parseInt(newPageSize),
      page: 1,
    }
    onPaginationChange?.(newPagination)
  }

  // Generate page numbers to show
  const getPageNumbers = () => {
    const pages = []
    const currentPage = pagination.page

    // Always show first page
    if (currentPage > 3) {
      pages.push(1)
      if (currentPage > 4) {
        pages.push("...")
      }
    }

    // Show pages around current page
    for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
      pages.push(i)
    }

    // Always show last page
    if (currentPage < totalPages - 2) {
      if (currentPage < totalPages - 3) {
        pages.push("...")
      }
      pages.push(totalPages)
    }

    return pages
  }

  return (
    <div className="bg-white border border-gray-200 rounded-lg px-4 sm:px-6 py-4">
      <div className="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 gap-4">
        {/* Left side - Info */}
        <div className="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 text-xs sm:text-sm text-gray-600">
          {selectedRows.size > 0 && (
            <span className="font-medium text-[#ca8ba0]">
              {selectedRows.size} of {pagination.total} row(s) selected
            </span>
          )}
          <span>
            Showing {startRecord.toLocaleString()} to {endRecord.toLocaleString()} of{" "}
            {pagination.total.toLocaleString()} entries
          </span>
        </div>

        {/* Right side - Controls */}
        <div className="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:gap-4">
          {/* Rows per page */}
          <div className="flex items-center space-x-2">
            <span className="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Show</span>
            <Select value={pagination.pageSize.toString()} onValueChange={handlePageSizeChange}>
              <SelectTrigger className="h-9 w-16 sm:w-20 border-gray-300 text-xs sm:text-sm">
                <SelectValue />
              </SelectTrigger>
              <SelectContent side="top">
                {pageSizeOptions.map((pageSize) => (
                  <SelectItem key={pageSize} value={pageSize.toString()}>
                    {pageSize}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
            <span className="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">entries</span>
          </div>

          {/* Pagination controls */}
          <div className="flex items-center justify-center space-x-1 overflow-x-auto">
            {/* First page button */}
            <Button
              variant="outline"
              size="sm"
              className="h-8 w-8 sm:h-9 sm:w-9 p-0 border-gray-300 flex-shrink-0"
              onClick={() => handlePageChange(1)}
              disabled={pagination.page === 1}
            >
              <ChevronsLeft className="h-3 w-3 sm:h-4 sm:w-4" />
            </Button>

            {/* Previous page button */}
            <Button
              variant="outline"
              size="sm"
              className="h-8 w-8 sm:h-9 sm:w-9 p-0 border-gray-300 flex-shrink-0"
              onClick={() => handlePageChange(pagination.page - 1)}
              disabled={pagination.page === 1}
            >
              <ChevronLeft className="h-3 w-3 sm:h-4 sm:w-4" />
            </Button>

            {/* Page numbers */}
            <div className="flex items-center space-x-1 overflow-x-auto">
              {getPageNumbers().map((page, index) => (
                <Button
                  key={index}
                  variant={page === pagination.page ? "default" : "outline"}
                  size="sm"
                  className={cn(
                    "h-8 w-8 sm:h-9 sm:w-9 p-0 text-xs sm:text-sm flex-shrink-0",
                    page === pagination.page
                      ? "bg-[#ca8ba0] hover:bg-[#ca8ba0]/90 border-[#ca8ba0] text-white"
                      : "border-gray-300 hover:bg-gray-50",
                  )}
                  onClick={() => typeof page === "number" && handlePageChange(page)}
                  disabled={page === "..."}
                >
                  {page}
                </Button>
              ))}
            </div>

            {/* Next page button */}
            <Button
              variant="outline"
              size="sm"
              className="h-8 w-8 sm:h-9 sm:w-9 p-0 border-gray-300 flex-shrink-0"
              onClick={() => handlePageChange(pagination.page + 1)}
              disabled={pagination.page === totalPages}
            >
              <ChevronRight className="h-3 w-3 sm:h-4 sm:w-4" />
            </Button>

            {/* Last page button */}
            <Button
              variant="outline"
              size="sm"
              className="h-8 w-8 sm:h-9 sm:w-9 p-0 border-gray-300 flex-shrink-0"
              onClick={() => handlePageChange(totalPages)}
              disabled={pagination.page === totalPages}
            >
              <ChevronsRight className="h-3 w-3 sm:h-4 sm:w-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>
  )
}
