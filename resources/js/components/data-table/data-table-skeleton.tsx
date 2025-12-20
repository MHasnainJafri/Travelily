"use client"

import { memo } from "react"
import { TableRow, TableCell } from "@/components/ui/table"
import { Skeleton } from "@/components/ui/skeleton"

interface DataTableSkeletonProps {
  columnCount: number
  rowCount: number
  enableSelection?: boolean
  enableActions?: boolean
}

function DataTableSkeletonComponent({
  columnCount,
  rowCount,
  enableSelection = false,
  enableActions = false,
}: DataTableSkeletonProps) {
  return (
    <>
      {Array.from({ length: rowCount }).map((_, rowIndex) => (
        <TableRow key={`skeleton-row-${rowIndex}`}>
          {enableSelection && (
            <TableCell>
              <Skeleton className="h-4 w-4" />
            </TableCell>
          )}
          {Array.from({ length: columnCount }).map((_, colIndex) => (
            <TableCell key={`skeleton-cell-${rowIndex}-${colIndex}`}>
              <Skeleton className="h-4 w-full" />
            </TableCell>
          ))}
          {enableActions && (
            <TableCell>
              <Skeleton className="h-8 w-8 rounded-full" />
            </TableCell>
          )}
        </TableRow>
      ))}
    </>
  )
}

export const DataTableSkeleton = memo(DataTableSkeletonComponent)
