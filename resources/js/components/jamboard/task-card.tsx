import { MoreHorizontal } from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"

interface TaskCardProps {
  task: {
    id: string
    title: string
    description: string
    status: string
    assignee?: {
      name: string
      avatar: string
      initials: string
    }
  }
}

export function TaskCard({ task }: TaskCardProps) {
  const getStatusBadgeColor = (status: string) => {
    switch (status.toLowerCase()) {
      case "completed":
        return "bg-green-100 text-green-800"
      case "in progress":
        return "bg-blue-100 text-blue-800"
      case "pending":
        return "bg-yellow-100 text-yellow-800"
      case "review":
        return "bg-purple-100 text-purple-800"
      case "overdue":
        return "bg-red-100 text-red-800"
      default:
        return "bg-gray-100 text-gray-800"
    }
  }

  return (
    <Card>
      <CardContent className="p-4">
        <div className="flex items-center justify-between mb-3">
          <Badge className={getStatusBadgeColor(task.status)}>{task.status}</Badge>
          <Button variant="ghost" size="sm">
            <MoreHorizontal className="w-4 h-4" />
          </Button>
        </div>
        <h4 className="font-semibold mb-2">{task.title}</h4>
        <p className="text-sm text-gray-600 mb-3">{task.description}</p>
        {task.assignee ? (
          <div className="flex items-center gap-2">
            <Avatar className="w-6 h-6">
              <AvatarImage src={task.assignee.avatar || "/placeholder.svg"} alt={task.assignee.name} />
              <AvatarFallback className="text-xs">{task.assignee.initials}</AvatarFallback>
            </Avatar>
            <span className="text-sm text-gray-600">Assigned to {task.assignee.name}</span>
          </div>
        ) : (
          <div className="text-sm text-gray-600">Unassigned</div>
        )}
      </CardContent>
    </Card>
  )
}
