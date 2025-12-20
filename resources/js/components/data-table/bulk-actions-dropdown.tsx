"use client"

import { ChevronDown } from "lucide-react"
import { Button } from "@/components/ui/button"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"
import type { BulkAction } from "@/lib/types"
import { useTableStore } from "@/lib/stores/table-store"
import { cn } from "@/lib/utils"

interface BulkActionsDropdownProps {
  actions: BulkAction[]
  selectedCount: number
  selectedIds: (string | number)[]
}

export function BulkActionsDropdown({ actions, selectedCount, selectedIds }: BulkActionsDropdownProps) {
  const { clearSelection } = useTableStore()

  const handleAction = async (action: BulkAction) => {
    await action.action(selectedIds)
    clearSelection()
  }

  if (selectedCount === 0) return null

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button
          variant="outline"
          className="h-10 px-3 sm:px-4 border-[#ca8ba0]/30 bg-[#ca8ba0]/10 text-[#ca8ba0] hover:bg-[#ca8ba0]/20 hover:border-[#ca8ba0]/40 text-sm"
        >
          <span className="hidden sm:inline">Actions</span>
          <span className="sm:hidden">Act</span>
          <span className="ml-1">({selectedCount})</span>
          <ChevronDown className="ml-2 h-4 w-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="start" className="w-48">
        {actions.map((action) => (
          <DropdownMenuItem
            key={action.id}
            onClick={() => handleAction(action)}
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
        ))}
      </DropdownMenuContent>
    </DropdownMenu>
  )
}
