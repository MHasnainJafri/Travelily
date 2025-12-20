import { Head, usePage } from '@inertiajs/react';
import React from 'react'
import Image from "@/components/Image"
import { Star, MapPin, Calendar, Phone, Mail, MoreHorizontal, Play } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { UserDetail } from '@/types/User';
import UserReview from './partials/UserReview';
const breadcrumbs: BreadcrumbItem[] = [
{
title: 'Dashboard',
href: '/dashboard',
},
];
const View = () => {
        const { props } = usePage();
    const user = props.user as UserDetail | undefined;
    if (!user) {
      return <div className="text-center py-20 text-gray-500">User not found.</div>;
    }
    console.log(user)
  return ( <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Dashboard" />
         <div className="min-h-screen bg-gray-50 p-6">
      <div className="max-w-7xl mx-auto space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">User Details</h1>
            <p className="text-gray-600">Manage user profile and activity</p>
          </div>
          <div className="flex gap-3">
            <Button variant="outline">Edit User</Button>
            <Button variant="destructive">Suspend Account</Button>
          </div>
        </div>

        {/* User Profile Card */}
        <Card>
          <CardContent className="p-6">
            <div className="flex items-start gap-6">
              <div className="relative">
                <Avatar className="w-24 h-24">
                  <AvatarImage src={user.profile_photo} alt="Alex" />
                  <AvatarFallback className="text-2xl">AL</AvatarFallback>
                </Avatar>
                <div className="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
              </div>

              <div className="flex-1">
                <div className="flex items-center gap-3 mb-2">
                  <h2 className="text-2xl font-bold">{user.name}</h2>
                  <Badge variant="secondary">{user.verified?'Verified':'UnVerified'}</Badge>
                  <Badge className="bg-purple-100 text-purple-800">{user.roles[0].name}</Badge>
                </div>

                <div className="flex items-center gap-1 mb-3">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star key={star} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                  ))}
                  <span className="text-sm text-gray-600 ml-1">{user?.profile?.rating} ({user.written_reviews_count} reviews)</span>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                  <div className="text-center p-3 bg-gray-50 rounded-lg">
                    <div className="text-2xl font-bold text-gray-900">{user?.profile?.followers_count}</div>
                    <div className="text-sm text-gray-600">Followers</div>
                  </div>
                  <div className="text-center p-3 bg-gray-50 rounded-lg">
                    <div className="text-2xl font-bold text-gray-900">{user.friends_of_mine_count}</div>
                    <div className="text-sm text-gray-600">Following</div>
                  </div>
                  <div className="text-center p-3 bg-gray-50 rounded-lg">
                    <div className="text-2xl font-bold text-gray-900">{user.listings_count}</div>
                    <div className="text-sm text-gray-600">Posts</div>
                  </div>
                </div>

                <div className="flex flex-wrap gap-4 text-sm text-gray-600">
                  <div className="flex items-center gap-1">
                    <Mail className="w-4 h-4" />
                    {user.email}
                  </div>
                  {/* <div className="flex items-center gap-1">
                    <Phone className="w-4 h-4" />
                    +1 (555) 123-4567
                  </div> */}
                  <div className="flex items-center gap-1">
                    <Calendar className="w-4 h-4" />
                    {user.created_at}
                  </div>
                  {/* <div className="flex items-center gap-1">
                    <MapPin className="w-4 h-4" />
                    San Francisco, CA
                  </div> */}
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Tabs Section */}
        <Tabs defaultValue="overview" className="space-y-6">
          <TabsList className="grid w-full grid-cols-6">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="listings">Listings ({user.listings_count})</TabsTrigger>
            <TabsTrigger value="reviews">Reviews ({user.written_reviews_count})</TabsTrigger>
            <TabsTrigger value="history">History</TabsTrigger>
            <TabsTrigger value="activity">Activity</TabsTrigger>
            <TabsTrigger value="settings">Settings</TabsTrigger>
          </TabsList>

          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Bio Section */}
              <Card>
                <CardHeader>
                  <CardTitle>About</CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="text-gray-700 leading-relaxed">
                    {user?.profile?.bio || "No bio available. This user has not provided any information about themselves."}
                  </p>
                </CardContent>
              </Card>

              {/* Short Video */}
              <Card>
                <CardHeader>
                  <CardTitle>Introduction Video</CardTitle>
                </CardHeader>
                <CardContent>
                    {
                        user?.profile?.short_video?(
                            <div className="flex items-center justify-center mb-4">
                              <video
                                src={user?.profile?.short_video}
                                controls
                                className="w-full h-auto rounded-lg"
                              />
                            </div>
                        ):(
                            <p className="text-gray-500">No introduction video available.</p>
                        )
                    }
                  {/* <div className="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                    <Image
                      src="/placeholder.svg?height=200&width=300"
                      alt="User introduction video"
                      fill
                      className="object-cover"
                    />
                    <div className="absolute inset-0 flex items-center justify-center">
                      <Button size="lg" className="rounded-full w-16 h-16">
                        <Play className="w-6 h-6 ml-1" />
                      </Button>
                    </div>
                    <div className="absolute bottom-2 right-2 bg-black/70 text-white px-2 py-1 rounded text-sm">
                      2:34
                    </div>
                  </div> */}
                </CardContent>
              </Card>
            </div>

            {/* Recent Activity */}
            {/* <Card>
              <CardHeader>
                <CardTitle>Recent Activity</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span className="text-sm">New listing "Luxury Seaside Villa" published</span>
                    <span className="text-xs text-gray-500 ml-auto">2 hours ago</span>
                  </div>
                  <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span className="text-sm">Received 5-star review from guest</span>
                    <span className="text-xs text-gray-500 ml-auto">1 day ago</span>
                  </div>
                  <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <div className="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <span className="text-sm">Profile updated with new photos</span>
                    <span className="text-xs text-gray-500 ml-auto">3 days ago</span>
                  </div>
                </div>
              </CardContent>
            </Card> */}
          </TabsContent>

          <TabsContent value="listings" className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {
                  user.listings && user.listings.length > 0 ? (
                    user.listings.map((listing) => (
                      <Card key={listing.id} className="overflow-hidden">
                        <div className="relative aspect-video">
                          <Image
                            src={listing.media[0]?.original_url || "/placeholder.svg?height=200&width=300"}
                            alt={listing.title}
                            fill
                            className="object-cover"
                          />
                          <div className="absolute top-2 left-2 bg-white px-2 py-1 rounded text-sm font-medium">
                            {listing.featured ? "Featured" : "Active"}
                          </div>
                        </div>
                        <CardContent className="p-4">
                          <h3 className="font-semibold mb-1">{listing.title}</h3>
                          {/* <div className="flex items-center gap-1 mb-2">
                            {[1, 2, 3, 4, 5].map((star) => (
                              <Star key={star} className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                            ))}
                            <span className="text-xs text-gray-600">{listing.rating}</span>
                          </div> */}
                          <p className="text-sm text-gray-600 mb-2">{listing.description}</p>
                          <div className="flex justify-between items-center">
                            <span className="font-bold">${listing.price}/night</span>
                            <Badge variant={listing.status ? "outline" : "secondary"}>
                              {listing.status ? "Active" : "Inactive"}
                            </Badge>
                          </div>
                        </CardContent>
                      </Card>
                    ))
                  ) : (
                    <div className="col-span-full text-center text-gray-500">No listings available.</div>
                  )

                }
             
            </div>
          </TabsContent>

          <TabsContent value="reviews" className="space-y-6">
            {/* <div className="flex justify-between items-center">
              <div className="flex gap-2">
                <Button variant="outline" size="sm">
                  Newest
                </Button>
                <Button variant="outline" size="sm">
                  Top Rated
                </Button>
                <Button variant="outline" size="sm">
                  Positive
                </Button>
                <Button variant="outline" size="sm">
                  Critical
                </Button>
              </div>
            </div> */}

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div className="bg-gray-50 rounded-lg p-4">
              <h3 className="font-semibold mb-2">Review Summary</h3>
              <div className="flex items-center gap-2 mb-2">
                <span className="text-3xl font-bold text-yellow-500">{user?.profile?.rating ?? 0}</span>
                <div className="flex gap-1">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star key={star} className={`w-5 h-5 ${user?.profile?.rating >= star ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'}`} />
                  ))}
                </div>
              </div>
              <div className="text-sm text-gray-600">{user.written_reviews_count} reviews</div>
            </div>
            <div className="bg-gray-50 rounded-lg p-4">
              <h3 className="font-semibold mb-2">Most Recent Review</h3>
              {user.written_reviews && user.written_reviews.length > 0 ? (
                <div>
                  <div className="flex items-center gap-2 mb-1">
                    <Avatar className="w-8 h-8">
                      <AvatarImage src={user.written_reviews[0]?.reviewed_user?.profile_photo} alt={user.written_reviews[0]?.reviewed_user?.name} />
                      <AvatarFallback>{user.written_reviews[0]?.reviewed_user?.name?.charAt(0)}</AvatarFallback>
                    </Avatar>
                    <span className="font-medium">{user.written_reviews[0]?.reviewed_user?.name}</span>
                    <span className="text-xs text-gray-500 ml-auto">{new Date(user.written_reviews[0]?.created_at).toLocaleDateString()}</span>
                  </div>
                  <p className="text-sm text-gray-700">{user.written_reviews[0]?.comment}</p>
                </div>
              ) : (
                <div className="text-gray-500">No recent reviews.</div>
              )}
            </div>
          </div>
          <hr />

<div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div className="bg-gray-50 rounded-lg p-4">
              <h3 className="font-semibold mb-2">Given Reviews</h3>
               <div className="space-y-4">

                {
                  user.received_reviews && user.received_reviews.length > 0 ? (
                    user.received_reviews.map((review) => (
                     <UserReview review={review}  />
                    ))
                  ) : (
                    <div className="text-center text-gray-500">No reviews available.</div>
                  )
                }

             
            </div>
              </div>

              <div>
                              <h3 className="font-semibold mb-2">Recieved Reviews</h3>

 <div className="space-y-4">

                {
                  user.written_reviews && user.written_reviews.length > 0 ? (
                    user.written_reviews.map((review) => (
                     <UserReview review={review}  />
                    ))
                  ) : (
                    <div className="text-center text-gray-500">No reviews available.</div>
                  )
                }

              <Card>
                <CardContent className="p-4">
                  <div className="flex items-start gap-3">
                    <Avatar className="w-10 h-10">
                      <AvatarImage src="/placeholder.svg?height=40&width=40" alt="Jaylan" />
                      <AvatarFallback>JL</AvatarFallback>
                    </Avatar>
                    <div className="flex-1">
                      <div className="flex items-center justify-between mb-1">
                        <h4 className="font-semibold">Jaylan Liphsitz</h4>
                        <Button variant="ghost" size="sm">
                          <MoreHorizontal className="w-4 h-4" />
                        </Button>
                      </div>
                      <div className="flex items-center gap-1 mb-2">
                        {[1, 2, 3, 4, 5].map((star) => (
                          <Star key={star} className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                        ))}
                        <span className="text-xs text-gray-600 ml-1">2 days ago</span>
                      </div>
                      <p className="text-sm text-gray-700">
                        Beautiful Place! Nice Views, Great for Exploring With Excellent
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card>
                <CardContent className="p-4">
                  <div className="flex items-start gap-3">
                    <Avatar className="w-10 h-10">
                      <AvatarImage src="/placeholder.svg?height=40&width=40" alt="Sukhvir" />
                      <AvatarFallback>SB</AvatarFallback>
                    </Avatar>
                    <div className="flex-1">
                      <div className="flex items-center justify-between mb-1">
                        <h4 className="font-semibold">Sukhvir Bomer</h4>
                        <Button variant="ghost" size="sm">
                          <MoreHorizontal className="w-4 h-4" />
                        </Button>
                      </div>
                      <div className="flex items-center gap-1 mb-2">
                        {[1, 2, 3, 4, 5].map((star) => (
                          <Star key={star} className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                        ))}
                        <span className="text-xs text-gray-600 ml-1">1 week ago</span>
                      </div>
                      <p className="text-sm text-gray-700">
                        Amazing stay! Very Comfortable Down And Across Established Places!
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card>
                <CardContent className="p-4">
                  <div className="flex items-start gap-3">
                    <Avatar className="w-10 h-10">
                      <AvatarImage src="/placeholder.svg?height=40&width=40" alt="Maria" />
                      <AvatarFallback>MG</AvatarFallback>
                    </Avatar>
                    <div className="flex-1">
                      <div className="flex items-center justify-between mb-1">
                        <h4 className="font-semibold">Maria Garcia</h4>
                        <Button variant="ghost" size="sm">
                          <MoreHorizontal className="w-4 h-4" />
                        </Button>
                      </div>
                      <div className="flex items-center gap-1 mb-2">
                        {[1, 2, 3, 4, 5].map((star) => (
                          <Star key={star} className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                        ))}
                        <span className="text-xs text-gray-600 ml-1">2 weeks ago</span>
                      </div>
                      <p className="text-sm text-gray-700">
                        Exceptional host and beautiful property. Alex was very responsive and helpful throughout our
                        stay. Highly recommended!
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>


              </div>

</div>




           
          </TabsContent>

          <TabsContent value="history" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Hosting History */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center justify-between">
                    <span>Hosting History</span>
                    <Badge variant="secondary">156 bookings</Badge>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-green-900">Le Lagore</h4>
                        <p className="text-sm text-green-700">Guest: Sarah Johnson</p>
                        <p className="text-xs text-green-600">Dec 15-22, 2024 • 7 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-green-900">$3,150</div>
                        <Badge className="bg-green-100 text-green-800">Completed</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-blue-900">Luxury Seaside</h4>
                        <p className="text-sm text-blue-700">Guest: Michael Chen</p>
                        <p className="text-xs text-blue-600">Jan 5-12, 2025 • 7 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-blue-900">$4,550</div>
                        <Badge className="bg-blue-100 text-blue-800">Upcoming</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-gray-900">Urban Retreat</h4>
                        <p className="text-sm text-gray-700">Guest: Emma Wilson</p>
                        <p className="text-xs text-gray-600">Nov 28 - Dec 2, 2024 • 4 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-gray-900">$1,120</div>
                        <Badge variant="outline">Completed</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-red-900">Le Lagore</h4>
                        <p className="text-sm text-red-700">Guest: David Martinez</p>
                        <p className="text-xs text-red-600">Oct 10-15, 2024 • 5 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-red-900">$0</div>
                        <Badge className="bg-red-100 text-red-800">Cancelled</Badge>
                      </div>
                    </div>

                    <Button variant="outline" className="w-full">
                      View All Hosting History
                    </Button>
                  </div>
                </CardContent>
              </Card>

              {/* Booking History */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center justify-between">
                    <span>Booking History</span>
                    <Badge variant="secondary">23 trips</Badge>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="flex items-center justify-between p-4 bg-purple-50 border border-purple-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-purple-900">Mountain Cabin Retreat</h4>
                        <p className="text-sm text-purple-700">Host: Jennifer Adams</p>
                        <p className="text-xs text-purple-600">Dec 28, 2024 - Jan 2, 2025 • 5 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-purple-900">$1,875</div>
                        <Badge className="bg-purple-100 text-purple-800">Upcoming</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-green-900">Beachfront Villa</h4>
                        <p className="text-sm text-green-700">Host: Robert Kim</p>
                        <p className="text-xs text-green-600">Sep 15-20, 2024 • 5 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-green-900">$2,250</div>
                        <Badge className="bg-green-100 text-green-800">Completed</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-gray-900">City Loft</h4>
                        <p className="text-sm text-gray-700">Host: Lisa Thompson</p>
                        <p className="text-xs text-gray-600">Aug 3-7, 2024 • 4 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-gray-900">$960</div>
                        <Badge variant="outline">Completed</Badge>
                      </div>
                    </div>

                    <div className="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                      <div className="flex-1">
                        <h4 className="font-semibold text-yellow-900">Desert Oasis</h4>
                        <p className="text-sm text-yellow-700">Host: Carlos Rodriguez</p>
                        <p className="text-xs text-yellow-600">Jul 12-16, 2024 • 4 nights</p>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-yellow-900">$1,200</div>
                        <Badge className="bg-yellow-100 text-yellow-800">Reviewed</Badge>
                      </div>
                    </div>

                    <Button variant="outline" className="w-full">
                      View All Booking History
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </div>

            {/* Revenue Summary */}
            <Card>
              <CardHeader>
                <CardTitle>Revenue & Spending Summary</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                  <div className="text-center p-4 bg-green-50 rounded-lg">
                    <div className="text-2xl font-bold text-green-900">$47,250</div>
                    <div className="text-sm text-green-700">Total Hosting Revenue</div>
                    <div className="text-xs text-green-600">This Year</div>
                  </div>
                  <div className="text-center p-4 bg-blue-50 rounded-lg">
                    <div className="text-2xl font-bold text-blue-900">$8,450</div>
                    <div className="text-sm text-blue-700">Total Booking Spend</div>
                    <div className="text-xs text-blue-600">This Year</div>
                  </div>
                  <div className="text-center p-4 bg-purple-50 rounded-lg">
                    <div className="text-2xl font-bold text-purple-900">$38,800</div>
                    <div className="text-sm text-purple-700">Net Revenue</div>
                    <div className="text-xs text-purple-600">After Expenses</div>
                  </div>
                  <div className="text-center p-4 bg-orange-50 rounded-lg">
                    <div className="text-2xl font-bold text-orange-900">94%</div>
                    <div className="text-sm text-orange-700">Occupancy Rate</div>
                    <div className="text-xs text-orange-600">Average</div>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Recent Transactions */}
            <Card>
              <CardHeader>
                <CardTitle>Recent Transactions</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  <div className="flex items-center justify-between p-3 border rounded-lg">
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                      <div>
                        <div className="font-medium">Hosting Payment Received</div>
                        <div className="text-sm text-gray-600">Le Lagore - Sarah Johnson</div>
                      </div>
                    </div>
                    <div className="text-right">
                      <div className="font-bold text-green-600">+$3,150</div>
                      <div className="text-xs text-gray-500">Dec 22, 2024</div>
                    </div>
                  </div>

                  <div className="flex items-center justify-between p-3 border rounded-lg">
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                      <div>
                        <div className="font-medium">Booking Payment</div>
                        <div className="text-sm text-gray-600">Mountain Cabin Retreat</div>
                      </div>
                    </div>
                    <div className="text-right">
                      <div className="font-bold text-red-600">-$1,875</div>
                      <div className="text-xs text-gray-500">Dec 20, 2024</div>
                    </div>
                  </div>

                  <div className="flex items-center justify-between p-3 border rounded-lg">
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                      <div>
                        <div className="font-medium">Service Fee</div>
                        <div className="text-sm text-gray-600">Platform commission</div>
                      </div>
                    </div>
                    <div className="text-right">
                      <div className="font-bold text-blue-600">-$315</div>
                      <div className="text-xs text-gray-500">Dec 22, 2024</div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="activity">
            <Card>
              <CardHeader>
                <CardTitle>User Activity Log</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="text-center text-gray-500 py-8">Activity tracking coming soon...</div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="settings">
            <Card>
              <CardHeader>
                <CardTitle>Account Settings</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="text-center text-gray-500 py-8">Settings management coming soon...</div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
    </AppLayout>

  )
}

export default View
