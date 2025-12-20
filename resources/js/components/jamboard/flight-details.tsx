import { Plane } from "lucide-react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"

interface FlightDetailsProps {
  flight: {
    flightNumber: string
    airline: string
    status: string
    departure: {
      time: string
      date: string
      airport: string
      code: string
    }
    arrival: {
      time: string
      date: string
      airport: string
      code: string
    }
  }
}

export function FlightDetails({ flight }: FlightDetailsProps) {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Plane className="w-5 h-5" />
          Flight Details
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          <div className="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
            <div>
              <div className="font-semibold">{flight.flightNumber}</div>
              <div className="text-sm text-gray-600">{flight.airline}</div>
            </div>
            <Badge className="bg-blue-100 text-blue-800">{flight.status}</Badge>
          </div>
          <div className="grid grid-cols-2 gap-4 text-sm">
            <div>
              <div className="text-gray-600">Departure</div>
              <div className="font-medium">
                {flight.departure.date}, {flight.departure.time}
              </div>
              <div className="text-gray-600">
                {flight.departure.airport} ({flight.departure.code})
              </div>
            </div>
            <div>
              <div className="text-gray-600">Arrival</div>
              <div className="font-medium">
                {flight.arrival.date}, {flight.arrival.time}
              </div>
              <div className="text-gray-600">
                {flight.arrival.airport} ({flight.arrival.code})
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
