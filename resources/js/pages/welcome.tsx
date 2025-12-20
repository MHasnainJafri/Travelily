import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import {
  Star,
  MapPin,
  Calendar,
  Search,
  Navigation,
  Shield,
  Users,
  Download,
  Apple,
  Smartphone,
  ArrowRight,
  CheckCircle,
  UserPlus,
  MessageSquare,
  Plane,
} from "lucide-react";
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import Image from "@/components/Image";
export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <div className="min-h-screen bg-white overflow-x-hidden">
      {/* Header */}
      <header className="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <div className="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
              <span className="text-white font-bold text-sm">T</span>
            </div>
            <span className="text-xl font-bold text-gray-900">TRAVELLY</span>
          </div>
          <nav className="hidden md:flex items-center space-x-8">
            <Link href="#features" className="text-gray-600 hover:text-purple-600 transition-colors font-medium">
              Features
            </Link>
            <Link href="#journey" className="text-gray-600 hover:text-purple-600 transition-colors font-medium">
              How it Works
            </Link>
            <Link href="#testimonials" className="text-gray-600 hover:text-purple-600 transition-colors font-medium">
              Reviews
            </Link>
            <Link href="#download" className="text-gray-600 hover:text-purple-600 transition-colors font-medium">
              Download
            </Link>
          </nav>
          <Button className="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white shadow-lg">
            Get Started
          </Button>
        </div>
      </header>

      {/* Hero Section */}
      <section className="pt-24 pb-20 bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 relative overflow-hidden">
        {/* Background decorations */}
        <div className="absolute top-0 left-0 w-full h-full">
          <div className="absolute top-20 left-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
          <div className="absolute top-40 right-10 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse delay-1000"></div>
          <div className="absolute bottom-20 left-1/2 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse delay-2000"></div>
        </div>

        <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="grid lg:grid-cols-2 gap-12 xl:gap-20 items-center">
            <div className="space-y-8 text-center lg:text-left">
              <div className="space-y-6">
                <div className="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-sm rounded-full border border-purple-200 text-purple-700 text-sm font-medium">
                  <Star className="w-4 h-4 mr-2 text-yellow-500" />
                  Rated #1 Travel Planning App
                </div>
                <h1 className="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 leading-tight">
                  Plan Together,
                  <br />
                  <span className="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600">
                    Travel Better
                  </span>
                </h1>
                <p className="text-lg sm:text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                  The ultimate collaborative travel platform. Plan with friends, connect with local guides, and book
                  everything from accommodations to experiences in one beautiful app.
                </p>
              </div>

              <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <Button
                  size="lg"
                  className="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-4 text-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                >
                  <Apple className="mr-2 h-5 w-5" />
                  Download for iOS
                </Button>
                <Button
                  size="lg"
                  variant="outline"
                  className="border-2 border-purple-600 text-purple-600 hover:bg-purple-50 px-8 py-4 text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                >
                  <Smartphone className="mr-2 h-5 w-5" />
                  Get on Android
                </Button>
              </div>

              <div className="flex flex-wrap items-center justify-center lg:justify-start gap-6 sm:gap-8 text-sm text-gray-600">
                <div className="flex items-center space-x-2">
                  <Star className="h-5 w-5 text-yellow-400 fill-current" />
                  <span className="font-semibold">4.8</span>
                  <span>App Store</span>
                </div>
                <div className="flex items-center space-x-2">
                  <Download className="h-5 w-5 text-purple-600" />
                  <span className="font-semibold">100K+</span>
                  <span>Downloads</span>
                </div>
                <div className="flex items-center space-x-2">
                  <Users className="h-5 w-5 text-green-600" />
                  <span className="font-semibold">50K+</span>
                  <span>Active Users</span>
                </div>
              </div>
            </div>

            <div className="relative flex justify-center lg:justify-end">
              <div className="relative">
                {/* Floating elements */}
                <div className="absolute -top-4 -left-4 w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-400 rounded-2xl opacity-20 animate-bounce"></div>
                <div className="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-400 rounded-2xl opacity-20 animate-bounce delay-1000"></div>

                {/* Phone mockup */}
                <div className="relative mx-auto w-80 h-[600px] transform hover:scale-105 transition-transform duration-500">
                  <div className="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-400 rounded-[3rem] transform rotate-6 opacity-20 blur-sm"></div>
                  <div className="relative bg-white rounded-[3rem] p-2 shadow-2xl">
                    <Image
                      src="/placeholder.svg?height=580&width=300&query=modern travel app interface with purple theme"
                      alt="TRAVELLY Mobile App Interface"
                      width={300}
                      height={580}
                      className="rounded-[2.5rem] object-cover"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Curved Journey Section */}
      <section id="journey" className="py-20 bg-white relative overflow-hidden">
        {/* Curved background */}
        <div className="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50"></div>
        <svg className="absolute top-0 left-0 w-full h-32" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,0 C300,120 900,120 1200,0 L1200,120 L0,120 Z" fill="url(#gradient)" />
          <defs>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stopColor="#f3e8ff" />
              <stop offset="50%" stopColor="#fce7f3" />
              <stop offset="100%" stopColor="#dbeafe" />
            </linearGradient>
          </defs>
        </svg>

        <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="text-center mb-20">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Your Journey to Perfect Travel
            </h2>
            <p className="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
              Follow our simple, intuitive process to plan unforgettable trips with friends
            </p>
          </div>

          {/* Journey Steps */}
          <div className="relative max-w-6xl mx-auto">
            {/* Curved line connecting steps */}
            <svg className="absolute top-0 left-0 w-full h-full hidden lg:block" viewBox="0 0 1000 600">
              <path
                d="M 100 100 Q 300 50 500 150 T 900 100"
                stroke="url(#journeyGradient)"
                strokeWidth="3"
                fill="none"
                strokeDasharray="10,5"
                className="animate-pulse"
              />
              <defs>
                <linearGradient id="journeyGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stopColor="#8b5cf6" />
                  <stop offset="50%" stopColor="#ec4899" />
                  <stop offset="100%" stopColor="#3b82f6" />
                </linearGradient>
              </defs>
            </svg>

            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-4">
              {/* Step 1 */}
              <div className="relative group">
                <div className="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <UserPlus className="h-8 w-8 text-white" />
                  </div>
                  <div className="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                    1
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">Create & Invite</h3>
                  <p className="text-gray-600 text-center leading-relaxed">
                    Start a new trip and invite your friends to join your collaborative planning board
                  </p>
                </div>
              </div>

              {/* Step 2 */}
              <div className="relative group lg:mt-12">
                <div className="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                  <div className="w-16 h-16 bg-gradient-to-br from-pink-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <MessageSquare className="h-8 w-8 text-white" />
                  </div>
                  <div className="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-br from-pink-600 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                    2
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">Plan Together</h3>
                  <p className="text-gray-600 text-center leading-relaxed">
                    Use the interactive jamboard to brainstorm ideas, vote on activities, and build your itinerary
                  </p>
                </div>
              </div>

              {/* Step 3 */}
              <div className="relative group lg:mt-6">
                <div className="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                  <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <Calendar className="h-8 w-8 text-white" />
                  </div>
                  <div className="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                    3
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">Book Everything</h3>
                  <p className="text-gray-600 text-center leading-relaxed">
                    Reserve accommodations, transport, local guides, and activities all in one place
                  </p>
                </div>
              </div>

              {/* Step 4 */}
              <div className="relative group lg:mt-16">
                <div className="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <Plane className="h-8 w-8 text-white" />
                  </div>
                  <div className="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                    4
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">Travel & Enjoy</h3>
                  <p className="text-gray-600 text-center leading-relaxed">
                    Access your complete itinerary, navigate with ease, and create unforgettable memories
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section id="features" className="py-20 bg-gradient-to-br from-gray-50 to-blue-50 relative">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-20">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Everything You Need for Perfect Travel
            </h2>
            <p className="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
              From collaborative planning to local connections, TRAVELLY brings your entire travel ecosystem together
            </p>
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {[
              {
                icon: Users,
                title: "Collaborative Jamboard",
                description:
                  "Plan together with friends using our interactive jamboard. Add ideas, vote on activities, and create the perfect itinerary as a team.",
                color: "from-purple-500 to-pink-500",
                bgColor: "bg-purple-50",
              },
              {
                icon: Calendar,
                title: "Complete Tour Planning",
                description:
                  "Plan everything from accommodations to car rentals, activities to dining. One platform for your entire travel experience.",
                color: "from-green-500 to-blue-500",
                bgColor: "bg-green-50",
              },
              {
                icon: MapPin,
                title: "Local Guide Marketplace",
                description:
                  "Connect with verified local guides who can show you hidden gems and authentic experiences in any destination.",
                color: "from-blue-500 to-purple-500",
                bgColor: "bg-blue-50",
              },
              {
                icon: Navigation,
                title: "Social Trip Invites",
                description:
                  "Invite friends to join your adventures. Everyone can contribute ideas, book their portions, and stay coordinated.",
                color: "from-orange-500 to-red-500",
                bgColor: "bg-orange-50",
              },
              {
                icon: Shield,
                title: "Host Services",
                description:
                  "List your property or services as a host. Connect with travelers and provide authentic local experiences.",
                color: "from-pink-500 to-purple-500",
                bgColor: "bg-pink-50",
              },
              {
                icon: Search,
                title: "Smart Recommendations",
                description:
                  "AI-powered suggestions based on your group's preferences, budget, and travel style for personalized experiences.",
                color: "from-indigo-500 to-blue-500",
                bgColor: "bg-indigo-50",
              },
            ].map((feature, index) => (
              <Card
                key={index}
                className="border-0 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 bg-white/80 backdrop-blur-sm"
              >
                <CardContent className="p-8 text-center">
                  <div
                    className={`w-16 h-16 bg-gradient-to-br ${feature.color} rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg`}
                  >
                    <feature.icon className="h-8 w-8 text-white" />
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4">{feature.title}</h3>
                  <p className="text-gray-600 leading-relaxed">{feature.description}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Guide & Host Section */}
      <section className="py-20 bg-white relative overflow-hidden">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 xl:gap-20 items-center">
            <div className="order-2 lg:order-1">
              <div className="relative">
                <div className="absolute -top-4 -left-4 w-full h-full bg-gradient-to-br from-purple-100 to-pink-100 rounded-3xl transform rotate-3"></div>
                <Image
                  src="/placeholder.svg?height=500&width=600&query=local tour guide with travelers exploring beautiful city"
                  alt="Local Guide with Travelers"
                  width={600}
                  height={500}
                  className="relative rounded-3xl shadow-2xl object-cover"
                />
              </div>
            </div>

            <div className="order-1 lg:order-2 space-y-8">
              <div>
                <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">For Guides & Hosts</h2>
                <p className="text-lg sm:text-xl text-gray-600 leading-relaxed">
                  Join our community of local experts and turn your passion for travel into income. Whether you're a
                  tour guide, host, or local expert, TRAVELLY connects you with travelers seeking authentic experiences.
                </p>
              </div>

              <div className="space-y-6">
                {[
                  {
                    title: "Create Your Profile",
                    description: "Showcase your expertise, services, and local knowledge with a beautiful profile",
                  },
                  {
                    title: "Set Your Availability",
                    description: "Manage bookings and set your own schedule and rates with full control",
                  },
                  {
                    title: "Connect with Travelers",
                    description: "Get discovered by travelers looking for your unique services and experiences",
                  },
                ].map((item, index) => (
                  <div key={index} className="flex items-start space-x-4">
                    <div className="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1 shadow-lg">
                      <CheckCircle className="h-5 w-5 text-white" />
                    </div>
                    <div>
                      <h3 className="font-bold text-gray-900 mb-2 text-lg">{item.title}</h3>
                      <p className="text-gray-600 leading-relaxed">{item.description}</p>
                    </div>
                  </div>
                ))}
              </div>

              <Button
                className="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                size="lg"
              >
                Become a Guide/Host
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* App Screenshots Section */}
      <section className="py-20 bg-gradient-to-br from-purple-50 to-pink-50">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">See TRAVELLY in Action</h2>
            <p className="text-lg sm:text-xl text-gray-600">
              Experience the intuitive interface designed for modern travelers
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {[
              {
                title: "Browse Listings",
                description: "Explore curated accommodations with beautiful photos and detailed information.",
                image: "travel app home screen with listings",
              },
              {
                title: "Manage Bookings",
                description: "Track your reservations and manage your travel itinerary effortlessly.",
                image: "booking calendar interface",
              },
              {
                title: "Personal Profile",
                description: "Customize your preferences and access your travel history.",
                image: "travel app profile and settings",
              },
            ].map((item, index) => (
              <div key={index} className="text-center group">
                <div className="bg-white rounded-3xl p-6 shadow-lg mb-6 mx-auto w-72 transform group-hover:scale-105 transition-all duration-500 hover:shadow-2xl">
                  <Image
                    src={`/placeholder.svg?height=400&width=200&query=${item.image}`}
                    alt={item.title}
                    width={200}
                    height={400}
                    className="rounded-2xl mx-auto shadow-lg"
                  />
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-3">{item.title}</h3>
                <p className="text-gray-600 leading-relaxed max-w-xs mx-auto">{item.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials Section */}
      <section id="testimonials" className="py-20 bg-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
              Loved by Travelers Worldwide
            </h2>
            <p className="text-lg sm:text-xl text-gray-600">Join thousands of happy travelers who trust TRAVELLY</p>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {[
              {
                name: "Sarah Johnson",
                role: "Group Travel Organizer",
                content:
                  "The collaborative jamboard made planning our group trip to Italy so easy! Everyone could add their ideas and we voted on activities together. Our local guide was amazing too!",
                avatar: "happy female traveler",
              },
              {
                name: "Carlos Martinez",
                role: "Local Guide",
                content:
                  "As a local guide in Barcelona, TRAVELLY has transformed my business. I can showcase my tours, manage bookings, and connect with travelers who want authentic experiences.",
                avatar: "professional male tour guide",
              },
              {
                name: "Emma Rodriguez",
                role: "Travel Coordinator",
                content:
                  "Planning our friend group's trip was chaos until we found TRAVELLY. Now everyone can add activities, we split costs easily, and everything is organized in one place!",
                avatar: "young female digital nomad",
              },
            ].map((testimonial, index) => (
              <Card
                key={index}
                className="border-0 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 bg-white"
              >
                <CardContent className="p-8">
                  <div className="flex items-center mb-6">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} className="h-5 w-5 text-yellow-400 fill-current" />
                    ))}
                  </div>
                  <p className="text-gray-600 mb-8 leading-relaxed text-lg">"{testimonial.content}"</p>
                  <div className="flex items-center">
                    <Image
                      src={`/placeholder.svg?height=50&width=50&query=${testimonial.avatar}`}
                      alt={testimonial.name}
                      width={50}
                      height={50}
                      className="rounded-full mr-4 shadow-lg"
                    />
                    <div>
                      <p className="font-bold text-gray-900 text-lg">{testimonial.name}</p>
                      <p className="text-gray-600">{testimonial.role}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Download Section */}
      <section
        id="download"
        className="py-20 bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 relative overflow-hidden"
      >
        {/* Background decorations */}
        <div className="absolute top-0 left-0 w-full h-full">
          <div className="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
          <div className="absolute bottom-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">Start Your Journey Today</h2>
            <p className="text-lg sm:text-xl text-purple-100 mb-12 leading-relaxed">
              Download TRAVELLY now and discover a world of amazing travel experiences at your fingertips. Join
              thousands of travelers who plan better together.
            </p>

            <div className="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16">
              <Button
                size="lg"
                className="bg-white text-purple-600 hover:bg-gray-100 px-8 py-4 text-lg shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105"
              >
                <Apple className="mr-3 h-6 w-6" />
                <div className="text-left">
                  <div className="text-xs opacity-80">Download on the</div>
                  <div className="font-bold">App Store</div>
                </div>
              </Button>

              <Button
                size="lg"
                className="bg-white text-purple-600 hover:bg-gray-100 px-8 py-4 text-lg shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105"
              >
                <Smartphone className="mr-3 h-6 w-6" />
                <div className="text-left">
                  <div className="text-xs opacity-80">Get it on</div>
                  <div className="font-bold">Google Play</div>
                </div>
              </Button>
            </div>

            <div className="flex flex-wrap items-center justify-center gap-8 text-purple-100">
              <div className="flex items-center space-x-2">
                <Shield className="h-6 w-6" />
                <span className="font-medium">Secure & Private</span>
              </div>
              <div className="flex items-center space-x-2">
                <Users className="h-6 w-6" />
                <span className="font-medium">100K+ Users</span>
              </div>
              <div className="flex items-center space-x-2">
                <Star className="h-6 w-6" />
                <span className="font-medium">4.8 Rating</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-16">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="lg:col-span-1">
              <div className="flex items-center space-x-2 mb-6">
                <div className="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                  <span className="text-white font-bold">T</span>
                </div>
                <span className="text-2xl font-bold">TRAVELLY</span>
              </div>
              <p className="text-gray-400 leading-relaxed mb-6">
                Your perfect travel companion for discovering and booking amazing accommodations worldwide.
              </p>
              <div className="flex space-x-4">{/* Social media icons would go here */}</div>
            </div>

            {[
              {
                title: "Product",
                links: ["Features", "Pricing", "Security", "API"],
              },
              {
                title: "Company",
                links: ["About", "Careers", "Contact", "Blog"],
              },
              {
                title: "Support",
                links: ["Help Center", "Privacy Policy", "Terms of Service", "Status"],
              },
            ].map((section, index) => (
              <div key={index}>
                <h3 className="font-bold mb-6 text-lg">{section.title}</h3>
                <ul className="space-y-3">
                  {section.links.map((link, linkIndex) => (
                    <li key={linkIndex}>
                      <Link href="#" className="text-gray-400 hover:text-white transition-colors duration-300">
                        {link}
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>

          <div className="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
            <p>&copy; 2024 TRAVELLY. All rights reserved. Made with ❤️ for travelers worldwide.</p>
          </div>
        </div>
      </footer>
    </div>
    );
}
