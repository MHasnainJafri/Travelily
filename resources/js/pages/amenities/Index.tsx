"use client"
import { Eye, Edit, Trash2, Download, Trash, Plus, Home, Building } from "lucide-react"
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

interface Amenity {
    id: number
    name: string
    listings_count: number
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Amenities', href: '/admin/amenities' },
]

export default function Index() {
    const apiEndpoint = `/admin/api/get-amenities`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Amenity>[] = useMemo(() => [
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
            header: "Amenity Name",
            accessorKey: "name",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Home className="h-4 w-4 text-blue-500" />
                    <span className="font-medium text-gray-900">{value}</span>
                </div>
            ),
        },
        {
            id: "listings_count",
            header: "Listings",
            accessorKey: "listings_count",
            sortable: true,
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Building className="h-4 w-4 text-gray-400" />
                    <Badge variant="outline">{value} listings</Badge>
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

    const rowActions = (row: Amenity) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.amenities.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.amenities.edit', row.id))}>
                <Edit className="h-4 w-4 mr-2" />
                Edit Amenity
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Amenity
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.amenities.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting amenity:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Amenities Management" />
            <div className="p-4">
                <div className="flex justify-end mb-4">
                    <Link href={route('admin.amenities.create')}>
                        <Button className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
                            <Plus className="h-4 w-4 mr-2" />
                            Add New Amenity
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
                title="Amenity Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
