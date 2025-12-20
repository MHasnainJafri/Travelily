<?php

namespace App\Observers;

use App\Models\Jam;
use App\Models\JamUser;
use App\Services\ChatService;

class JamObserver
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function created(Jam $jam): void
    {
        $this->chatService->findOrCreateJamConversation($jam->id, $jam->creator_id);
    }
}
