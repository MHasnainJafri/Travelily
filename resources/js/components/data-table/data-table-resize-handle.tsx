"use client"

import type React from "react"

import { useState, useRef, useEffect } from "react"
import { cn } from "@/lib/utils"

interface DataTableResizeHandleProps {
  onResize: (delta: number) => void
  onResizeStart?: () => void
  onResizeEnd?: () => void
  className?: string
}

export function DataTableResizeHandle({ onResize, onResizeStart, onResizeEnd, className }: DataTableResizeHandleProps) {
  const [isResizing, setIsResizing] = useState(false)
  const startXRef = useRef<number>(0)
  const lastDeltaRef = useRef<number>(0)

  // Handle mouse events for resizing
  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      if (!isResizing) return

      const delta = e.clientX - startXRef.current
      const deltaDiff = delta - lastDeltaRef.current

      if (deltaDiff !== 0) {
        onResize(deltaDiff)
        lastDeltaRef.current = delta
      }
    }

    const handleMouseUp = () => {
      if (isResizing) {
        setIsResizing(false)
        onResizeEnd?.()
        document.body.style.cursor = ""
        document.body.style.userSelect = ""
      }
    }

    if (isResizing) {
      document.addEventListener("mousemove", handleMouseMove)
      document.addEventListener("mouseup", handleMouseUp)
    }

    return () => {
      document.removeEventListener("mousemove", handleMouseMove)
      document.removeEventListener("mouseup", handleMouseUp)
    }
  }, [isResizing, onResize, onResizeEnd])

  const handleMouseDown = (e: React.MouseEvent<HTMLDivElement>) => {
    e.preventDefault()
    startXRef.current = e.clientX
    lastDeltaRef.current = 0
    setIsResizing(true)
    onResizeStart?.()

    // Change cursor and disable text selection during resize
    document.body.style.cursor = "col-resize"
    document.body.style.userSelect = "none"
  }

  return (
    <div
      className={cn(
        "absolute right-0 top-0 h-full w-1.5 cursor-col-resize select-none touch-none opacity-0 hover:bg-primary/50 hover:opacity-100 group-hover:opacity-100",
        isResizing && "bg-primary opacity-100",
        className,
      )}
      onMouseDown={handleMouseDown}
      onClick={(e) => e.stopPropagation()}
    />
  )
}
