<?php

namespace App\Services;

use App\Events\Chat\ConversationUpdated;
use App\Events\Chat\MessageRead;
use App\Events\Chat\MessageSent;
use App\Events\Chat\ParticipantJoined;
use App\Events\Chat\ParticipantLeft;
use App\Events\Chat\UserTyping;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Jam;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function getConversationsForUser(int $userId, ?string $type = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = Conversation::whereHas('activeParticipants', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
        ->with(['latestMessage.sender', 'activeParticipants'])
        ->withCount(['messages as unread_count' => function ($q) use ($userId) {
            $q->where('sender_id', '!=', $userId)
              ->whereDoesntHave('reads', function ($rq) use ($userId) {
                  $rq->where('user_id', $userId);
              });
        }]);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getPersonalConversations(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->getConversationsForUser($userId, Conversation::TYPE_PERSONAL, $perPage);
    }

    public function getJamConversations(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->getConversationsForUser($userId, Conversation::TYPE_JAM, $perPage);
    }

    public function findOrCreatePersonalConversation(int $userId, int $friendId): Conversation
    {
        $conversation = Conversation::findPersonalConversation($userId, $friendId);

        if (!$conversation) {
            $conversation = DB::transaction(function () use ($userId, $friendId) {
                $conversation = Conversation::create([
                    'type' => Conversation::TYPE_PERSONAL,
                    'created_by' => $userId,
                ]);

                $conversation->participants()->attach([
                    $userId => ['role' => ConversationParticipant::ROLE_MEMBER, 'joined_at' => now()],
                    $friendId => ['role' => ConversationParticipant::ROLE_MEMBER, 'joined_at' => now()],
                ]);

                return $conversation;
            });

            broadcast(new ConversationUpdated($conversation, 'created'))->toOthers();
        }

        return $conversation->load('activeParticipants');
    }

    public function findOrCreateJamConversation(int $jamId, int $userId): Conversation
    {
        $jam = Jam::with('users')->findOrFail($jamId);
        
        $conversation = Conversation::where('type', Conversation::TYPE_JAM)
            ->where('jam_id', $jamId)
            ->first();

        if (!$conversation) {
            $conversation = DB::transaction(function () use ($jam, $userId) {
                $conversation = Conversation::create([
                    'type' => Conversation::TYPE_JAM,
                    'jam_id' => $jam->id,
                    'name' => $jam->name,
                    'image' => $jam->image,
                    'created_by' => $userId,
                ]);

                $participants = [];
                foreach ($jam->users as $user) {
                    $participants[$user->id] = [
                        'role' => $user->id === $jam->creator_id 
                            ? ConversationParticipant::ROLE_ADMIN 
                            : ConversationParticipant::ROLE_MEMBER,
                        'joined_at' => now(),
                    ];
                }

                $conversation->participants()->attach($participants);

                return $conversation;
            });

            broadcast(new ConversationUpdated($conversation, 'created'))->toOthers();
        }

        return $conversation->load('activeParticipants');
    }

    public function getConversation(int $conversationId, int $userId): ?Conversation
    {
        $conversation = Conversation::with(['activeParticipants', 'jam'])
            ->find($conversationId);

        if (!$conversation || !$conversation->hasParticipant($userId)) {
            return null;
        }

        return $conversation;
    }

    public function getMessages(int $conversationId, int $userId, int $perPage = 50, ?int $beforeId = null): ?LengthAwarePaginator
    {
        $conversation = $this->getConversation($conversationId, $userId);
        
        if (!$conversation) {
            return null;
        }

        $query = Message::where('conversation_id', $conversationId)
            ->with(['sender', 'replyTo.sender', 'reads'])
            ->orderByDesc('created_at');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        return $query->paginate($perPage);
    }

    public function sendMessage(int $conversationId, int $senderId, array $data): ?Message
    {
        $conversation = $this->getConversation($conversationId, $senderId);
        
        if (!$conversation) {
            return null;
        }

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'type' => $data['type'] ?? Message::TYPE_TEXT,
            'body' => $data['body'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'reply_to_id' => $data['reply_to_id'] ?? null,
        ]);

        $message->load(['sender', 'replyTo.sender']);

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    public function editMessage(int $messageId, int $userId, string $newBody): ?Message
    {
        $message = Message::where('id', $messageId)
            ->where('sender_id', $userId)
            ->first();

        if (!$message) {
            return null;
        }

        $message->edit($newBody);
        
        return $message->fresh(['sender', 'replyTo.sender']);
    }

    public function deleteMessage(int $messageId, int $userId): bool
    {
        $message = Message::where('id', $messageId)
            ->where('sender_id', $userId)
            ->first();

        if (!$message) {
            return false;
        }

        return $message->delete();
    }

    public function markMessagesAsRead(int $conversationId, int $userId, ?array $messageIds = null): array
    {
        $conversation = $this->getConversation($conversationId, $userId);
        
        if (!$conversation) {
            return [];
        }

        $query = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->whereDoesntHave('reads', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        if ($messageIds) {
            $query->whereIn('id', $messageIds);
        }

        $messages = $query->get();
        $readMessageIds = [];

        foreach ($messages as $message) {
            $message->markAsReadBy($userId);
            $readMessageIds[] = $message->id;
        }

        $participant = $conversation->participantRecords()
            ->where('user_id', $userId)
            ->first();
        
        if ($participant) {
            $participant->markAsRead();
        }

        if (!empty($readMessageIds)) {
            broadcast(new MessageRead($conversationId, $userId, $readMessageIds))->toOthers();
        }

        return $readMessageIds;
    }

    public function sendTypingIndicator(int $conversationId, int $userId, bool $isTyping = true): void
    {
        $conversation = $this->getConversation($conversationId, $userId);
        
        if ($conversation) {
            $user = User::find($userId);
            broadcast(new UserTyping($conversationId, $user, $isTyping))->toOthers();
        }
    }

    public function addParticipantToConversation(int $conversationId, int $userId, string $role = 'member'): bool
    {
        $conversation = Conversation::find($conversationId);
        
        if (!$conversation || $conversation->hasParticipant($userId)) {
            return false;
        }

        $conversation->participants()->attach($userId, [
            'role' => $role,
            'joined_at' => now(),
        ]);

        $user = User::find($userId);
        broadcast(new ParticipantJoined($conversationId, $user))->toOthers();

        Message::createSystemMessage(
            $conversationId,
            "{$user->name} joined the conversation"
        );

        return true;
    }

    public function removeParticipantFromConversation(int $conversationId, int $userId): bool
    {
        $participant = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->first();

        if (!$participant) {
            return false;
        }

        $participant->leave();

        $user = User::find($userId);
        broadcast(new ParticipantLeft($conversationId, $user))->toOthers();

        Message::createSystemMessage(
            $conversationId,
            "{$user->name} left the conversation"
        );

        return true;
    }

    public function toggleMute(int $conversationId, int $userId): bool
    {
        $participant = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            return false;
        }

        $participant->toggleMute();
        return $participant->is_muted;
    }

    public function togglePin(int $conversationId, int $userId): bool
    {
        $participant = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            return false;
        }

        $participant->togglePin();
        return $participant->is_pinned;
    }

    public function searchMessages(int $conversationId, int $userId, string $query, int $perPage = 20): ?LengthAwarePaginator
    {
        $conversation = $this->getConversation($conversationId, $userId);
        
        if (!$conversation) {
            return null;
        }

        return Message::where('conversation_id', $conversationId)
            ->where('body', 'like', "%{$query}%")
            ->with(['sender'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function syncJamParticipants(int $jamId): void
    {
        $conversation = Conversation::where('type', Conversation::TYPE_JAM)
            ->where('jam_id', $jamId)
            ->first();

        if (!$conversation) {
            return;
        }

        $jam = Jam::with('users')->find($jamId);
        
        if (!$jam) {
            return;
        }

        $currentParticipantIds = $conversation->activeParticipants()->pluck('users.id')->toArray();
        $jamUserIds = $jam->users->pluck('id')->toArray();

        $toAdd = array_diff($jamUserIds, $currentParticipantIds);
        $toRemove = array_diff($currentParticipantIds, $jamUserIds);

        foreach ($toAdd as $userId) {
            $this->addParticipantToConversation($conversation->id, $userId);
        }

        foreach ($toRemove as $userId) {
            $this->removeParticipantFromConversation($conversation->id, $userId);
        }
    }
}
