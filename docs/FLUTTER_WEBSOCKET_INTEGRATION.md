# Travelilly Chat WebSocket Integration for Flutter

## Overview

This document explains how to integrate WebSocket real-time features with your Flutter app using Pusher (or compatible services like Laravel Reverb).

---

## Configuration

### Server Configuration

The backend uses **Pusher** as the default broadcast driver. Configure these environment variables on the server:

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

### Flutter Dependencies

Add the `pusher_channels_flutter` package to your `pubspec.yaml`:

```yaml
dependencies:
  pusher_channels_flutter: ^2.2.1
```

---

## Authentication

Before subscribing to private channels, authenticate with your API token via:

```
POST {{base_url}}/broadcasting/auth
Headers:
  Authorization: Bearer {token}
Body:
  socket_id: {socket_id}
  channel_name: {channel_name}
```

---

## Channels

### 1. Private Conversation Channel
**Channel Name:** `private-conversation.{conversationId}`

Used for all message-related events within a specific conversation.

**Authorization:** User must be a participant in the conversation.

---

### 2. User Conversations Channel
**Channel Name:** `private-user.{userId}.conversations`

Used for conversation-level updates (new conversations, updates).

**Authorization:** User can only subscribe to their own channel.

---

### 3. Presence Online Channel
**Channel Name:** `presence-online`

Used to track online users in real-time.

**Authorization:** Any authenticated user.

---

### 4. Jam Channel
**Channel Name:** `private-jam.{jamId}`

Used for jam-specific events.

**Authorization:** User must be a member of the jam.

---

## Events Reference

### Message Events (on `private-conversation.{conversationId}`)

#### 1. `message.sent`
Triggered when a new message is sent.

```json
{
  "id": 123,
  "conversation_id": 1,
  "sender": {
    "id": 6,
    "name": "John Doe",
    "username": "johndoe",
    "profile_photo": "profile_photo/abc.png"
  },
  "type": "text",
  "body": "Hello, how are you?",
  "metadata": null,
  "reply_to": null,
  "created_at": "2025-12-14T10:06:41+00:00"
}
```

#### 2. `message.read`
Triggered when messages are marked as read.

```json
{
  "conversation_id": 1,
  "user": {
    "id": 6,
    "name": "John Doe"
  },
  "message_ids": [120, 121, 122],
  "read_at": "2025-12-14T10:10:00+00:00"
}
```

#### 3. `user.typing`
Triggered when a user starts/stops typing.

```json
{
  "conversation_id": 1,
  "user": {
    "id": 6,
    "name": "John Doe",
    "username": "johndoe"
  },
  "is_typing": true
}
```

#### 4. `participant.joined`
Triggered when a user joins a conversation.

```json
{
  "conversation_id": 1,
  "user": {
    "id": 7,
    "name": "Jane Smith",
    "username": "janesmith",
    "profile_photo": "profile_photo/xyz.png"
  },
  "joined_at": "2025-12-14T10:15:00+00:00"
}
```

#### 5. `participant.left`
Triggered when a user leaves a conversation.

```json
{
  "conversation_id": 1,
  "user": {
    "id": 7,
    "name": "Jane Smith"
  },
  "left_at": "2025-12-14T10:20:00+00:00"
}
```

---

### Conversation Events (on `private-user.{userId}.conversations`)

#### 1. `conversation.created`
Triggered when a new conversation is created.

```json
{
  "conversation_id": 5,
  "type": "personal",
  "name": null,
  "image": null,
  "last_message_at": null,
  "action": "created"
}
```

#### 2. `conversation.updated`
Triggered when a conversation is updated.

```json
{
  "conversation_id": 5,
  "type": "jam",
  "name": "Trip to Paris",
  "image": "conversations/paris.jpg",
  "last_message_at": "2025-12-14T10:30:00+00:00",
  "action": "updated"
}
```

---

### Online Status Events (on `presence-online`)

#### 1. `user.status`
Triggered when a user comes online/offline.

```json
{
  "user_id": 6,
  "is_online": true,
  "last_seen": "2025-12-14T10:00:00+00:00"
}
```

---

## Flutter Implementation

### 1. Initialize Pusher

```dart
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

class PusherService {
  late PusherChannelsFlutter pusher;
  final String apiToken;
  final String baseUrl;

  PusherService({required this.apiToken, required this.baseUrl});

  Future<void> init() async {
    pusher = PusherChannelsFlutter.getInstance();

    await pusher.init(
      apiKey: 'YOUR_PUSHER_APP_KEY',
      cluster: 'mt1',
      onConnectionStateChange: (currentState, previousState) {
        print('Connection: $previousState -> $currentState');
      },
      onError: (message, code, error) {
        print('Pusher Error: $message');
      },
      onAuthorizer: (channelName, socketId, options) async {
        // Authenticate private/presence channels
        final response = await http.post(
          Uri.parse('$baseUrl/broadcasting/auth'),
          headers: {
            'Authorization': 'Bearer $apiToken',
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: {
            'socket_id': socketId,
            'channel_name': channelName,
          },
        );
        return jsonDecode(response.body);
      },
    );

    await pusher.connect();
  }
}
```

### 2. Subscribe to Conversation Channel

```dart
Future<void> subscribeToConversation(int conversationId) async {
  final channelName = 'private-conversation.$conversationId';

  await pusher.subscribe(
    channelName: channelName,
    onEvent: (event) {
      print('Event: ${event.eventName}');
      print('Data: ${event.data}');

      switch (event.eventName) {
        case 'message.sent':
          _handleNewMessage(jsonDecode(event.data));
          break;
        case 'message.read':
          _handleMessageRead(jsonDecode(event.data));
          break;
        case 'user.typing':
          _handleTyping(jsonDecode(event.data));
          break;
        case 'participant.joined':
          _handleParticipantJoined(jsonDecode(event.data));
          break;
        case 'participant.left':
          _handleParticipantLeft(jsonDecode(event.data));
          break;
      }
    },
  );
}

void _handleNewMessage(Map<String, dynamic> data) {
  // Add message to local list, update UI
  print('New message from ${data['sender']['name']}: ${data['body']}');
}

void _handleTyping(Map<String, dynamic> data) {
  // Show/hide typing indicator
  final isTyping = data['is_typing'] as bool;
  final userName = data['user']['name'];
  print('$userName is ${isTyping ? 'typing...' : 'stopped typing'}');
}

void _handleMessageRead(Map<String, dynamic> data) {
  // Update read receipts in UI
  final messageIds = List<int>.from(data['message_ids']);
  print('Messages read: $messageIds');
}
```

### 3. Subscribe to User Conversations

```dart
Future<void> subscribeToUserConversations(int userId) async {
  final channelName = 'private-user.$userId.conversations';

  await pusher.subscribe(
    channelName: channelName,
    onEvent: (event) {
      final data = jsonDecode(event.data);
      
      switch (event.eventName) {
        case 'conversation.created':
          // Add new conversation to list
          print('New conversation: ${data['conversation_id']}');
          break;
        case 'conversation.updated':
          // Update conversation in list
          print('Conversation updated: ${data['conversation_id']}');
          break;
      }
    },
  );
}
```

### 4. Subscribe to Online Presence

```dart
Future<void> subscribeToOnlineStatus() async {
  await pusher.subscribe(
    channelName: 'presence-online',
    onEvent: (event) {
      if (event.eventName == 'user.status') {
        final data = jsonDecode(event.data);
        print('User ${data['user_id']} is ${data['is_online'] ? 'online' : 'offline'}');
      }
    },
    onMemberAdded: (member) {
      print('User came online: ${member.userId}');
    },
    onMemberRemoved: (member) {
      print('User went offline: ${member.userId}');
    },
  );
}
```

### 5. Unsubscribe

```dart
Future<void> unsubscribeFromConversation(int conversationId) async {
  await pusher.unsubscribe(channelName: 'private-conversation.$conversationId');
}

Future<void> disconnect() async {
  await pusher.disconnect();
}
```

---

## API Endpoints for Chat

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/chat/conversations` | List all conversations |
| POST | `/chat/conversations/personal` | Create/get personal conversation |
| GET | `/chat/conversations/{id}` | Get conversation details |
| GET | `/chat/conversations/{id}/messages` | Get messages (paginated) |
| POST | `/chat/conversations/{id}/messages` | Send message |
| PUT | `/chat/messages/{id}` | Edit message |
| DELETE | `/chat/messages/{id}` | Delete message |
| POST | `/chat/conversations/{id}/read` | Mark messages as read |
| POST | `/chat/conversations/{id}/typing` | Send typing indicator |
| POST | `/chat/conversations/{id}/mute` | Toggle mute |
| POST | `/chat/conversations/{id}/pin` | Toggle pin |

---

## Message Types

| Type | Description |
|------|-------------|
| `text` | Plain text message |
| `image` | Image attachment |
| `video` | Video attachment |
| `audio` | Audio/voice message |
| `file` | File attachment |
| `location` | Location share |
| `system` | System message (join/leave) |

---

## Quick Reference Table

| Channel | Event | Description |
|---------|-------|-------------|
| `private-conversation.{id}` | `message.sent` | New message received |
| `private-conversation.{id}` | `message.read` | Messages marked as read |
| `private-conversation.{id}` | `user.typing` | User typing status |
| `private-conversation.{id}` | `participant.joined` | User joined conversation |
| `private-conversation.{id}` | `participant.left` | User left conversation |
| `private-user.{id}.conversations` | `conversation.created` | New conversation |
| `private-user.{id}.conversations` | `conversation.updated` | Conversation updated |
| `presence-online` | `user.status` | User online/offline |

---

## Notes

1. **Private channels** require authentication via `/broadcasting/auth`
2. **Presence channels** also return member info (who's subscribed)
3. All events use **Laravel Echo naming**: `eventName` without the class prefix
4. Timestamps are in **ISO 8601** format
5. Use `toOthers()` on server - sender doesn't receive their own broadcast
