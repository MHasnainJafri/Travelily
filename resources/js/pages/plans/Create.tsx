"use client"
import { useState } from "react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { BreadcrumbItem } from "@/types"
import AppLayout from "@/layouts/app-layout"
import { Head, useForm } from "@inertiajs/react"
import { route } from 'ziggy-js'
import { ArrowLeft, Save } from "lucide-react"
import { Link } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Plans', href: '/admin/plans' },
    { title: 'Create Plan', href: '/admin/plans/create' },
]

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        stripe_price_id: '',
        price: '',
        currency: 'USD',
        description: '',
        trial_days: '0',
    })

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('admin.plans.store'))
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Plan" />
            <div className="p-6 max-w-2xl mx-auto">
                <div className="mb-6">
                    <Link href={route('admin.plans.index')}>
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="h-4 w-4 mr-2" />
                            Back to Plans
                        </Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Create New Plan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name">Plan Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Enter plan name"
                                    required
                                />
                                {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="price">Price *</Label>
                                    <Input
                                        id="price"
                                        type="number"
                                        step="0.01"
                                        value={data.price}
                                        onChange={(e) => setData('price', e.target.value)}
                                        placeholder="0.00"
                                        required
                                    />
                                    {errors.price && <p className="text-sm text-red-500">{errors.price}</p>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="currency">Currency *</Label>
                                    <Input
                                        id="currency"
                                        value={data.currency}
                                        onChange={(e) => setData('currency', e.target.value)}
                                        placeholder="USD"
                                        maxLength={3}
                                        required
                                    />
                                    {errors.currency && <p className="text-sm text-red-500">{errors.currency}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="stripe_price_id">Stripe Price ID</Label>
                                <Input
                                    id="stripe_price_id"
                                    value={data.stripe_price_id}
                                    onChange={(e) => setData('stripe_price_id', e.target.value)}
                                    placeholder="price_xxxxx"
                                />
                                {errors.stripe_price_id && <p className="text-sm text-red-500">{errors.stripe_price_id}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="trial_days">Trial Days</Label>
                                <Input
                                    id="trial_days"
                                    type="number"
                                    value={data.trial_days}
                                    onChange={(e) => setData('trial_days', e.target.value)}
                                    placeholder="0"
                                    min="0"
                                />
                                {errors.trial_days && <p className="text-sm text-red-500">{errors.trial_days}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Enter plan description"
                                    rows={4}
                                />
                                {errors.description && <p className="text-sm text-red-500">{errors.description}</p>}
                            </div>

                            <div className="flex justify-end gap-4">
                                <Link href={route('admin.plans.index')}>
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" disabled={processing} className="bg-[#ca8ba0] hover:bg-[#ca8ba0]/90">
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Creating...' : 'Create Plan'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
