<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'last_read_at',
        'is_muted',
        'is_pinned',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    const ROLE_ADMIN = 'admin';
    const ROLE_MEMBER = 'member';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isActive(): bool
    {
        return is_null($this->left_at);
    }

    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }

    public function toggleMute(): void
    {
        $this->update(['is_muted' => !$this->is_muted]);
    }

    public function togglePin(): void
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
    }

    public function leave(): void
    {
        $this->update(['left_at' => now()]);
    }
}
