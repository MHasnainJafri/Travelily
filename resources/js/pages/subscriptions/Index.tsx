"use client"
import { Eye, Trash2, Download, Trash, Calendar, CreditCard } from "lucide-react"
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

interface Subscription {
    id: number
    user: string
    plan: string
    stripe_status: string
    trial_ends_at: string
    ends_at: string
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Subscriptions', href: '/admin/subscriptions' },
]

const statusColors: Record<string, string> = {
    active: 'bg-green-100 text-green-800',
    trialing: 'bg-blue-100 text-blue-800',
    past_due: 'bg-yellow-100 text-yellow-800',
    canceled: 'bg-red-100 text-red-800',
    incomplete: 'bg-gray-100 text-gray-800',
}

export default function Index() {
    const apiEndpoint = `/admin/api/get-subscriptions`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Subscription>[] = useMemo(() => [
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
            id: "user",
            header: "User",
            accessorKey: "user",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-medium text-gray-900">{value || 'N/A'}</div>
            ),
        },
        {
            id: "plan",
            header: "Plan",
            accessorKey: "plan",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <CreditCard className="h-4 w-4 text-gray-400" />
                    <span>{value || 'N/A'}</span>
                </div>
            ),
        },
        {
            id: "stripe_status",
            header: "Status",
            accessorKey: "stripe_status",
            sortable: true,
            filterable: true,
            filterType: "select",
            filterOptions: [
                { label: 'Active', value: 'active' },
                { label: 'Trialing', value: 'trialing' },
                { label: 'Past Due', value: 'past_due' },
                { label: 'Canceled', value: 'canceled' },
            ],
            cell: (value) => (
                <Badge className={statusColors[value] || 'bg-gray-100 text-gray-800'}>
                    {value}
                </Badge>
            ),
        },
        {
            id: "trial_ends_at",
            header: "Trial Ends",
            accessorKey: "trial_ends_at",
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
            id: "ends_at",
            header: "Ends At",
            accessorKey: "ends_at",
            sortable: true,
            filterable: true,
            filterType: "date",
            cell: (value) => value || 'Active',
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

    const rowActions = (row: Subscription) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.subscriptions.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Subscription
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.subscriptions.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting subscription:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Subscriptions Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Subscription Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
