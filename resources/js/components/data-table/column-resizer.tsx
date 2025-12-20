"use client"

import type React from "react"

import { useState, useCallback } from "react"
import { useTableStore } from "@/lib/stores/table-store"

interface ColumnResizerProps {
  columnId: string
  onResize?: (columnId: string, width: number) => void
}

export function ColumnResizer({ columnId, onResize }: ColumnResizerProps) {
  const { setColumnWidth } = useTableStore()
  const [isResizing, setIsResizing] = useState(false)

  const handleMouseDown = useCallback(
    (e: React.MouseEvent) => {
      e.preventDefault()
      setIsResizing(true)

      const startX = e.clientX
      const column = e.currentTarget.closest("[data-column-id]") as HTMLElement
      const startWidth = column?.offsetWidth || 150

      const handleMouseMove = (e: MouseEvent) => {
        const diff = e.clientX - startX
        const newWidth = Math.max(50, startWidth + diff) // Minimum width of 50px

        if (column) {
          column.style.width = `${newWidth}px`
        }
      }

      const handleMouseUp = (e: MouseEvent) => {
        setIsResizing(false)
        const diff = e.clientX - startX
        const newWidth = Math.max(50, startWidth + diff)

        setColumnWidth(columnId, newWidth)
        onResize?.(columnId, newWidth)

        document.removeEventListener("mousemove", handleMouseMove)
        document.removeEventListener("mouseup", handleMouseUp)
      }

      document.addEventListener("mousemove", handleMouseMove)
      document.addEventListener("mouseup", handleMouseUp)
    },
    [columnId, setColumnWidth, onResize],
  )

  return (
    <div
      className={`absolute right-0 top-0 w-1 h-full cursor-col-resize hover:bg-[#ca8ba0] ${
        isResizing ? "bg-[#ca8ba0]" : "bg-transparent"
      }`}
      onMouseDown={handleMouseDown}
    />
  )
}
