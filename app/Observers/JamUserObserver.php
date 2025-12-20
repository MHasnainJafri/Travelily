<?php

namespace App\Observers;

use App\Models\JamUser;
use App\Services\ChatService;

class JamUserObserver
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function created(JamUser $jamUser): void
    {
        $this->chatService->syncJamParticipants($jamUser->jam_id);
    }

    public function deleted(JamUser $jamUser): void
    {
        $this->chatService->syncJamParticipants($jamUser->jam_id);
    }
}
