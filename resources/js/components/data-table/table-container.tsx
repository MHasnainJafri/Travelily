"use client"

import { useRef, useEffect } from "react"
import { TableHead } from "./table-head"
import { TableBody } from "./table-body"
import type { TableColumn, TableData, RowAction } from "@/lib/types"
import type React from "react"

interface TableContainerProps<T extends TableData> {
  columns: TableColumn<T>[]
  data: T[]
  enableColumnResizing?: boolean
  onColumnResize?: (columnId: string, width: number) => void
  rowActions?: RowAction<T>[] | ((row: T) => React.ReactNode)
}

export function TableContainer<T extends TableData>({
  columns,
  data,
  enableColumnResizing = false,
  onColumnResize,
  rowActions,
}: TableContainerProps<T>) {
  const headerScrollRef = useRef<HTMLDivElement>(null)
  const bodyScrollRef = useRef<HTMLDivElement>(null)

  // Synchronize horizontal scrolling between header and body
  useEffect(() => {
    const headerElement = headerScrollRef.current
    const bodyElement = bodyScrollRef.current

    if (!headerElement || !bodyElement) return

    const syncHeaderScroll = () => {
      if (bodyElement) {
        bodyElement.scrollLeft = headerElement.scrollLeft
      }
    }

    const syncBodyScroll = () => {
      if (headerElement) {
        headerElement.scrollLeft = bodyElement.scrollLeft
      }
    }

    headerElement.addEventListener("scroll", syncHeaderScroll)
    bodyElement.addEventListener("scroll", syncBodyScroll)

    return () => {
      headerElement.removeEventListener("scroll", syncHeaderScroll)
      bodyElement.removeEventListener("scroll", syncBodyScroll)
    }
  }, [])

  return (
    <div className="bg-white border border-gray-200 rounded-lg overflow-hidden">
      {/* Fixed Header */}
      <div
        ref={headerScrollRef}
        className="overflow-x-auto scrollbar-hide"
        style={{ scrollbarWidth: "none", msOverflowStyle: "none" }}
      >
        <TableHead
          columns={columns}
          data={data}
          enableColumnResizing={enableColumnResizing}
          onColumnResize={onColumnResize}
        />
      </div>

      {/* Scrollable Body */}
      <div ref={bodyScrollRef} className="overflow-auto max-h-[600px]" style={{ scrollbarWidth: "thin" }}>
        <TableBody data={data} columns={columns} rowActions={rowActions} />
      </div>
    </div>
  )
}
