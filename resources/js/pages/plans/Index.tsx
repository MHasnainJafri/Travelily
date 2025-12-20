"use client"
import { Eye, Edit, Trash2, Download, Trash, Plus, DollarSign, Clock } from "lucide-react"
import { DataTable } from "@/components/data-table/data-table"
import { DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import type { TableColumn, BulkAction } from "@/lib/types"
import { useMemo, useState } from "react"
import { BreadcrumbItem } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, Link } from "@inertiajs/react"
import { Inertia } from '@inertiajs/inertia'
import { route } from 'ziggy-js'
import DeleteModal from "@/components/data-table/DeleteModal"

interface Plan {
    id: number
    name: string
    price: string
    trial_days: number
    features_count: number
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Plans', href: '/admin/plans' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-plans`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Plan>[] = useMemo(() => [
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
            header: "Plan Name",
            accessorKey: "name",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="font-medium text-gray-900">{value}</div>
            ),
        },
        {
            id: "price",
            header: "Price",
            accessorKey: "price",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-1 font-semibold text-green-600">
                    <DollarSign className="h-4 w-4" />
                    {value}
                </div>
            ),
        },
        {
            id: "trial_days",
            header: "Trial Days",
            accessorKey: "trial_days",
            sortable: true,
            filterable: true,
            filterType: "number",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Clock className="h-4 w-4 text-gray-400" />
                    <span>{value} days</span>
                </div>
            ),
        },
        {
            id: "features_count",
            header: "Features",
            accessorKey: "features_count",
            sortable: true,
            cell: (value) => (
                <Badge variant="outline">{value} features</Badge>
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

    const rowActions = (row: Plan) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.plans.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.plans.edit', row.id))}>
                <Edit className="h-4 w-4 mr-2" />
                Edit Plan
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Plan
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.plans.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting plan:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Plans Management" />
            <div className="p-4">
                <div className="flex justify-end mb-4">
                    <Link href={route('admin.plans.create')}>
                        <Button className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
                            <Plus className="h-4 w-4 mr-2" />
                            Add New Plan
                        </Button>
                    </Link>
                </div>
            </div>
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Plan Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
