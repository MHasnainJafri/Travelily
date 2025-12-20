"use client"
import { Eye, Trash2, Download, Trash, MapPin, Calendar } from "lucide-react"
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

interface Trip {
    id: number
    jamboard_name: string
    destination: string
    start_date: string
    end_date: string
    looking_for: string
    user: string
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Trips', href: '/admin/trips' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-trips`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Trip>[] = useMemo(() => [
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
            id: "jamboard_name",
            header: "Jamboard Name",
            accessorKey: "jamboard_name",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-medium text-gray-900">{value || 'N/A'}</div>
            ),
        },
        {
            id: "destination",
            header: "Destination",
            accessorKey: "destination",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <MapPin className="h-4 w-4 text-gray-400" />
                    <span>{value || 'N/A'}</span>
                </div>
            ),
        },
        {
            id: "start_date",
            header: "Start Date",
            accessorKey: "start_date",
            sortable: true,
            filterable: true,
            filterType: "date",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Calendar className="h-4 w-4 text-gray-400" />
                    <span>{value || 'N/A'}</span>
                </div>
            ),
        },
        {
            id: "end_date",
            header: "End Date",
            accessorKey: "end_date",
            sortable: true,
            filterable: true,
            filterType: "date",
            cell: (value) => value || 'N/A',
        },
        {
            id: "looking_for",
            header: "Looking For",
            accessorKey: "looking_for",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <Badge variant="outline">{value || 'N/A'}</Badge>
            ),
        },
        {
            id: "user",
            header: "Created By",
            accessorKey: "user",
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

    const rowActions = (row: Trip) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.trips.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Trip
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.trips.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting trip:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Trips Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Trip Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
