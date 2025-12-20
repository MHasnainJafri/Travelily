"use client"
import { Eye, Edit, Trash2, Download, Trash, Plus, Heart, Users } from "lucide-react"
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

interface Interest {
    id: number
    name: string
    users_count: number
    buddy_users_count: number
    advertisements_count: number
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Interests', href: '/admin/interests' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-interests`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Interest>[] = useMemo(() => [
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
            header: "Interest Name",
            accessorKey: "name",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Heart className="h-4 w-4 text-pink-500" />
                    <span className="font-medium text-gray-900">{value}</span>
                </div>
            ),
        },
        {
            id: "users_count",
            header: "Users",
            accessorKey: "users_count",
            sortable: true,
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Users className="h-4 w-4 text-gray-400" />
                    <span>{value}</span>
                </div>
            ),
        },
        {
            id: "buddy_users_count",
            header: "Buddy Users",
            accessorKey: "buddy_users_count",
            sortable: true,
            cell: (value) => (
                <Badge variant="outline">{value} buddies</Badge>
            ),
        },
        {
            id: "advertisements_count",
            header: "Ads",
            accessorKey: "advertisements_count",
            sortable: true,
            cell: (value) => (
                <Badge variant="secondary">{value} ads</Badge>
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

    const rowActions = (row: Interest) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.interests.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.interests.edit', row.id))}>
                <Edit className="h-4 w-4 mr-2" />
                Edit Interest
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Interest
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.interests.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting interest:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Interests Management" />
            <div className="p-4">
                <div className="flex justify-end mb-4">
                    <Link href={route('admin.interests.create')}>
                        <Button className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
                            <Plus className="h-4 w-4 mr-2" />
                            Add New Interest
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
                title="Interest Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
