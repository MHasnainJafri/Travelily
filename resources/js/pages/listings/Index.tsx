"use client"
import { Eye, Trash2, Download, Trash, Home, DollarSign, Users } from "lucide-react"
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

interface Listing {
    id: number
    title: string
    location: string
    price: number
    max_people: number
    num_rooms: number
    host: string
    image: string
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Listings', href: '/admin/listings' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-listings`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Listing>[] = useMemo(() => [
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
            id: "image",
            header: "Image",
            accessorKey: "image",
            sortable: false,
            filterable: false,
            cell: (value) => (
                <img 
                    src={value || "/placeholder.png"} 
                    onError={(e) => {
                        (e.currentTarget as HTMLImageElement).src = "/placeholder.png"
                        e.currentTarget.onerror = null
                    }}
                    alt="Listing"
                    className="w-16 h-16 rounded-lg object-cover"
                />
            ),
        },
        {
            id: "title",
            header: "Title",
            accessorKey: "title",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-medium text-gray-900">{value}</div>
            ),
        },
        {
            id: "location",
            header: "Location",
            accessorKey: "location",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Home className="h-4 w-4 text-gray-400" />
                    <span>{value || 'N/A'}</span>
                </div>
            ),
        },
        {
            id: "price",
            header: "Price",
            accessorKey: "price",
            sortable: true,
            filterable: true,
            filterType: "number",
            cell: (value) => (
                <div className="flex items-center gap-1 font-semibold text-green-600">
                    <DollarSign className="h-4 w-4" />
                    {value}
                </div>
            ),
        },
        {
            id: "max_people",
            header: "Max People",
            accessorKey: "max_people",
            sortable: true,
            filterable: true,
            filterType: "number",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Users className="h-4 w-4 text-gray-400" />
                    <span>{value}</span>
                </div>
            ),
        },
        {
            id: "num_rooms",
            header: "Rooms",
            accessorKey: "num_rooms",
            sortable: true,
            filterable: true,
            filterType: "number",
        },
        {
            id: "host",
            header: "Host",
            accessorKey: "host",
            sortable: true,
            filterable: true,
            filterType: "text",
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

    const rowActions = (row: Listing) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.listings.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Listing
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.listings.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting listing:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Listings Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Listing Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
