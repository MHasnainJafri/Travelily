# Travelily Chat API - Flutter Integration Guide

## Overview
This document provides complete API documentation for integrating the real-time chat feature into the Travelily Flutter app. The chat system supports:
- **Personal chats** (1-on-1 with friends)
- **Jam group chats** (group conversations tied to Jam/trips)
- **Real-time messaging** via Pusher (WebSocket)
- **Media messages** (images, videos, audio, files, voice notes, location)

---

## Authentication
All API endpoints require Bearer token authentication.

```
Authorization: Bearer {access_token}
```

---

## Base URLs
| Environment | Base URL |
|-------------|----------|
| Development | `http://localhost:8000/api/v1` |
| Production | `https://your-domain.com/api/v1` |

---

## Pusher Configuration
```dart
// Pusher credentials (get from backend team)
const String PUSHER_APP_KEY = "your_pusher_key";
const String PUSHER_CLUSTER = "mt1"; // or your cluster
const String PUSHER_AUTH_URL = "https://your-domain.com/api/broadcasting/auth";
```

### Recommended Flutter Packages
```yaml
dependencies:
  pusher_channels_flutter: ^2.2.1  # For real-time WebSocket
  dio: ^5.0.0                       # HTTP client
  cached_network_image: ^3.3.0      # Image caching
  audio_waveforms: ^1.0.0           # Voice note visualization
  image_picker: ^1.0.0              # Media selection
  file_picker: ^6.0.0               # File selection
  geolocator: ^10.0.0               # Location
  intl: ^0.18.0                     # Date formatting
```

---

## Data Models

### Conversation Model
```dart
class Conversation {
  final int id;
  final String type; // 'personal' or 'jam'
  final String? name;
  final String? image;
  final int? jamId;
  final JamInfo? jam;
  final List<Participant> participants;
  final int participantsCount;
  final Participant? otherParticipant; // For personal chats
  final Message? lastMessage;
  final int unreadCount;
  final bool isMuted;
  final bool isPinned;
  final DateTime? lastReadAt;
  final DateTime? lastMessageAt;
  final DateTime createdAt;
}

class Participant {
  final int id;
  final String name;
  final String? username;
  final String? profilePhoto;
  final String role; // 'admin' or 'member'
}

class JamInfo {
  final int id;
  final String name;
  final String? destination;
  final DateTime? startDate;
  final DateTime? endDate;
}
```

### Message Model
```dart
class Message {
  final int id;
  final int conversationId;
  final String type; // 'text', 'image', 'video', 'audio', 'file', 'location', 'system'
  final String? body;
  final Map<String, dynamic>? metadata;
  final Sender sender;
  final bool isOwnMessage;
  final Message? replyTo;
  final bool isEdited;
  final DateTime? editedAt;
  final List<ReadReceipt>? readBy;
  final int? readCount;
  final DateTime createdAt;
  final DateTime updatedAt;
}

class Sender {
  final int id;
  final String name;
  final String? username;
  final String? profilePhoto;
}

class ReadReceipt {
  final int userId;
  final DateTime readAt;
}
```

### Message Metadata Structures
```dart
// Image metadata
class ImageMetadata {
  final String url;
  final String? filename;
  final int? filesize;
  final String? mimetype;
  final int? width;
  final int? height;
}

// Video metadata
class VideoMetadata {
  final String url;
  final String? filename;
  final int? filesize;
  final String? mimetype;
  final int? duration; // seconds
  final String? thumbnail;
}

// Audio/Voice note metadata
class AudioMetadata {
  final String url;
  final String? filename;
  final int? filesize;
  final String? mimetype;
  final int? duration; // seconds
  final bool? isVoiceNote;
}

// File metadata
class FileMetadata {
  final String url;
  final String filename;
  final int? filesize;
  final String? mimetype;
}

// Location metadata
class LocationMetadata {
  final double latitude;
  final double longitude;
  final String? address;
}
```

---

## API Endpoints

### 1. Conversations

#### Get All Conversations
```
GET /chat/conversations?type={type}&per_page={per_page}
```
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| type | string | No | Filter: `personal` or `jam` |
| per_page | int | No | Default: 20 |

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "personal",
      "name": "Jane Cooper",
      "image": "https://...",
      "participants_count": 2,
      "other_participant": {
        "id": 2,
        "name": "Jane Cooper",
        "username": "janecooper",
        "profile_photo": "https://..."
      },
      "last_message": {
        "id": 45,
        "body": "Hey, long time no see!",
        "type": "text",
        "sender": {"id": 2, "name": "Jane Cooper"},
        "created_at": "2025-12-13T15:30:00Z"
      },
      "unread_count": 3,
      "is_muted": false,
      "is_pinned": false,
      "last_message_at": "2025-12-13T15:30:00Z"
    }
  ],
  "message": "Conversations retrieved successfully"
}
```

#### Get Personal Conversations Only
```
GET /chat/conversations/personal?per_page={per_page}
```

#### Get Jam Conversations Only
```
GET /chat/conversations/jam?per_page={per_page}
```

#### Get Single Conversation Details
```
GET /chat/conversations/{conversationId}
```

#### Start Personal Chat
```
POST /chat/conversations/personal/{friendId}
```
Creates or returns existing conversation with a friend.

#### Start/Join Jam Group Chat
```
POST /chat/conversations/jam/{jamId}
```
Creates or joins the group chat for a Jam. All Jam members are auto-added.

#### Mute/Unmute Conversation
```
POST /chat/conversations/{conversationId}/mute
```
**Response:**
```json
{
  "success": true,
  "data": {"is_muted": true},
  "message": "Conversation muted"
}
```

#### Pin/Unpin Conversation
```
POST /chat/conversations/{conversationId}/pin
```

#### Leave Conversation (Group only)
```
POST /chat/conversations/{conversationId}/leave
```

#### Add Participant (Group only)
```
POST /chat/conversations/{conversationId}/participants
Content-Type: application/json

{"user_id": 5}
```

---

### 2. Messages

#### Get Messages (with pagination)
```
GET /chat/conversations/{conversationId}/messages?per_page={per_page}&before_id={before_id}
```
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | int | No | Default: 50 |
| before_id | int | No | For infinite scroll - load messages before this ID |

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "conversation_id": 1,
      "type": "text",
      "body": "Hey, long time no see! 👋",
      "metadata": null,
      "sender": {
        "id": 2,
        "name": "Jane Cooper",
        "username": "janecooper",
        "profile_photo": "https://..."
      },
      "is_own_message": false,
      "reply_to": null,
      "is_edited": false,
      "read_by": [
        {"user_id": 1, "read_at": "2025-12-13T15:31:00Z"}
      ],
      "read_count": 1,
      "created_at": "2025-12-13T15:30:00Z"
    }
  ]
}
```

#### Send Text Message
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "text",
  "body": "Hey, long time no see! 👋"
}
```

#### Send Image Message
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "image",
  "body": "Check out this view!",
  "metadata": {
    "url": "https://your-domain.com/storage/chat/images/photo.jpg",
    "filename": "vacation_photo.jpg",
    "filesize": 1024000,
    "mimetype": "image/jpeg",
    "width": 1920,
    "height": 1080
  }
}
```

#### Send Video Message
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "video",
  "body": "Video from the trip",
  "metadata": {
    "url": "https://your-domain.com/storage/chat/videos/video.mp4",
    "filename": "trip_video.mp4",
    "filesize": 15000000,
    "mimetype": "video/mp4",
    "duration": 45,
    "thumbnail": "https://your-domain.com/storage/chat/videos/video_thumb.jpg"
  }
}
```

#### Send Voice Note / Audio
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "audio",
  "metadata": {
    "url": "https://your-domain.com/storage/chat/voice-notes/audio.m4a",
    "filename": "voice_note.m4a",
    "filesize": 250000,
    "mimetype": "audio/mp4",
    "duration": 15,
    "is_voice_note": true
  }
}
```

#### Send File
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "file",
  "body": "Here's the itinerary",
  "metadata": {
    "url": "https://your-domain.com/storage/chat/files/itinerary.pdf",
    "filename": "Trip_Itinerary.pdf",
    "filesize": 500000,
    "mimetype": "application/pdf"
  }
}
```

#### Send Location
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "location",
  "body": "I'm here!",
  "metadata": {
    "latitude": 48.8584,
    "longitude": 2.2945,
    "address": "Eiffel Tower, Paris, France"
  }
}
```

#### Reply to a Message
```
POST /chat/conversations/{conversationId}/messages
Content-Type: application/json

{
  "type": "text",
  "body": "Yes, I'll be there!",
  "reply_to_id": 5
}
```

#### Edit Message
```
PUT /chat/messages/{messageId}
Content-Type: application/json

{"body": "Updated message content"}
```

#### Delete Message
```
DELETE /chat/messages/{messageId}
```

#### Search Messages
```
GET /chat/conversations/{conversationId}/search?query={query}&per_page={per_page}
```

---

### 3. Real-time Actions

#### Mark Messages as Read
```
POST /chat/conversations/{conversationId}/read
Content-Type: application/json

{"message_ids": [1, 2, 3]}
```
Send empty/null `message_ids` to mark ALL unread messages as read.

#### Send Typing Indicator
```
POST /chat/conversations/{conversationId}/typing
Content-Type: application/json

{"is_typing": true}
```
Call with `false` when user stops typing.

---

### 4. Media Upload

#### Upload Media File (Image/Video/Audio/File)
```
POST /chat/upload
Content-Type: multipart/form-data

file: <binary>
type: "image" | "video" | "audio" | "file"
```

**Supported formats:**
| Type | Formats | Max Size |
|------|---------|----------|
| image | jpeg, png, gif, webp | 50MB |
| video | mp4, quicktime, webm, avi | 50MB |
| audio | mpeg, wav, ogg, webm, mp4 | 50MB |
| file | pdf, doc, docx, xls, xlsx, zip, txt | 50MB |

**Response:**
```json
{
  "success": true,
  "data": {
    "type": "image",
    "metadata": {
      "url": "https://your-domain.com/storage/chat/images/abc123.jpg",
      "filename": "photo.jpg",
      "filesize": 1024000,
      "mimetype": "image/jpeg",
      "width": 1920,
      "height": 1080
    }
  }
}
```

#### Upload Voice Note
```
POST /chat/upload/voice-note
Content-Type: multipart/form-data

audio: <binary>
duration: 15
```
Max size: 10MB. Formats: wav, mp3, m4a, ogg, webm

---

## Pusher Real-time Integration

### Channel Authentication
```
POST /api/broadcasting/auth
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {access_token}

socket_id={socket_id}&channel_name={channel_name}
```

### Channels to Subscribe

| Channel | Purpose |
|---------|---------|
| `private-conversation.{conversationId}` | Messages in a specific conversation |
| `private-user.{userId}.conversations` | User's conversation list updates |
| `presence-online` | Online/offline status of users |

### Events to Listen

#### On `private-conversation.{id}` channel:
| Event | Payload | Description |
|-------|---------|-------------|
| `message.sent` | Message object | New message received |
| `message.read` | `{user, message_ids, read_at}` | Messages marked as read |
| `user.typing` | `{user, is_typing}` | User typing indicator |
| `participant.joined` | `{user, joined_at}` | User joined group |
| `participant.left` | `{user, left_at}` | User left group |

#### On `private-user.{id}.conversations` channel:
| Event | Payload | Description |
|-------|---------|-------------|
| `conversation.created` | Conversation object | New conversation started |
| `conversation.updated` | Conversation object | Conversation details changed |

#### On `presence-online` channel:
| Event | Payload | Description |
|-------|---------|-------------|
| `user.status` | `{user_id, is_online, last_seen}` | User online status |

---

## Flutter Implementation Example

### Pusher Setup
```dart
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

class PusherService {
  late PusherChannelsFlutter pusher;
  
  Future<void> init(String accessToken) async {
    pusher = PusherChannelsFlutter.getInstance();
    
    await pusher.init(
      apiKey: PUSHER_APP_KEY,
      cluster: PUSHER_CLUSTER,
      onAuthorizer: (channelName, socketId, options) async {
        // Call your backend to authenticate
        final response = await dio.post(
          '$BASE_URL/../broadcasting/auth',
          data: {
            'socket_id': socketId,
            'channel_name': channelName,
          },
          options: Options(headers: {
            'Authorization': 'Bearer $accessToken',
            'Content-Type': 'application/x-www-form-urlencoded',
          }),
        );
        return response.data;
      },
    );
    
    await pusher.connect();
  }
  
  Future<void> subscribeToConversation(int conversationId) async {
    final channel = await pusher.subscribe(
      channelName: 'private-conversation.$conversationId',
    );
    
    channel.bind('message.sent', (event) {
      final message = Message.fromJson(jsonDecode(event.data));
      // Handle new message
    });
    
    channel.bind('user.typing', (event) {
      final data = jsonDecode(event.data);
      // Show typing indicator
    });
    
    channel.bind('message.read', (event) {
      final data = jsonDecode(event.data);
      // Update read receipts
    });
  }
}
```

### Chat Repository Example
```dart
class ChatRepository {
  final Dio _dio;
  
  Future<List<Conversation>> getConversations({String? type}) async {
    final response = await _dio.get('/chat/conversations', 
      queryParameters: {'type': type});
    return (response.data['data'] as List)
      .map((e) => Conversation.fromJson(e)).toList();
  }
  
  Future<Conversation> startPersonalChat(int friendId) async {
    final response = await _dio.post('/chat/conversations/personal/$friendId');
    return Conversation.fromJson(response.data['data']);
  }
  
  Future<List<Message>> getMessages(int conversationId, {int? beforeId}) async {
    final response = await _dio.get(
      '/chat/conversations/$conversationId/messages',
      queryParameters: {'before_id': beforeId},
    );
    return (response.data['data'] as List)
      .map((e) => Message.fromJson(e)).toList();
  }
  
  Future<Message> sendMessage(int conversationId, {
    required String type,
    String? body,
    Map<String, dynamic>? metadata,
    int? replyToId,
  }) async {
    final response = await _dio.post(
      '/chat/conversations/$conversationId/messages',
      data: {
        'type': type,
        'body': body,
        'metadata': metadata,
        'reply_to_id': replyToId,
      },
    );
    return Message.fromJson(response.data['data']);
  }
  
  Future<Map<String, dynamic>> uploadMedia(File file, String type) async {
    final formData = FormData.fromMap({
      'file': await MultipartFile.fromFile(file.path),
      'type': type,
    });
    final response = await _dio.post('/chat/upload', data: formData);
    return response.data['data']['metadata'];
  }
  
  Future<void> sendTypingIndicator(int conversationId, bool isTyping) async {
    await _dio.post('/chat/conversations/$conversationId/typing',
      data: {'is_typing': isTyping});
  }
  
  Future<void> markAsRead(int conversationId, {List<int>? messageIds}) async {
    await _dio.post('/chat/conversations/$conversationId/read',
      data: {'message_ids': messageIds});
  }
}
```

---

## UI Requirements (Based on Design)

### Chat List Screen
- Two tabs: **Personal** and **Jam**
- Each conversation shows:
  - Avatar (user photo for personal, jam image for group)
  - Name
  - Last message preview (with "typing..." indicator support)
  - Timestamp
  - Unread count badge
  - Double-check icon for read status
- Floating action button to start new chat

### Chat Screen
- Header: Name, online status, call/video buttons
- Message bubbles:
  - Own messages: Right-aligned, pink/coral color
  - Other messages: Left-aligned, white/light color
- Support for:
  - Text messages
  - Image messages (tappable to view full)
  - Voice messages (with waveform and play button)
  - Video messages (with thumbnail and play button)
  - File messages (with download button)
  - Location messages (with map preview)
  - Reply quotes
- Message input:
  - Text field
  - Attachment button (camera, gallery, file, location)
  - Voice record button
  - Send button
- Typing indicator ("Jane is typing...")
- Read receipts (double check marks)

---

## Error Handling

All API errors return:
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Validation error"]
  }
}
```

| HTTP Code | Meaning |
|-----------|---------|
| 400 | Bad request / Validation error |
| 401 | Unauthorized - Invalid/expired token |
| 403 | Forbidden - No access to resource |
| 404 | Not found |
| 422 | Validation failed |
| 500 | Server error |

---

## Best Practices

1. **Pagination**: Use `before_id` for message pagination (infinite scroll)
2. **Caching**: Cache conversation list and messages locally
3. **Optimistic UI**: Show sent messages immediately, update on confirmation
4. **Debounce typing**: Send typing indicator max every 2-3 seconds
5. **Mark as read**: Call when conversation is opened/visible
6. **Connection handling**: Reconnect Pusher on network restore
7. **Background sync**: Refresh conversation list when app resumes

---

## Postman Collection
Import the provided `Chat API.postman_collection.json` file for testing all endpoints.
