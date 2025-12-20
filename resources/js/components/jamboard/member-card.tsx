import { CheckCircle, XCircle, MoreHorizontal } from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"

interface MemberCardProps {
  member: {
    id: string
    name: string
    email: string
    avatar: string
    role: string
    permissions: {
      editJamboard: boolean
      addTravelers: boolean
      editBudget: boolean
      addDestinations: boolean
    }
  }
}

export function MemberCard({ member }: MemberCardProps) {
  const getInitials = (name: string) => {
    return name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase()
  }

  const getRoleBadgeColor = (role: string) => {
    switch (role.toLowerCase()) {
      case "admin":
        return "bg-blue-100 text-blue-800"
      case "member":
        return "bg-green-100 text-green-800"
      case "limited":
        return "bg-yellow-100 text-yellow-800"
      default:
        return "bg-gray-100 text-gray-800"
    }
  }

  return (
    <Card>
      <CardContent className="p-6">
        <div className="flex items-start justify-between">
          <div className="flex items-center gap-4">
            <Avatar className="w-16 h-16">
              <AvatarImage src={member.avatar || "/placeholder.svg"} alt={member.name} />
              <AvatarFallback>{getInitials(member.name)}</AvatarFallback>
            </Avatar>
            <div>
              <h4 className="font-semibold text-lg">{member.name}</h4>
              <p className="text-sm text-gray-600">{member.email}</p>
              <div className="flex gap-2 mt-2">
                <Badge className={getRoleBadgeColor(member.role)}>{member.role}</Badge>
              </div>
            </div>
          </div>
          <Button variant="ghost" size="sm">
            <MoreHorizontal className="w-4 h-4" />
          </Button>
        </div>

        <div className="mt-4">
          <h5 className="font-medium mb-3">Permissions</h5>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div className="flex items-center justify-between">
              <span className="text-sm">Edit Jamboard</span>
              {member.permissions.editJamboard ? (
                <CheckCircle className="w-5 h-5 text-green-600" />
              ) : (
                <XCircle className="w-5 h-5 text-red-600" />
              )}
            </div>
            <div className="flex items-center justify-between">
              <span className="text-sm">Add New Travelers</span>
              {member.permissions.addTravelers ? (
                <CheckCircle className="w-5 h-5 text-green-600" />
              ) : (
                <XCircle className="w-5 h-5 text-red-600" />
              )}
            </div>
            <div className="flex items-center justify-between">
              <span className="text-sm">Edit Budget</span>
              {member.permissions.editBudget ? (
                <CheckCircle className="w-5 h-5 text-green-600" />
              ) : (
                <XCircle className="w-5 h-5 text-red-600" />
              )}
            </div>
            <div className="flex items-center justify-between">
              <span className="text-sm">Add Destinations</span>
              {member.permissions.addDestinations ? (
                <CheckCircle className="w-5 h-5 text-green-600" />
              ) : (
                <XCircle className="w-5 h-5 text-red-600" />
              )}
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
