<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userId = auth()->id();
        $otherParticipant = $this->isPersonal() ? $this->getOtherParticipant($userId) : null;
        
        $participant = $this->participantRecords
            ->where('user_id', $userId)
            ->first();

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->isPersonal() && $otherParticipant 
                ? $otherParticipant->name 
                : $this->name,
            'image' => $this->isPersonal() && $otherParticipant 
                ? $otherParticipant->profile_photo 
                : $this->image,
            'jam_id' => $this->jam_id,
            'jam' => $this->when($this->isJam() && $this->jam, function () {
                return [
                    'id' => $this->jam->id,
                    'name' => $this->jam->name,
                    'destination' => $this->jam->destination,
                    'start_date' => $this->jam->start_date?->format('Y-m-d'),
                    'end_date' => $this->jam->end_date?->format('Y-m-d'),
                ];
            }),
            'participants' => $this->when($this->relationLoaded('activeParticipants'), function () {
                return $this->activeParticipants->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'profile_photo' => $user->profile_photo,
                        'role' => $user->pivot->role,
                    ];
                });
            }),
            'participants_count' => $this->activeParticipants->count(),
            'other_participant' => $this->when($this->isPersonal() && $otherParticipant, function () use ($otherParticipant) {
                return [
                    'id' => $otherParticipant->id,
                    'name' => $otherParticipant->name,
                    'username' => $otherParticipant->username,
                    'profile_photo' => $otherParticipant->profile_photo,
                ];
            }),
            'last_message' => $this->when($this->relationLoaded('latestMessage') && $this->latestMessage->isNotEmpty(), function () {
                $message = $this->latestMessage->first();
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'type' => $message->type,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                    ],
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            }),
            'unread_count' => $this->unread_count ?? $this->getUnreadCountFor($userId),
            'is_muted' => $participant?->is_muted ?? false,
            'is_pinned' => $participant?->is_pinned ?? false,
            'last_read_at' => $participant?->last_read_at?->toIso8601String(),
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
