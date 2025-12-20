"use client"
import { Eye, Trash2, Download, Trash, Calendar, DollarSign, Users } from "lucide-react"
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

interface Booking {
    id: number
    host: string
    guest: string
    start_date: string
    end_date: string
    num_people: number
    total_price: string
    status: string
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Bookings', href: '/admin/bookings' },
]

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    cancelled: 'bg-gray-100 text-gray-800',
}

export default function Index() {
    const apiEndpoint = `/admin/api/get-bookings`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Booking>[] = useMemo(() => [
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
            id: "guest",
            header: "Guest",
            accessorKey: "guest",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-medium text-gray-900">{value || 'N/A'}</div>
            ),
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
            id: "start_date",
            header: "Check In",
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
            header: "Check Out",
            accessorKey: "end_date",
            sortable: true,
            filterable: true,
            filterType: "date",
            cell: (value) => value || 'N/A',
        },
        {
            id: "num_people",
            header: "Guests",
            accessorKey: "num_people",
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
            id: "total_price",
            header: "Total",
            accessorKey: "total_price",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-semibold text-green-600">{value}</div>
            ),
        },
        {
            id: "status",
            header: "Status",
            accessorKey: "status",
            sortable: true,
            filterable: true,
            filterType: "select",
            filterOptions: [
                { label: 'Pending', value: 'pending' },
                { label: 'Approved', value: 'approved' },
                { label: 'Rejected', value: 'rejected' },
                { label: 'Cancelled', value: 'cancelled' },
            ],
            cell: (value) => (
                <Badge className={statusColors[value] || 'bg-gray-100 text-gray-800'}>
                    {value}
                </Badge>
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

    const rowActions = (row: Booking) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.bookings.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Booking
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.bookings.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting booking:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Booking Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
