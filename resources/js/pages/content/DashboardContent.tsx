import React from 'react'

import { useState } from "react"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Progress } from "@/components/ui/progress"
import {
  Users,
  Map,
  CreditCard,
  Calendar,
  Globe,
  Heart,
  MessageSquare,
  CheckSquare,
  ArrowUpRight,
  ArrowDownRight,
  MoreHorizontal,
  ChevronRight,
} from "lucide-react"
import { Stat } from '@/services/types/Dashboard'


const recentJamboards = [
  {
    id: 1,
    name: "European Summer Adventure",
    destination: "Paris, Rome, Barcelona",
    participants: 5,
    progress: 75,
    startDate: "Jun 15, 2025",
    status: "active",
  },
  {
    id: 2,
    name: "Asian Cultural Tour",
    destination: "Tokyo, Seoul, Bangkok",
    participants: 3,
    progress: 45,
    startDate: "Aug 10, 2025",
    status: "planning",
  },
  {
    id: 3,
    name: "African Safari",
    destination: "Kenya, Tanzania",
    participants: 8,
    progress: 90,
    startDate: "Jul 22, 2025",
    status: "active",
  },
  {
    id: 4,
    name: "South American Expedition",
    destination: "Peru, Brazil, Argentina",
    participants: 4,
    progress: 30,
    startDate: "Sep 05, 2025",
    status: "planning",
  },
]

const topDestinations = [
  { name: "Paris, France", count: 845, percentage: 18 },
  { name: "Bali, Indonesia", count: 732, percentage: 15 },
  { name: "Tokyo, Japan", count: 654, percentage: 13 },
  { name: "New York, USA", count: 587, percentage: 12 },
  { name: "Barcelona, Spain", count: 521, percentage: 10 },
]

const recentUsers = [
  {
    id: 1,
    name: "Emma Wilson",
    email: "emma@example.com",
    role: "Traveler",
    joinDate: "May 24, 2025",
    avatar: "/placeholder.svg?height=40&width=40&query=woman portrait",
  },
  {
    id: 2,
    name: "James Rodriguez",
    email: "james@example.com",
    role: "Guide",
    joinDate: "May 23, 2025",
    avatar: "/placeholder.svg?height=40&width=40&query=man portrait",
  },
  {
    id: 3,
    name: "Sophia Chen",
    email: "sophia@example.com",
    role: "Traveler",
    joinDate: "May 22, 2025",
    avatar: "/placeholder.svg?height=40&width=40&query=woman portrait asian",
  },
  {
    id: 4,
    name: "Michael Johnson",
    email: "michael@example.com",
    role: "Traveler",
    joinDate: "May 21, 2025",
    avatar: "/placeholder.svg?height=40&width=40&query=man portrait black",
  },
]

const recentPosts = [
  {
    id: 1,
    author: "Emma Wilson",
    content: "Just finished an amazing trek through the Swiss Alps! The views were breathtaking. #SwissAlps #Hiking",
    likes: 245,
    comments: 32,
    time: "2 hours ago",
    avatar: "/placeholder.svg?height=40&width=40&query=woman portrait",
  },
  {
    id: 2,
    author: "James Rodriguez",
    content:
      "My guide to the best street food in Bangkok is now live! Check it out for all the hidden gems. #Bangkok #StreetFood",
    likes: 189,
    comments: 28,
    time: "5 hours ago",
    avatar: "/placeholder.svg?height=40&width=40&query=man portrait",
  },
  {
    id: 3,
    author: "Sophia Chen",
    content: "Sunset at Santorini was magical. Definitely a must-visit destination! #Santorini #Greece #Sunset",
    likes: 312,
    comments: 45,
    time: "8 hours ago",
    avatar: "/placeholder.svg?height=40&width=40&query=woman portrait asian",
  },
]

const DashboardContent = ({stats}:{stats:Stat[]}) => {
  const [timeRange, setTimeRange] = useState("week")

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
          <p className="text-gray-500">Welcome to the Travelilly admin dashboard</p>
        </div>
        <div className="flex items-center gap-2">
          <Tabs value={timeRange} onValueChange={setTimeRange} className="w-[400px]">
            <TabsList className="grid w-full grid-cols-4">
              <TabsTrigger value="day">Day</TabsTrigger>
              <TabsTrigger value="week">Week</TabsTrigger>
              <TabsTrigger value="month">Month</TabsTrigger>
              <TabsTrigger value="year">Year</TabsTrigger>
            </TabsList>
          </Tabs>
        </div>
      </div>

      {/* Stats Overview */}
      <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        {stats.map((stat, index) => (
          <Card key={index} className="overflow-hidden">
            <CardHeader className="flex flex-row items-center justify-between pb-2 space-y-0">
              <CardTitle className="text-sm font-medium">{stat.title}</CardTitle>
              <div className={`${stat.color} p-2 rounded-full`}>
                <stat.icon className="w-4 h-4 text-white" />
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stat.value}</div>
              <div className="flex items-center mt-1 text-xs">
                {stat.trend === "up" ? (
                  <ArrowUpRight className="w-3 h-3 mr-1 text-emerald-500" />
                ) : (
                  <ArrowDownRight className="w-3 h-3 mr-1 text-red-500" />
                )}
                <span className={stat.trend === "up" ? "text-emerald-500" : "text-red-500"}>
                  {stat.change} from last {timeRange}
                </span>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {/* Main Content */}
      <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {/* Recent Jamboards */}
        <Card className="lg:col-span-2">
          <CardHeader className="flex flex-row items-center justify-between">
            <div>
              <CardTitle>Recent Jamboards</CardTitle>
              <CardDescription>Latest trip planning activities</CardDescription>
            </div>
            <Button variant="outline" size="sm">
              View All
            </Button>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentJamboards.map((jamboard) => (
                <div key={jamboard.id} className="flex items-center space-x-4">
                  <div className="flex-1">
                    <div className="flex items-center justify-between mb-1">
                      <div className="font-medium">{jamboard.name}</div>
                      <Badge variant={jamboard.status === "active" ? "default" : "outline"}>{jamboard.status}</Badge>
                    </div>
                    <div className="text-sm text-gray-500 mb-2">{jamboard.destination}</div>
                    <div className="flex items-center justify-between text-xs text-gray-500 mb-1">
                      <div className="flex items-center">
                        <Users className="w-3 h-3 mr-1" />
                        {jamboard.participants} participants
                      </div>
                      <div>{jamboard.startDate}</div>
                    </div>
                    <div className="flex items-center">
                      <Progress value={jamboard.progress} className="h-1.5 flex-1" />
                      <span className="ml-2 text-xs font-medium">{jamboard.progress}%</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Top Destinations */}
        <Card>
          <CardHeader>
            <CardTitle>Top Destinations</CardTitle>
            <CardDescription>Most popular travel locations</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {topDestinations.map((destination, index) => (
                <div key={index} className="flex items-center justify-between">
                  <div className="flex items-center">
                    <div className="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                      <Globe className="w-4 h-4 text-blue-600" />
                    </div>
                    <div>
                      <div className="font-medium">{destination.name}</div>
                      <div className="text-xs text-gray-500">{destination.count} jamboards</div>
                    </div>
                  </div>
                  <div className="text-sm font-medium">{destination.percentage}%</div>
                </div>
              ))}
              <Button variant="outline" size="sm" className="w-full mt-2">
                View All Destinations
              </Button>
            </div>
          </CardContent>
        </Card>

        {/* Recent Users */}
        <Card>
          <CardHeader className="flex flex-row items-center justify-between">
            <div>
              <CardTitle>Recent Users</CardTitle>
              <CardDescription>Newly registered users</CardDescription>
            </div>
            <Button variant="ghost" size="icon">
              <MoreHorizontal className="w-4 h-4" />
            </Button>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentUsers.map((user) => (
                <div key={user.id} className="flex items-center justify-between">
                  <div className="flex items-center">
                    <Avatar className="w-8 h-8 mr-3">
                      <AvatarImage src={user.avatar || "/placeholder.svg"} alt={user.name} />
                      <AvatarFallback>
                        {user.name
                          .split(" ")
                          .map((n) => n[0])
                          .join("")}
                      </AvatarFallback>
                    </Avatar>
                    <div>
                      <div className="font-medium">{user.name}</div>
                      <div className="text-xs text-gray-500">{user.role}</div>
                    </div>
                  </div>
                  <Badge variant="outline" className="text-xs">
                    {user.joinDate}
                  </Badge>
                </div>
              ))}
              <Button variant="outline" size="sm" className="w-full mt-2">
                View All Users
              </Button>
            </div>
          </CardContent>
        </Card>

        {/* Recent Posts */}
        <Card className="lg:col-span-2">
          <CardHeader className="flex flex-row items-center justify-between">
            <div>
              <CardTitle>Recent Posts</CardTitle>
              <CardDescription>Latest user content</CardDescription>
            </div>
            <Button variant="outline" size="sm">
              View All
            </Button>
          </CardHeader>
          <CardContent>
            <div className="space-y-6">
              {recentPosts.map((post) => (
                <div key={post.id} className="space-y-2">
                  <div className="flex items-center space-x-3">
                    <Avatar className="w-8 h-8">
                      <AvatarImage src={post.avatar || "/placeholder.svg"} alt={post.author} />
                      <AvatarFallback>
                        {post.author
                          .split(" ")
                          .map((n) => n[0])
                          .join("")}
                      </AvatarFallback>
                    </Avatar>
                    <div>
                      <div className="font-medium">{post.author}</div>
                      <div className="text-xs text-gray-500">{post.time}</div>
                    </div>
                  </div>
                  <p className="text-sm text-gray-700">{post.content}</p>
                  <div className="flex items-center space-x-4 text-xs text-gray-500">
                    <div className="flex items-center">
                      <Heart className="w-3.5 h-3.5 mr-1" />
                      {post.likes} likes
                    </div>
                    <div className="flex items-center">
                      <MessageSquare className="w-3.5 h-3.5 mr-1" />
                      {post.comments} comments
                    </div>
                    <Button variant="ghost" size="sm" className="ml-auto h-7 px-2">
                      View Post <ChevronRight className="ml-1 w-3.5 h-3.5" />
                    </Button>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Tasks Overview */}
        <Card>
          <CardHeader>
            <CardTitle>Tasks Overview</CardTitle>
            <CardDescription>Trip planning tasks</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <div className="text-sm font-medium">Pending Tasks</div>
                <div className="text-2xl font-bold">24</div>
              </div>
              <div className="flex items-center justify-between">
                <div className="text-sm font-medium">In Progress</div>
                <div className="text-2xl font-bold">18</div>
              </div>
              <div className="flex items-center justify-between">
                <div className="text-sm font-medium">Completed</div>
                <div className="text-2xl font-bold">42</div>
              </div>
              <div className="pt-4">
                <div className="flex items-center justify-between mb-2">
                  <div className="text-sm font-medium">Task Completion</div>
                  <div className="text-sm font-medium">65%</div>
                </div>
                <Progress value={65} className="h-2" />
              </div>
              <Button variant="outline" size="sm" className="w-full mt-2">
                <CheckSquare className="w-4 h-4 mr-2" />
                Manage Tasks
              </Button>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}

export default DashboardContent
