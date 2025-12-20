import * as Dialog from "@radix-ui/react-dialog";
import { Trash2 } from "lucide-react";
import { useState } from "react";
import { DropdownMenuItem } from "../ui/dropdown-menu";

export default function RowAction({ row, onDelete }:any) {
  const [open, setOpen] = useState(false);

  return (
    <Dialog.Root open={open} onOpenChange={setOpen}>
      <DropdownMenuItem
        onClick={() => setOpen(true)}
        className="text-red-600 hover:bg-red-50"
      >
        <Trash2 className="h-4 w-4 mr-2" />
        Delete User
      </DropdownMenuItem>

      

      <Dialog.Portal>
        <Dialog.Overlay className="fixed inset-0 bg-black/40" />
        <Dialog.Content className="fixed left-1/2 top-1/2 w-[90vw] max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-6 shadow-lg">
          <Dialog.Title className="text-lg font-semibold text-gray-900">
            Confirm Deletion
          </Dialog.Title>
          <Dialog.Description className="text-sm text-gray-600 mt-1">
            Are you sure you want to delete this user? This action cannot be undone.
          </Dialog.Description>

          <div className="mt-4 flex justify-end gap-2">
            <button
              onClick={() => setOpen(false)}
              className="px-4 py-2 text-sm rounded bg-gray-100 hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              onClick={() => {
                // onDelete(row.id);
                setOpen(false);
              }}
              className="px-4 py-2 text-sm rounded bg-red-600 text-white hover:bg-red-700"
            >
              Delete
            </button>
          </div>
        </Dialog.Content>
      </Dialog.Portal>
    </Dialog.Root>
  );
}
