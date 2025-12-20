<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'body',
        'metadata',
        'reply_to_id',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';
    const TYPE_LOCATION = 'location';
    const TYPE_SYSTEM = 'system';

    protected static function booted(): void
    {
        static::created(function (Message $message) {
            $message->conversation->update(['last_message_at' => $message->created_at]);
        });
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(MessageRead::class);
    }

    public function readBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_reads')
            ->withPivot('read_at');
    }

    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    public function markAsReadBy(int $userId): void
    {
        if (!$this->isReadBy($userId) && $this->sender_id !== $userId) {
            $this->reads()->create([
                'user_id' => $userId,
                'read_at' => now(),
            ]);
        }
    }

    public function edit(string $newBody): void
    {
        $this->update([
            'body' => $newBody,
            'is_edited' => true,
            'edited_at' => now(),
        ]);
    }

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isMedia(): bool
    {
        return in_array($this->type, [self::TYPE_IMAGE, self::TYPE_VIDEO, self::TYPE_AUDIO]);
    }

    public function isSystem(): bool
    {
        return $this->type === self::TYPE_SYSTEM;
    }

    public static function createSystemMessage(int $conversationId, string $body, array $metadata = []): self
    {
        return self::create([
            'conversation_id' => $conversationId,
            'sender_id' => auth()->id(),
            'type' => self::TYPE_SYSTEM,
            'body' => $body,
            'metadata' => $metadata,
        ]);
    }
}
