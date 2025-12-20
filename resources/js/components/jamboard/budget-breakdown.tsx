import { DollarSign } from "lucide-react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Progress } from "@/components/ui/progress"

interface BudgetBreakdownProps {
  budget: {
    total: number
    allocated: number
    breakdown: Array<{
      category: string
      amount: number
    }>
  }
}

export function BudgetBreakdown({ budget }: BudgetBreakdownProps) {
  const percentage = (budget.allocated / budget.total) * 100

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <DollarSign className="w-5 h-5" />
          Budget Breakdown
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-3">
          {budget.breakdown.map((item, index) => (
            <div key={index} className="flex justify-between">
              <span className="text-sm">{item.category}</span>
              <span className="font-medium">${item.amount}</span>
            </div>
          ))}
          <hr />
          <div className="flex justify-between font-semibold">
            <span>Total per person</span>
            <span>${budget.total}</span>
          </div>
          <Progress value={percentage} className="mt-2" />
          <div className="text-xs text-gray-600">{Math.round(percentage)}% of budget allocated</div>
        </div>
      </CardContent>
    </Card>
  )
}
