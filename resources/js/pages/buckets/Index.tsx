"use client"
import { Eye, Trash2, Download, Trash, List, Image, User } from "lucide-react"
import { DataTable } from "@/components/data-table/data-table"
import { DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Badge } from "@/components/ui/badge"
import type { TableColumn, BulkAction } from "@/lib/types"
import { useMemo, useState } from "react"
import { BreadcrumbItem } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head } from "@inertiajs/react"
import { Inertia } from '@inertiajs/inertia'
import { route } from 'ziggy-js'
import DeleteModal from "@/components/data-table/DeleteModal"

interface BucketList {
    id: number
    name: string
    description: string
    user: string
    images_count: number
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Bucket Lists', href: '/admin/buckets' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-buckets`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<BucketList>[] = useMemo(() => [
        {
            id: "id",
            header: "ID",
            accessorKey: "id",
            sortable: true,
            filterable: true,
            filterType: "text",
            width: "80px",
        },
        {
            id: "name",
            header: "Bucket Name",
            accessorKey: "name",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <List className="h-4 w-4 text-purple-500" />
                    <span className="font-medium text-gray-900">{value}</span>
                </div>
            ),
        },
        {
            id: "description",
            header: "Description",
            accessorKey: "description",
            sortable: false,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="max-w-xs truncate text-gray-600">{value || 'N/A'}</div>
            ),
        },
        {
            id: "user",
            header: "Owner",
            accessorKey: "user",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <User className="h-4 w-4 text-gray-400" />
                    <span>{value || 'N/A'}</span>
                </div>
            ),
        },
        {
            id: "images_count",
            header: "Images",
            accessorKey: "images_count",
            sortable: true,
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Image className="h-4 w-4 text-gray-400" />
                    <Badge variant="outline">{value} images</Badge>
                </div>
            ),
        },
        {
            id: "created_at",
            header: "Created",
            accessorKey: "created_at",
            sortable: true,
        },
    ], [])

    const bulkActions: BulkAction[] = [
        {
            id: "export",
            label: "Export Selected",
            icon: <Download className="h-4 w-4" />,
            action: async (selectedIds) => {
                console.log("Exporting:", selectedIds)
            },
        },
        {
            id: "delete",
            label: "Delete Selected",
            icon: <Trash className="h-4 w-4" />,
            variant: "destructive",
            action: async (selectedIds) => {
                console.log("Deleting:", selectedIds)
            },
        },
    ]

    const rowActions = (row: BucketList) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.buckets.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Bucket List
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.buckets.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting bucket list:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bucket Lists Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Bucket List Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
