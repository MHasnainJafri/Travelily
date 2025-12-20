"use client"
import { Eye, Edit, Trash2, Download, Trash } from "lucide-react"
import { DataTable } from "@/components/data-table/data-table"
import { DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Badge } from "@/components/ui/badge"
import { Star } from "lucide-react"
import type { TableColumn, BulkAction } from "@/lib/types"
import { useMemo } from "react"
import { BreadcrumbItem, AdvertisingCampaign } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, usePage } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
{
title: 'Dashboard',
href: '/dashboard',
},
];


export default function Index() {
const apiEndpoint = `/admin/api/get-advertising`


const columns: TableColumn<AdvertisingCampaign>[] = useMemo(
    () => [
    {
    id: 'id',
    header: 'ID',
    accessorKey: 'id',
    sortable: true,
    filterable: true,
    filterType: 'text',
    width: '80px',
    },
    {
    id: 'title',
    header: 'Title',
    accessorKey: 'title',
    sortable: true,
    filterable: true,
    filterType: 'text',
    cell: (value: string) => (
    <div className="font-medium text-gray-900">
        {value.length > 30 ? `${value.substring(0, 30)}...` : value}
    </div>
    ),
    },
    {
    id: 'duration_days',
    header: 'Duration (Days)',
    accessorKey: 'duration_days',
    sortable: true,
    filterable: true,
    filterType: 'text',
    cell: (value: number) => <div className="font-medium text-gray-900">{value}</div>,
    },
    {
    id: 'locations',
    header: 'Locations',
    accessorKey: 'locations',
    sortable: false,
    filterable: true,
    filterType: 'text',
    cell: (value: string[]) => <div className="font-medium text-gray-900">{value.join(', ')}</div>,
    },
    {
    id: 'age_ranges',
    header: 'Age Ranges',
    accessorKey: 'age_ranges',
    sortable: false,
    filterable: true,
    filterType: 'text',
    cell: (value: string[]) => <div className="font-medium text-gray-900">{value.join(', ')}</div>,
    },
    {
    id: 'genders',
    header: 'Genders',
    accessorKey: 'genders',
    sortable: false,
    filterable: true,
    filterType: 'text',
    cell: (value: string[]) => <div className="font-medium text-gray-900">{value.join(', ')}</div>,
    },
    {
    id: 'relationships',
    header: 'Relationships',
    accessorKey: 'relationships',
    sortable: false,
    filterable: true,
    filterType: 'text',
    cell: (value: string[]) => <div className="font-medium text-gray-900">{value.join(', ')}</div>,
    },
    {
    id: 'created_at',
    header: 'Created At',
    accessorKey: 'created_at',
    sortable: true,
    filterable: true,
    filterType: 'text',
    cell: (value: string) => (
    <div className="font-medium text-gray-900">
        {new Date(value).toLocaleDateString()}
    </div>
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
    const rowActions = (row: AdvertisingCampaign) => (
    <>
        <DropdownMenuItem onClick={()=> console.log("View", row.id)}>
            <Eye className="h-4 w-4 mr-2" />
            View Details
        </DropdownMenuItem>
        <DropdownMenuItem onClick={()=> console.log("Edit", row.id)}>
            <Edit className="h-4 w-4 mr-2" />
            Edit AdvertisingCampaign
        </DropdownMenuItem>
        <DropdownMenuItem onClick={()=> console.log("Delete", row.id)} className="text-red-600 hover:bg-red-50">
            <Trash2 className="h-4 w-4 mr-2" />
            Delete AdvertisingCampaign
        </DropdownMenuItem>
    </>
    )

    const handleExport = (exportData: AdvertisingCampaign[]) => {
    console.log("Exporting data:", exportData)
    // Implement export logic (CSV, Excel, etc.)
    }

    return (
    <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Dashboard" />
        <DataTable apiEndpoint={apiEndpoint} columns={columns} bulkActions={bulkActions} rowActions={rowActions}
            onExport={handleExport} searchable exportable title="AdvertisingCampaign Management" />
    </AppLayout>
    )
    }
