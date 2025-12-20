<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\EditMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function conversations(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $perPage = $request->query('per_page', 20);

        $conversations = $this->chatService->getConversationsForUser(
            auth()->id(),
            $type,
            $perPage
        );

        return API::success(
            ConversationResource::collection($conversations),
            'Conversations retrieved successfully'
        );
    }

    public function personalConversations(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 20);

        $conversations = $this->chatService->getPersonalConversations(
            auth()->id(),
            $perPage
        );

        return API::success(
            ConversationResource::collection($conversations),
            'Personal conversations retrieved successfully'
        );
    }

    public function jamConversations(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 20);

        $conversations = $this->chatService->getJamConversations(
            auth()->id(),
            $perPage
        );

        return API::success(
            ConversationResource::collection($conversations),
            'Jam conversations retrieved successfully'
        );
    }

    public function startPersonalChat(Request $request, int $friendId): JsonResponse
    {
        $userId = auth()->id();

        if ($userId === $friendId) {
            return API::error('Cannot start a conversation with yourself', 400);
        }

        $conversation = $this->chatService->findOrCreatePersonalConversation($userId, $friendId);

        return API::success(
            new ConversationResource($conversation),
            'Conversation started successfully'
        );
    }

    public function startJamChat(int $jamId): JsonResponse
    {
        $conversation = $this->chatService->findOrCreateJamConversation($jamId, auth()->id());

        return API::success(
            new ConversationResource($conversation),
            'Jam conversation started successfully'
        );
    }

    public function show(int $conversationId): JsonResponse
    {
        $conversation = $this->chatService->getConversation($conversationId, auth()->id());

        if (!$conversation) {
            return API::error('Conversation not found or access denied', 404);
        }

        return API::success(
            new ConversationResource($conversation),
            'Conversation retrieved successfully'
        );
    }

    public function messages(Request $request, int $conversationId): JsonResponse
    {
        $perPage = $request->query('per_page', 50);
        $beforeId = $request->query('before_id');

        $messages = $this->chatService->getMessages(
            $conversationId,
            auth()->id(),
            $perPage,
            $beforeId
        );

        if ($messages === null) {
            return API::error('Conversation not found or access denied', 404);
        }

        return API::success(
            MessageResource::collection($messages),
            'Messages retrieved successfully'
        );
    }

    public function sendMessage(SendMessageRequest $request, int $conversationId): JsonResponse
    {
        $message = $this->chatService->sendMessage(
            $conversationId,
            auth()->id(),
            $request->validated()
        );

        if (!$message) {
            return API::error('Failed to send message', 400);
        }

        return API::success(
            new MessageResource($message),
            'Message sent successfully',
            201
        );
    }

    public function editMessage(EditMessageRequest $request, int $messageId): JsonResponse
    {
        $message = $this->chatService->editMessage(
            $messageId,
            auth()->id(),
            $request->validated()['body']
        );

        if (!$message) {
            return API::error('Message not found or you cannot edit this message', 404);
        }

        return API::success(
            new MessageResource($message),
            'Message edited successfully'
        );
    }

    public function deleteMessage(int $messageId): JsonResponse
    {
        $deleted = $this->chatService->deleteMessage($messageId, auth()->id());

        if (!$deleted) {
            return API::error('Message not found or you cannot delete this message', 404);
        }

        return API::success(null, 'Message deleted successfully');
    }

    public function markAsRead(Request $request, int $conversationId): JsonResponse
    {
        $messageIds = $request->input('message_ids');

        $readMessageIds = $this->chatService->markMessagesAsRead(
            $conversationId,
            auth()->id(),
            $messageIds
        );

        return API::success(
            ['read_message_ids' => $readMessageIds],
            'Messages marked as read'
        );
    }

    public function typing(Request $request, int $conversationId): JsonResponse
    {
        $isTyping = $request->input('is_typing', true);

        $this->chatService->sendTypingIndicator(
            $conversationId,
            auth()->id(),
            $isTyping
        );

        return API::success(null, 'Typing indicator sent');
    }

    public function toggleMute(int $conversationId): JsonResponse
    {
        $isMuted = $this->chatService->toggleMute($conversationId, auth()->id());

        return API::success(
            ['is_muted' => $isMuted],
            $isMuted ? 'Conversation muted' : 'Conversation unmuted'
        );
    }

    public function togglePin(int $conversationId): JsonResponse
    {
        $isPinned = $this->chatService->togglePin($conversationId, auth()->id());

        return API::success(
            ['is_pinned' => $isPinned],
            $isPinned ? 'Conversation pinned' : 'Conversation unpinned'
        );
    }

    public function searchMessages(Request $request, int $conversationId): JsonResponse
    {
        $request->validate(['query' => 'required|string|min:1']);

        $messages = $this->chatService->searchMessages(
            $conversationId,
            auth()->id(),
            $request->query('query'),
            $request->query('per_page', 20)
        );

        if ($messages === null) {
            return API::error('Conversation not found or access denied', 404);
        }

        return API::success(
            MessageResource::collection($messages),
            'Search results'
        );
    }

    public function leaveConversation(int $conversationId): JsonResponse
    {
        $left = $this->chatService->removeParticipantFromConversation(
            $conversationId,
            auth()->id()
        );

        if (!$left) {
            return API::error('Failed to leave conversation', 400);
        }

        return API::success(null, 'Left conversation successfully');
    }

    public function addParticipant(Request $request, int $conversationId): JsonResponse
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $added = $this->chatService->addParticipantToConversation(
            $conversationId,
            $request->input('user_id')
        );

        if (!$added) {
            return API::error('Failed to add participant', 400);
        }

        return API::success(null, 'Participant added successfully');
    }
}
