"use client"
import { Eye, Edit, Trash2, Download, Trash } from "lucide-react"
import { DataTable } from "@/components/data-table/data-table"
import { DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Badge } from "@/components/ui/badge"
import { Star } from "lucide-react"
import type { TableColumn, BulkAction } from "@/lib/types"
import { useMemo } from "react"
import { BreadcrumbItem, User } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, usePage } from "@inertiajs/react"
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import { useState } from "react";
import { Dialog, DialogContent, DialogHeader, DialogFooter, DialogTitle, DialogDescription } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import DeleteModal from "@/components/data-table/DeleteModal"

const breadcrumbs: BreadcrumbItem[] = [
{
title: 'Dashboard',
href: '/dashboard',
},
];


export default function Index() {
    const { props } = usePage();

  const role = props.role || 'traveller'
    const apiEndpoint = `/admin/api/get-users/${role}`

// Define columns
const columns: TableColumn<User>[] = useMemo(
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
    id: "profile_photo",
    header: "Image",
    accessorKey: "profile_photo",
    sortable: false,
    filterable: false,
    filterType: "text",
    cell: (value) => (
    <img src={'/storage/'+value || "/placeholder.png" } onError={(e)=> {
    (e.currentTarget as HTMLImageElement).src = "/placeholder.png";
    e.currentTarget.onerror = null;
    }}
    alt="User Image"
    className="w-20 h-20 rounded-full object-cover"
    />
    ),
    }
    ,
    {
    id: "name",
    header: "Name",
    accessorKey: "name",
    sortable: true,
    filterable: true,
    filterType: "text",
    cell: (value) => <div className="font-medium text-gray-900">{value}</div>

    },
    {
    id: "email",
    header: "Email",
    accessorKey: "email",
    sortable: true,
    filterable: true,
    filterType: "text",
    cell: (value) => <div className="font-medium text-gray-900">{value}</div>

    },
    {
    id: "username",
    header: "Username",
    accessorKey: "username",
    sortable: true,
    filterable: true,
    filterType: "text",
    cell: (value) => <div className="font-medium text-gray-900">{value}</div>

    },
    ],
    [],
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
    const [deleteUserId, setDeleteUserId] = useState<number | null>(null);

    const rowActions = (row: User) => (
        <>
            <DropdownMenuItem onClick={() => Inertia.visit(route('admin.users.show', row.id))}>
                <Eye className="h-4 w-4 mr-2" />
                View Details
            </DropdownMenuItem>
            {/* <DropdownMenuItem onClick={()=> console.log("Edit", row.id)}>
                <Edit className="h-4 w-4 mr-2" />
                Edit User
            </DropdownMenuItem> */}
            <DropdownMenuItem
                onClick={() => setDeleteUserId(row.id)}
                className="text-red-600 hover:bg-red-50"
            >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete User
            </DropdownMenuItem>
        </>
    );

    const handleDeleteUser = () => {
        if (deleteUserId) {
            Inertia.delete(route('admin.users.destroy', deleteUserId), {
                onSuccess: () => {
                    setDeleteUserId(null);
                },
                onError: (error) => {
                    console.error("Error deleting user:", error);
                }
            });
        }
    };

    const handleExport = (exportData: User[]) => {
        console.log("Exporting data:", exportData)
        // Implement export logic (CSV, Excel, etc.)
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <DataTable
                apiEndpoint={apiEndpoint}
                columns={columns}
                bulkActions={bulkActions}
                rowActions={rowActions}
                onExport={handleExport}
                searchable
                exportable
                title="User Management"
            />
             <DeleteModal
            deleteId={deleteUserId}
            setDeleteId={setDeleteUserId}
            handleDelete={handleDeleteUser}
            
        />
          
        </AppLayout>
    )

       
    }
