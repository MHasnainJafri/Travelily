"use client"
import { Eye, Trash2, Download, Trash, CheckSquare, Calendar, Users } from "lucide-react"
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

interface Task {
    id: number
    title: string
    description: string
    status: string
    due_date: string
    jam: string
    assignees_count: number
    created_at: string
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tasks', href: '/admin/tasks' },
]

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
}

export default function Index() {
    const apiEndpoint = `/admin/api/get-tasks`
    const [deleteId, setDeleteId] = useState<number | null>(null)

    const columns: TableColumn<Task>[] = useMemo(() => [
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
            id: "title",
            header: "Title",
            accessorKey: "title",
            sortable: true,
            filterable: true,
            filterType: "text",
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <CheckSquare className="h-4 w-4 text-gray-400" />
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
            id: "status",
            header: "Status",
            accessorKey: "status",
            sortable: true,
            filterable: true,
            filterType: "select",
            filterOptions: [
                { label: 'Pending', value: 'pending' },
                { label: 'In Progress', value: 'in_progress' },
                { label: 'Completed', value: 'completed' },
                { label: 'Cancelled', value: 'cancelled' },
            ],
            cell: (value) => (
                <Badge className={statusColors[value] || 'bg-gray-100 text-gray-800'}>
                    {value?.replace('_', ' ')}
                </Badge>
            ),
        },
        {
            id: "due_date",
            header: "Due Date",
            accessorKey: "due_date",
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
            id: "jam",
            header: "Jamboard",
            accessorKey: "jam",
            sortable: true,
            filterable: true,
            filterType: "text",
        },
        {
            id: "assignees_count",
            header: "Assignees",
            accessorKey: "assignees_count",
            sortable: true,
            cell: (value) => (
                <div className="flex items-center gap-2">
                    <Users className="h-4 w-4 text-gray-400" />
                    <span>{value}</span>
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

    const rowActions = (row: Task) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.tasks.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => setDeleteId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Task
            </DropdownMenuItem>
        </>
    )

    const handleDelete = () => {
        if (deleteId) {
            Inertia.delete(route('admin.tasks.destroy', deleteId), {
                onSuccess: () => setDeleteId(null),
                onError: (error) => console.error("Error deleting task:", error)
            })
        }
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tasks Management" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                searchable
                exportable
                title="Task Management"
            />
            <DeleteModal
                deleteId={deleteId}
                setDeleteId={setDeleteId}
                handleDelete={handleDelete}
            />
        </AppLayout>
    )
}
