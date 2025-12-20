"use client"

import { memo, useState } from "react"
import { MoreVertical, Eye, Edit, Trash2 } from "lucide-react"
import { Button } from "@/components/ui/button"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"
import { DeleteConfirmationModal } from "@/components/data-table/delete-confirmation-modal"

interface DataTableRowActionsProps<T> {
  row: T
  onView?: (row: T) => void
  onEdit?: (row: T) => void
  onDelete?: (row: T) => Promise<void>
}

function DataTableRowActionsComponent<T>({ row, onView, onEdit, onDelete }: DataTableRowActionsProps<T>) {
  const [deleteModalOpen, setDeleteModalOpen] = useState(false)
  const [isDeleting, setIsDeleting] = useState(false)

  const handleDeleteClick = () => {
    setDeleteModalOpen(true)
  }

  const handleDeleteConfirm = async () => {
    if (!onDelete) return

    setIsDeleting(true)
    try {
      await onDelete(row)
    } catch (error) {
      console.error("Error deleting record:", error)
    } finally {
      setIsDeleting(false)
      setDeleteModalOpen(false)
    }
  }

  return (
    <>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm" className="h-8 w-8 p-0">
            <span className="sr-only">Open menu</span>
            <MoreVertical className="h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          {onView && (
            <DropdownMenuItem onClick={() => onView(row)}>
              <Eye className="mr-2 h-4 w-4" />
              View
            </DropdownMenuItem>
          )}
          {onEdit && (
            <DropdownMenuItem onClick={() => onEdit(row)}>
              <Edit className="mr-2 h-4 w-4" />
              Edit
            </DropdownMenuItem>
          )}
          {onDelete && (
            <DropdownMenuItem onClick={handleDeleteClick} className="text-red-600 focus:text-red-600">
              <Trash2 className="w-4 h-4 mr-2" />
              Delete
            </DropdownMenuItem>
          )}
        </DropdownMenuContent>
      </DropdownMenu>

      {onDelete && (
        <DeleteConfirmationModal
          open={deleteModalOpen}
          onOpenChange={setDeleteModalOpen}
          onConfirm={handleDeleteConfirm}
          isLoading={isDeleting}
          title="Delete Record"
          description="Are you sure you want to delete this record? This action cannot be undone and will permanently remove the data from the system."
        />
      )}
    </>
  )
}

export const DataTableRowActions = memo(DataTableRowActionsComponent) as typeof DataTableRowActionsComponent
