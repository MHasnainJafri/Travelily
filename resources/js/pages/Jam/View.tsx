import Image from '@/components/Image';
import { AccommodationDetails } from '@/components/jamboard/accommodation-details';
import { BudgetBreakdown } from '@/components/jamboard/budget-breakdown';
import { ExperienceDetails } from '@/components/jamboard/experience-details';
import { FlightDetails } from '@/components/jamboard/flight-details';
import { MemberCard } from '@/components/jamboard/member-card';
import { TaskCard } from '@/components/jamboard/task-card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react'
import { Calendar, Clock, DollarSign, Edit, MapPin, MessageCircle, Trash2, Users } from 'lucide-react';
import React, { useState } from 'react'
const breadcrumbs: BreadcrumbItem[] = [
{
title: 'Dashboard',
href: '/dashboard',
},
];


const jamboardData = {
  id: "jamboard-001",
  title: "American Wanderers",
  location: "Bogliaaco, Italy",
  dates: {
    start: "Feb 12, 2023",
    end: "Feb 16, 2023",
  },
  images: [
    "/placeholder.svg?height=256&width=800",
    "/placeholder.svg?height=256&width=800",
    "/placeholder.svg?height=256&width=800",
    "/placeholder.svg?height=256&width=800",
  ],
  stats: {
    tripmates: 5,
    totalGuests: 6,
    stayDays: 2,
    totalBudget: 1200,
    planningComplete: 85,
  },
  flight: {
    flightNumber: "EK-5266",
    airline: "Emirates Airlines",
    status: "Confirmed",
    departure: {
      time: "9:00 AM",
      date: "Feb 12",
      airport: "Dubai",
      code: "DXB",
    },
    arrival: {
      time: "2:30 PM",
      date: "Feb 12",
      airport: "Rome",
      code: "FCO",
    },
  },
  accommodation: {
    name: "Alaska Hotel",
    type: "4-star hotel",
    rating: 4,
    status: "Booked",
    address: "Rome",
    checkIn: {
      date: "Feb 12",
      time: "3:00 PM",
    },
    checkOut: {
      date: "Feb 16",
      time: "11:00 AM",
    },
    amenities: ["Swimming", "Gym", "Meals"],
  },
  experiences: [
    {
      name: "Camping Night",
      description: "Outdoor adventure experience",
      status: "Planned",
      date: "05/09/2024",
      time: "10:00 PM",
      location: "Street 24, Nikke Palace Hotel street",
      category: "Backpacking",
    },
    {
      name: "City Tour",
      description: "Guided Rome exploration",
      status: "Optional",
      date: "Feb 14",
      time: "9:00 AM",
      location: "Rome City Center",
      category: "Trekking",
    },
  ],
  budget: {
    total: 200,
    allocated: 150,
    breakdown: [
      { category: "Flight", amount: 120 },
      { category: "Accommodation", amount: 60 },
      { category: "Experiences", amount: 20 },
    ],
  },
  members: [
    {
      id: "member-1",
      name: "Jane Cooper",
      email: "jane.cooper@email.com",
      avatar: "/placeholder.svg?height=64&width=64",
      role: "Admin",
      permissions: {
        editJamboard: true,
        addTravelers: true,
        editBudget: true,
        addDestinations: true,
      },
    },
    {
      id: "member-2",
      name: "Ahmad Arcand",
      email: "ahmad.arcand@email.com",
      avatar: "/placeholder.svg?height=64&width=64",
      role: "Member",
      permissions: {
        editJamboard: true,
        addTravelers: true,
        editBudget: true,
        addDestinations: true,
      },
    },
    {
      id: "member-3",
      name: "Nolan",
      email: "nolan@email.com",
      avatar: "/placeholder.svg?height=64&width=64",
      role: "Limited",
      permissions: {
        editJamboard: true,
        addTravelers: false,
        editBudget: false,
        addDestinations: false,
      },
    },
    {
      id: "member-4",
      name: "Sarah Wilson",
      email: "sarah.wilson@email.com",
      avatar: "/placeholder.svg?height=64&width=64",
      role: "Member",
      permissions: {
        editJamboard: true,
        addTravelers: true,
        editBudget: true,
        addDestinations: true,
      },
    },
    {
      id: "member-5",
      name: "Mike Johnson",
      email: "mike.johnson@email.com",
      avatar: "/placeholder.svg?height=64&width=64",
      role: "Member",
      permissions: {
        editJamboard: true,
        addTravelers: true,
        editBudget: true,
        addDestinations: true,
      },
    },
  ],
  tasks: [
    {
      id: "task-1",
      title: "Find new travel buddies",
      description: "Recruit 2 more members for the trip",
      status: "In Progress",
      assignee: {
        name: "Jane",
        avatar: "/placeholder.svg?height=24&width=24",
        initials: "JC",
      },
    },
    {
      id: "task-2",
      title: "My travel plans",
      description: "Finalize personal itinerary and preferences",
      status: "Completed",
      assignee: {
        name: "Ahmad",
        avatar: "/placeholder.svg?height=24&width=24",
        initials: "AA",
      },
    },
    {
      id: "task-3",
      title: "Fun with Sarah",
      description: "Plan special activities and surprises",
      status: "Pending",
      assignee: {
        name: "Sarah",
        avatar: "/placeholder.svg?height=24&width=24",
        initials: "SW",
      },
    },
    {
      id: "task-4",
      title: "Book restaurant reservations",
      description: "Reserve tables for group dinners",
      status: "Review",
      assignee: {
        name: "Mike",
        avatar: "/placeholder.svg?height=24&width=24",
        initials: "MJ",
      },
    },
    {
      id: "task-5",
      title: "Pack camping gear",
      description: "Prepare equipment for camping night",
      status: "Overdue",
      assignee: {
        name: "Nolan",
        avatar: "/placeholder.svg?height=24&width=24",
        initials: "NO",
      },
    },
    {
      id: "task-6",
      title: "Create photo album",
      description: "Set up shared album for trip photos",
      status: "Not Started",
    },
  ],
  travelGuide: {
    name: "Wade Warren",
    title: "Professional Travel Guide",
    avatar: "/placeholder.svg?height=64&width=64",
    rating: 4.9,
    reviews: 127,
  },
}

const View = () => {
      const [currentSlide, setCurrentSlide] = useState(0)

     const { props } = usePage();
        const record = props.record;
        if (!record) {
          return <div className="text-center py-20 text-gray-500">record not found.</div>;
        }
        console.log(record)
  return (
    <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Dashboard" />
         <div className="min-h-screen bg-gray-50 p-6">
 <div className="min-h-screen bg-gray-50 p-6">
      <div className="max-w-7xl mx-auto space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Jamboard Details</h1>
            <p className="text-gray-600">Manage travel planning board and members</p>
          </div>
          <div className="flex gap-3">
            <Button variant="outline">
              <Edit className="w-4 h-4 mr-2" />
              Edit Jamboard
            </Button>
            <Button variant="destructive">
              <Trash2 className="w-4 h-4 mr-2" />
              Delete Jamboard
            </Button>
          </div>
        </div>

        {/* Jamboard Overview Card */}
        <Card>
          <CardContent className="p-0">
            <div className="relative h-64 w-full">
              {/* Slider implementation */}
              <div className="relative h-full w-full overflow-hidden">
                {jamboardData.images.map((src, index) => (
                  <div
                    key={index}
                    className="absolute inset-0 transition-opacity duration-500"
                    style={{
                      opacity: currentSlide === index ? 1 : 0,
                      zIndex: currentSlide === index ? 10 : 0,
                    }}
                  >
                    <Image
                      src={src || "/placeholder.svg"}
                      alt={`${jamboardData.title} slide ${index + 1}`}
                      fill
                      className="object-cover rounded-t-lg"
                    />
                  </div>
                ))}

                {/* Slider navigation buttons */}
                <button
                  onClick={() => setCurrentSlide((prev) => (prev === 0 ? jamboardData.images.length - 1 : prev - 1))}
                  className="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition-colors"
                  aria-label="Previous slide"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <path d="m15 18-6-6 6-6" />
                  </svg>
                </button>

                <button
                  onClick={() => setCurrentSlide((prev) => (prev === jamboardData.images.length - 1 ? 0 : prev + 1))}
                  className="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition-colors"
                  aria-label="Next slide"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <path d="m9 18 6-6-6-6" />
                  </svg>
                </button>

                {/* Slide indicator */}
                <div className="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-medium z-20">
                  {currentSlide + 1}/{jamboardData.images.length}
                </div>
              </div>

              <div className="absolute bottom-4 left-4 bg-black/70 text-white px-4 py-2 rounded-lg z-20">
                <h2 className="text-2xl font-bold">{jamboardData.title}</h2>
                <div className="flex items-center gap-1 text-sm">
                  <MapPin className="w-4 h-4" />
                  {jamboardData.location}
                </div>
              </div>

              {/* Dot indicators */}
              <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                {jamboardData.images.map((_, index) => (
                  <button
                    key={index}
                    onClick={() => setCurrentSlide(index)}
                    className={`w-2 h-2 rounded-full transition-colors ${
                      currentSlide === index ? "bg-white" : "bg-white/50"
                    }`}
                    aria-label={`Go to slide ${index + 1}`}
                  />
                ))}
              </div>
            </div>

            <div className="p-6">
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div className="flex items-center gap-3">
                  <Users className="w-5 h-5 text-blue-600" />
                  <div>
                    <div className="font-semibold">{jamboardData.stats.tripmates} Tripmates</div>
                    <div className="text-sm text-gray-600">Active members</div>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <Calendar className="w-5 h-5 text-green-600" />
                  <div>
                    <div className="font-semibold">
                      {jamboardData.dates.start} - {jamboardData.dates.end}
                    </div>
                    <div className="text-sm text-gray-600">Travel dates</div>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <DollarSign className="w-5 h-5 text-purple-600" />
                  <div>
                    <div className="font-semibold">${jamboardData.budget.total} Budget</div>
                    <div className="text-sm text-gray-600">Per person</div>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <Clock className="w-5 h-5 text-orange-600" />
                  <div>
                    <div className="font-semibold">3h 58min</div>
                    <div className="text-sm text-gray-600">Travel time</div>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Tabs Section */}
        <Tabs defaultValue="overview" className="space-y-6">
          <TabsList className="grid w-full grid-cols-6">
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="members">Members ({jamboardData.members.length})</TabsTrigger>
            <TabsTrigger value="itinerary">Itinerary</TabsTrigger>
            <TabsTrigger value="tasks">Tasks</TabsTrigger>
            <TabsTrigger value="media">Media</TabsTrigger>
            <TabsTrigger value="settings">Settings</TabsTrigger>
          </TabsList>

          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Map Overview */}
              <Card>
                <CardHeader>
                  <CardTitle>Route Overview</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                    <Image
                      src="/placeholder.svg?height=300&width=400"
                      alt="Travel route map"
                      fill
                      className="object-cover"
                    />
                    <div className="absolute bottom-4 left-4 bg-black/70 text-white px-3 py-2 rounded-lg">
                      <div className="text-sm font-medium">3 hr 58 min</div>
                      <div className="text-xs">Tolls included</div>
                    </div>
                  </div>
                  <div className="mt-4 space-y-2">
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">From: Padua</span>
                      <span className="text-sm text-gray-600">To: Rome</span>
                    </div>
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">Distance: 485 km</span>
                      <span className="text-sm text-gray-600">Via: San Marino</span>
                    </div>
                  </div>
                </CardContent>
              </Card>

              {/* Travel Guide */}
              <Card>
                <CardHeader>
                  <CardTitle>Travel Guide</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="flex items-center gap-4 mb-4">
                    <Avatar className="w-16 h-16">
                      <AvatarImage
                        src={jamboardData.travelGuide.avatar || "/placeholder.svg"}
                        alt={jamboardData.travelGuide.name}
                      />
                      <AvatarFallback>WW</AvatarFallback>
                    </Avatar>
                    <div>
                      <h3 className="font-semibold text-lg">{jamboardData.travelGuide.name}</h3>
                      <p className="text-sm text-gray-600">{jamboardData.travelGuide.title}</p>
                      <div className="flex items-center gap-1 mt-1">
                        <div className="flex">
                          {[1, 2, 3, 4, 5].map((star) => (
                            <div key={star} className="w-3 h-3 bg-yellow-400 rounded-full mr-1"></div>
                          ))}
                        </div>
                        <span className="text-xs text-gray-500">
                          {jamboardData.travelGuide.rating} ({jamboardData.travelGuide.reviews} reviews)
                        </span>
                      </div>
                    </div>
                  </div>
                  <Button variant="outline" className="w-full">
                    View Profile
                  </Button>
                </CardContent>
              </Card>
            </div>

            {/* Quick Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              <Card>
                <CardContent className="p-4 text-center">
                  <div className="text-2xl font-bold text-blue-600">{jamboardData.stats.totalGuests}</div>
                  <div className="text-sm text-gray-600">Total Guests</div>
                </CardContent>
              </Card>
              <Card>
                <CardContent className="p-4 text-center">
                  <div className="text-2xl font-bold text-green-600">{jamboardData.stats.stayDays}</div>
                  <div className="text-sm text-gray-600">Stay Days</div>
                </CardContent>
              </Card>
              <Card>
                <CardContent className="p-4 text-center">
                  <div className="text-2xl font-bold text-purple-600">${jamboardData.stats.totalBudget}</div>
                  <div className="text-sm text-gray-600">Total Budget</div>
                </CardContent>
              </Card>
              <Card>
                <CardContent className="p-4 text-center">
                  <div className="text-2xl font-bold text-orange-600">{jamboardData.stats.planningComplete}%</div>
                  <div className="text-sm text-gray-600">Planning Complete</div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          <TabsContent value="members" className="space-y-6">
            <div className="flex justify-between items-center">
              <h3 className="text-lg font-semibold">Jamboard Members</h3>
              <Button>
                <Users className="w-4 h-4 mr-2" />
                Add Member
              </Button>
            </div>

            <div className="space-y-4">
              {jamboardData.members.map((member) => (
                <MemberCard key={member.id} member={member} />
              ))}
            </div>
          </TabsContent>

          <TabsContent value="itinerary" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <FlightDetails flight={jamboardData.flight} />
              <AccommodationDetails accommodation={jamboardData.accommodation} />
              <ExperienceDetails experiences={jamboardData.experiences} />
              <BudgetBreakdown budget={jamboardData.budget} />
            </div>
          </TabsContent>

          <TabsContent value="tasks" className="space-y-6">
            <div className="flex justify-between items-center">
              <h3 className="text-lg font-semibold">Jamboard Tasks</h3>
              <Button>Add Task</Button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              {jamboardData.tasks.map((task) => (
                <TaskCard key={task.id} task={task} />
              ))}
            </div>
          </TabsContent>

          <TabsContent value="media" className="space-y-6">
            <div className="flex justify-between items-center">
              <h3 className="text-lg font-semibold">Jamboard Media</h3>
              <Button>Upload Media</Button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
              {jamboardData.images.map((image, index) => (
                <div key={index} className="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                  <Image src={image || "/placeholder.svg"} alt={`Media ${index + 1}`} fill className="object-cover" />
                  {index === 0 && (
                    <div className="absolute top-2 right-2 bg-black/70 text-white px-2 py-1 rounded text-xs">Hero</div>
                  )}
                </div>
              ))}
            </div>
          </TabsContent>

          <TabsContent value="settings" className="space-y-6">
            <Card>
              <CardHeader>
                <CardTitle>Jamboard Settings</CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="flex items-center justify-between">
                  <div>
                    <div className="font-medium">Public Visibility</div>
                    <div className="text-sm text-gray-600">Allow others to discover this jamboard</div>
                  </div>
                  <Switch />
                </div>
                <div className="flex items-center justify-between">
                  <div>
                    <div className="font-medium">Member Invitations</div>
                    <div className="text-sm text-gray-600">Allow members to invite others</div>
                  </div>
                  <Switch defaultChecked />
                </div>
                <div className="flex items-center justify-between">
                  <div>
                    <div className="font-medium">Chat Notifications</div>
                    <div className="text-sm text-gray-600">Send notifications for new messages</div>
                  </div>
                  <Switch defaultChecked />
                </div>
                <div className="flex items-center justify-between">
                  <div>
                    <div className="font-medium">Auto-save Changes</div>
                    <div className="text-sm text-gray-600">Automatically save all modifications</div>
                  </div>
                  <Switch defaultChecked />
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>

        {/* Chat Button */}
        <div className="fixed bottom-6 right-6">
          <Button size="lg" className="rounded-full shadow-lg">
            <MessageCircle className="w-5 h-5 mr-2" />
            Open Chat
          </Button>
        </div>
      </div>
    </div>
            </div>
            </AppLayout>
  )
}

export default View
