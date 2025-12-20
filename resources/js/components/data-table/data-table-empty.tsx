"use client"

import { memo } from "react"
import { TableRow, TableCell } from "@/components/ui/table"
import { Loader2 } from "lucide-react"

interface DataTableEmptyProps {
  colSpan: number
  isLoading: boolean
  message: string
}

function DataTableEmptyComponent({ colSpan, isLoading, message }: DataTableEmptyProps) {
  return (
    <TableRow>
      <TableCell colSpan={colSpan} className="text-center py-8 text-muted-foreground">
        {isLoading ? (
          <div className="flex items-center justify-center">
            <Loader2 className="w-6 h-6 animate-spin mr-2" />
            Filtering data...
          </div>
        ) : (
          message
        )}
      </TableCell>
    </TableRow>
  )
}

export const DataTableEmpty = memo(DataTableEmptyComponent)
