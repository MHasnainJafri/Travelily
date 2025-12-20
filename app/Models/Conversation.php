<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'jam_id',
        'name',
        'image',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    const TYPE_PERSONAL = 'personal';
    const TYPE_JAM = 'jam';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jam(): BelongsTo
    {
        return $this->belongsTo(Jam::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'last_read_at', 'is_muted', 'is_pinned', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function activeParticipants(): BelongsToMany
    {
        return $this->participants()->whereNull('conversation_participants.left_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    public function participantRecords(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function isPersonal(): bool
    {
        return $this->type === self::TYPE_PERSONAL;
    }

    public function isJam(): bool
    {
        return $this->type === self::TYPE_JAM;
    }

    public function hasParticipant(int $userId): bool
    {
        return $this->activeParticipants()->where('users.id', $userId)->exists();
    }

    public function getUnreadCountFor(int $userId): int
    {
        $participant = $this->participantRecords()->where('user_id', $userId)->first();
        
        if (!$participant || !$participant->last_read_at) {
            return $this->messages()->where('sender_id', '!=', $userId)->count();
        }

        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('created_at', '>', $participant->last_read_at)
            ->count();
    }

    public function getOtherParticipant(int $userId): ?User
    {
        if (!$this->isPersonal()) {
            return null;
        }

        return $this->activeParticipants()
            ->where('users.id', '!=', $userId)
            ->first();
    }

    public static function findPersonalConversation(int $userId1, int $userId2): ?self
    {
        return self::where('type', self::TYPE_PERSONAL)
            ->whereHas('activeParticipants', function ($query) use ($userId1) {
                $query->where('users.id', $userId1);
            })
            ->whereHas('activeParticipants', function ($query) use ($userId2) {
                $query->where('users.id', $userId2);
            })
            ->first();
    }

    public static function findOrCreateJamConversation(int $jamId, int $creatorId): self
    {
        $conversation = self::where('type', self::TYPE_JAM)
            ->where('jam_id', $jamId)
            ->first();

        if (!$conversation) {
            $jam = Jam::findOrFail($jamId);
            $conversation = self::create([
                'type' => self::TYPE_JAM,
                'jam_id' => $jamId,
                'name' => $jam->name,
                'image' => $jam->image,
                'created_by' => $creatorId,
            ]);
        }

        return $conversation;
    }
}
