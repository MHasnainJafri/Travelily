"use client"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { BreadcrumbItem } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, useForm, Link } from "@inertiajs/react"
import { route } from 'ziggy-js'
import { ArrowLeft, Save } from "lucide-react"

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Amenities', href: '/admin/amenities' },
    { title: 'Create Amenity', href: '/admin/amenities/create' },
]

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
    })

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('admin.amenities.store'))
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Amenity" />
            <div className="p-6 max-w-2xl mx-auto">
                <div className="mb-6">
                    <Link href={route('admin.amenities.index')}>
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="h-4 w-4 mr-2" />
                            Back to Amenities
                        </Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Create New Amenity</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name">Amenity Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Enter amenity name (e.g., WiFi, Pool, Parking)"
                                    required
                                />
                                {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                            </div>

                            <div className="flex justify-end gap-4">
                                <Link href={route('admin.amenities.index')}>
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" disabled={processing} className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Creating...' : 'Create Amenity'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
