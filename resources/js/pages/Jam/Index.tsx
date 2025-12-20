"use client"
import { Eye, Edit, Trash2, Download, Trash } from "lucide-react"
import { DataTable } from "@/components/data-table/data-table"
import { DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Badge } from "@/components/ui/badge"
import { Star } from "lucide-react"
import type { TableColumn, BulkAction } from "@/lib/types"
import { useMemo } from "react"
import { BreadcrumbItem, JamBoard } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, usePage } from "@inertiajs/react"
import { Inertia } from "@inertiajs/inertia"

const breadcrumbs: BreadcrumbItem[] = [
{
title: 'Dashboard',
href: '/dashboard',
},
];


export default function Index() {
    const apiEndpoint = `/admin/api/get-jam`


const columns: TableColumn<JamBoard>[] = useMemo(
  () => [
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
      header: "Name",
      accessorKey: "name",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => <div className="font-medium text-gray-900">{value}</div>,
    },
    {
      id: "destination",
      header: "Destination",
      accessorKey: "destination",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => <div className="font-medium text-gray-900">{value}</div>,
    },
    {
      id: "start_date",
      header: "Start Date",
      accessorKey: "start_date",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => (
        <div className="font-medium text-gray-900">
          {new Date(value).toLocaleDateString()}
        </div>
      ),
    },
    {
      id: "end_date",
      header: "End Date",
      accessorKey: "end_date",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => (
        <div className="font-medium text-gray-900">
          {new Date(value).toLocaleDateString()}
        </div>
      ),
    },
    {
      id: "budget_min",
      header: "Min Budget",
      accessorKey: "budget_min",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => (
        <div className="font-medium text-gray-900">${parseFloat(value).toFixed(2)}</div>
      ),
    },
    {
      id: "budget_max",
      header: "Max Budget",
      accessorKey: "budget_max",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => (
        <div className="font-medium text-gray-900">${parseFloat(value).toFixed(2)}</div>
      ),
    },
    {
      id: "num_guests",
      header: "Guests",
      accessorKey: "num_guests",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: number) => <div className="font-medium text-gray-900">{value}</div>,
    },
    {
      id: "status",
      header: "Status",
      accessorKey: "status",
      sortable: true,
      filterable: true,
      filterType: "text",
      cell: (value: string) => (
        <div className="font-medium text-gray-900">{value}</div>
      ),
    },
  ],
  []
);

    // Define bulk actions
    const bulkActions: BulkAction[] = [
    {
    id: "export",
    label: "Export Selected",
    icon:
    <Download className="h-4 w-4" />,
    action: async (selectedIds) => {
    console.log("Exporting:", selectedIds)
    // Implement export logic
    },
    },
    {
    id: "delete",
    label: "Delete Selected",
    icon:
    <Trash className="h-4 w-4" />,
    variant: "destructive",
    action: async (selectedIds) => {
    console.log("Deleting:", selectedIds)
    // Implement delete logic
    },
    },
    ]

    // Row actions
    const rowActions = (row: JamBoard) => (
    <>
        <DropdownMenuItem onClick={() => Inertia.visit(route('admin.jamboard.view', row.id))}>
            <Eye className="h-4 w-4 mr-2" />
            View Details
        </DropdownMenuItem>
        {/* <DropdownMenuItem onClick={()=> console.log("Edit", row.id)}>
            <Edit className="h-4 w-4 mr-2" />
            Edit JamBoard
        </DropdownMenuItem>
        <DropdownMenuItem onClick={()=> console.log("Delete", row.id)} className="text-red-600 hover:bg-red-50">
            <Trash2 className="h-4 w-4 mr-2" />
            Delete JamBoard
        </DropdownMenuItem> */}
    </>
    )

    const handleExport = (exportData: JamBoard[]) => {
    console.log("Exporting data:", exportData)
    // Implement export logic (CSV, Excel, etc.)
    }

    return (
    <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

        <DataTable apiEndpoint={apiEndpoint}  columns={columns} bulkActions={bulkActions}
            rowActions={rowActions} onExport={handleExport} searchable exportable title="JamBoard Management" />
    
    </div>
    </AppLayout>
    )
    }
