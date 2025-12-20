import { Camera } from "lucide-react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"

interface ExperienceDetailsProps {
  experiences: Array<{
    name: string
    description: string
    status: string
    date: string
    time: string
    location: string
    category: string
  }>
}

export function ExperienceDetails({ experiences }: ExperienceDetailsProps) {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Camera className="w-5 h-5" />
          Experiences
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-3">
          {experiences.map((experience, index) => (
            <div key={index} className="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
              <div>
                <div className="font-semibold">{experience.name}</div>
                <div className="text-sm text-gray-600">{experience.description}</div>
                <div className="text-xs text-gray-500 mt-1">
                  {experience.date} at {experience.time} • {experience.location}
                </div>
              </div>
              <Badge className="bg-purple-100 text-purple-800">{experience.status}</Badge>
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  )
}
