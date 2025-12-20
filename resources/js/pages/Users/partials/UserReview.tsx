import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Review } from '@/types/User'
import { MoreHorizontal, Star } from 'lucide-react'
import React from 'react'

const UserReview = ({review}:{review:Review}) => {
return (
<Card key={review.id}>
    <CardContent className="p-4">
        <div className="flex items-start gap-3">
            <Avatar className="w-10 h-10">
                <AvatarImage src={review?.reviewed_user?.profile_photo} alt={review?.reviewed_user?.name} />
                <AvatarFallback>{review?.reviewed_user?.name.charAt(0)}</AvatarFallback>
            </Avatar>
            <div className="flex-1">
                <div className="flex items-center justify-between mb-1">
                    <h4 className="font-semibold">{review?.reviewed_user?.name}</h4>
                    <Button variant="ghost" size="sm">
                        <MoreHorizontal  className="w-4 h-4" />
                    </Button>
                </div>
                <div className="flex items-center gap-1 mb-2">
                    {[1, 2, 3, 4, 5].map((star) => (
                    <Star key={star} className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                    ))}
                    <span className="text-xs text-gray-600 ml-1">{new
                        Date(review.created_at).toLocaleDateString()}</span>
                </div>
                <p className="text-sm text-gray-700">{review.comment}</p>
            </div>
        </div>
    </CardContent>
</Card>
)
}

export default UserReview
