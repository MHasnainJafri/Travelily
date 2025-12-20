import { Hotel } from "lucide-react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"

interface AccommodationDetailsProps {
  accommodation: {
    name: string
    type: string
    rating: number
    status: string
    address: string
    checkIn: {
      date: string
      time: string
    }
    checkOut: {
      date: string
      time: string
    }
    amenities: string[]
  }
}

export function AccommodationDetails({ accommodation }: AccommodationDetailsProps) {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Hotel className="w-5 h-5" />
          Accommodation
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
            <div>
              <div className="font-semibold">{accommodation.name}</div>
              <div className="text-sm text-gray-600">
                {accommodation.type} in {accommodation.address}
              </div>
              <div className="flex gap-1 mt-1">
                {accommodation.amenities.map((amenity, index) => (
                  <Badge key={index} variant="outline" className="text-xs">
                    {amenity}
                  </Badge>
                ))}
              </div>
            </div>
            <Badge className="bg-green-100 text-green-800">{accommodation.status}</Badge>
          </div>
          <div className="grid grid-cols-2 gap-4 text-sm">
            <div>
              <div className="text-gray-600">Check-in</div>
              <div className="font-medium">
                {accommodation.checkIn.date}, {accommodation.checkIn.time}
              </div>
            </div>
            <div>
              <div className="text-gray-600">Check-out</div>
              <div className="font-medium">
                {accommodation.checkOut.date}, {accommodation.checkOut.time}
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
