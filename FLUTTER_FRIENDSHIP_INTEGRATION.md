# Travelily Friendship & Follow API - Flutter Integration Guide

## Overview
This document provides complete API documentation for integrating the friendship and follow system into the Travelily Flutter app.

### Key Concepts
- **Friend**: Mutual relationship between regular users (requires accept/reject)
- **Follow**: One-way relationship for Hosts/Guides (no approval needed for direct follow)
- **Auto-detection**: When sending a request, the system auto-detects if target is Host/Guide → creates follow request instead of friend request

---

## Authentication
All API endpoints require Bearer token authentication.

```
Authorization: Bearer {access_token}
```

---

## Base URL
```
Development: http://localhost:8000/api/v1
Production: https://your-domain.com/api/v1
```

---

## Data Models

### Friendship/Follow Request Model
```dart
class FriendshipRequest {
  final int id;
  final String type; // 'friend' or 'follow'
  final String status; // 'pending', 'accepted', 'rejected'
  final bool isSender; // true if current user sent the request
  final UserInfo user; // The other user (sender if received, receiver if sent)
  final DateTime createdAt;
  final DateTime updatedAt;
}

class UserInfo {
  final int id;
  final String name;
  final String? username;
  final String? profilePhoto;
  final List<String> roles; // ['user'], ['host'], ['guide'], etc.
}
```

### Relationship Status Model
```dart
class RelationshipStatus {
  final String status; // 'none', 'pending', 'accepted', 'rejected'
  final String? type; // 'friend' or 'follow' (null if status is 'none')
  final bool? isSender; // true if current user initiated (null if status is 'none')
}
```

---

## API Endpoints

### 1. Get Friends List
Get all accepted friends of the current user.

```
GET /friendships
```

**Response:**
```json
{
  "data": [
    {
      "id": 2,
      "name": "Jane Cooper",
      "username": "janecooper",
      "email": "jane@example.com",
      "profile_photo": "https://...",
      "roles": ["user"]
    }
  ]
}
```

---

### 2. Get Pending Requests (Received)
Get all friend/follow requests waiting for your approval.

```
GET /friendships/requests
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "friend",
      "status": "pending",
      "is_sender": false,
      "user": {
        "id": 5,
        "name": "John Doe",
        "username": "johndoe",
        "profile_photo": "https://...",
        "roles": ["user"]
      },
      "created_at": "2025-12-13T17:00:00+00:00",
      "updated_at": "2025-12-13T17:00:00+00:00"
    }
  ],
  "message": "Pending requests retrieved successfully"
}
```

---

### 3. Get Sent Requests
Get all friend/follow requests you have sent that are still pending.

```
GET /friendships/requests/sent
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "type": "follow",
      "status": "pending",
      "is_sender": true,
      "user": {
        "id": 10,
        "name": "Travel Guide Mike",
        "username": "guidemike",
        "profile_photo": "https://...",
        "roles": ["guide"]
      },
      "created_at": "2025-12-13T18:00:00+00:00",
      "updated_at": "2025-12-13T18:00:00+00:00"
    }
  ],
  "message": "Sent requests retrieved successfully"
}
```

---

### 4. Get Followers
Get all users who follow you (for Hosts/Guides).

```
GET /friendships/followers
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 3,
      "name": "Alice Smith",
      "username": "alicesmith",
      "profile_photo": "https://...",
      "roles": ["user"]
    }
  ],
  "message": "Followers retrieved successfully"
}
```

---

### 5. Get Following
Get all users you are following (Hosts/Guides you follow).

```
GET /friendships/following
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 15,
      "name": "Expert Host Sarah",
      "username": "hostsarah",
      "profile_photo": "https://...",
      "roles": ["host"]
    }
  ],
  "message": "Following list retrieved successfully"
}
```

---

### 6. Get Relationship Status
Check your relationship status with a specific user.

```
GET /friendships/status/{userId}
```

**Response (No relationship):**
```json
{
  "success": true,
  "data": {
    "status": "none",
    "type": null,
    "is_sender": null
  },
  "message": "Relationship status retrieved"
}
```

**Response (Pending friend request sent by you):**
```json
{
  "success": true,
  "data": {
    "status": "pending",
    "type": "friend",
    "is_sender": true
  },
  "message": "Relationship status retrieved"
}
```

**Response (Already friends):**
```json
{
  "success": true,
  "data": {
    "status": "accepted",
    "type": "friend",
    "is_sender": false
  },
  "message": "Relationship status retrieved"
}
```

**Response (Following a Host/Guide):**
```json
{
  "success": true,
  "data": {
    "status": "accepted",
    "type": "follow",
    "is_sender": true
  },
  "message": "Relationship status retrieved"
}
```

---

### 7. Send Friend/Follow Request
Send a friend or follow request. System auto-detects type based on target user's role.

```
POST /friendships/send/{userId}
```

**Response (Friend request to regular user):**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "type": "friend",
    "status": "pending",
    "is_sender": true,
    "user": {
      "id": 8,
      "name": "Bob Wilson",
      "username": "bobwilson",
      "profile_photo": "https://...",
      "roles": ["user"]
    },
    "created_at": "2025-12-13T19:00:00+00:00",
    "updated_at": "2025-12-13T19:00:00+00:00"
  },
  "message": "Friend request sent"
}
```

**Response (Follow request to Host/Guide):**
```json
{
  "success": true,
  "data": {
    "id": 6,
    "type": "follow",
    "status": "pending",
    "is_sender": true,
    "user": {
      "id": 12,
      "name": "Pro Guide Tom",
      "username": "guidetom",
      "profile_photo": "https://...",
      "roles": ["guide"]
    },
    "created_at": "2025-12-13T19:00:00+00:00",
    "updated_at": "2025-12-13T19:00:00+00:00"
  },
  "message": "Follow request sent"
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Request already exists or relationship already established"
}
```

---

### 8. Direct Follow (for Hosts/Guides)
Directly follow a Host or Guide without approval.

```
POST /friendships/follow/{userId}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 7,
    "type": "follow",
    "status": "accepted",
    "is_sender": true,
    "user": {
      "id": 20,
      "name": "Local Host Emma",
      "username": "hostemma",
      "profile_photo": "https://...",
      "roles": ["host"]
    },
    "created_at": "2025-12-13T19:30:00+00:00",
    "updated_at": "2025-12-13T19:30:00+00:00"
  },
  "message": "Now following user"
}
```

---

### 9. Unfollow
Stop following a Host/Guide.

```
POST /friendships/unfollow/{userId}
```

**Response:**
```json
{
  "success": true,
  "data": null,
  "message": "Unfollowed successfully"
}
```

---

### 10. Accept Request
Accept a pending friend/follow request.

```
POST /friendships/accept/{userId}
```
*Note: `userId` is the ID of the user who sent the request*

**Response:**
```json
{
  "success": true,
  "data": null,
  "message": "Friend request accepted"
}
```
or
```json
{
  "success": true,
  "data": null,
  "message": "Follow request accepted"
}
```

---

### 11. Reject Request
Reject a pending friend/follow request.

```
POST /friendships/reject/{userId}
```
*Note: `userId` is the ID of the user who sent the request*

**Response:**
```json
{
  "success": true,
  "data": null,
  "message": "Friend request rejected"
}
```

---

### 12. Cancel Sent Request
Cancel a friend/follow request you sent (that is still pending).

```
POST /friendships/cancel/{userId}
```
*Note: `userId` is the ID of the user you sent the request to*

**Response:**
```json
{
  "success": true,
  "data": null,
  "message": "Request cancelled"
}
```

---

### 13. Unfriend
Remove an existing friend.

```
POST /friendships/unfriend/{userId}
```

**Response:**
```json
{
  "success": true,
  "data": null,
  "message": "Unfriended successfully"
}
```

---

### 14. Search Users
Search for users by name, username, or email.

```
GET /users/search?q={query}
```

**Response:**
```json
{
  "data": [
    {
      "id": 5,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "profile_photo": "https://...",
      "roles": ["user"]
    },
    {
      "id": 15,
      "name": "John Guide",
      "username": "johnguide",
      "email": "johnguide@example.com",
      "profile_photo": "https://...",
      "roles": ["guide"]
    }
  ]
}
```

---

## Flutter Implementation Example

### Friendship Repository
```dart
class FriendshipRepository {
  final Dio _dio;

  FriendshipRepository(this._dio);

  Future<List<UserInfo>> getFriends() async {
    final response = await _dio.get('/friendships');
    return (response.data['data'] as List)
        .map((e) => UserInfo.fromJson(e))
        .toList();
  }

  Future<List<FriendshipRequest>> getPendingRequests() async {
    final response = await _dio.get('/friendships/requests');
    return (response.data['data'] as List)
        .map((e) => FriendshipRequest.fromJson(e))
        .toList();
  }

  Future<List<FriendshipRequest>> getSentRequests() async {
    final response = await _dio.get('/friendships/requests/sent');
    return (response.data['data'] as List)
        .map((e) => FriendshipRequest.fromJson(e))
        .toList();
  }

  Future<List<UserInfo>> getFollowers() async {
    final response = await _dio.get('/friendships/followers');
    return (response.data['data'] as List)
        .map((e) => UserInfo.fromJson(e))
        .toList();
  }

  Future<List<UserInfo>> getFollowing() async {
    final response = await _dio.get('/friendships/following');
    return (response.data['data'] as List)
        .map((e) => UserInfo.fromJson(e))
        .toList();
  }

  Future<RelationshipStatus> getRelationshipStatus(int userId) async {
    final response = await _dio.get('/friendships/status/$userId');
    return RelationshipStatus.fromJson(response.data['data']);
  }

  Future<FriendshipRequest> sendRequest(int userId) async {
    final response = await _dio.post('/friendships/send/$userId');
    return FriendshipRequest.fromJson(response.data['data']);
  }

  Future<FriendshipRequest> follow(int userId) async {
    final response = await _dio.post('/friendships/follow/$userId');
    return FriendshipRequest.fromJson(response.data['data']);
  }

  Future<void> unfollow(int userId) async {
    await _dio.post('/friendships/unfollow/$userId');
  }

  Future<void> acceptRequest(int senderId) async {
    await _dio.post('/friendships/accept/$senderId');
  }

  Future<void> rejectRequest(int senderId) async {
    await _dio.post('/friendships/reject/$senderId');
  }

  Future<void> cancelRequest(int userId) async {
    await _dio.post('/friendships/cancel/$userId');
  }

  Future<void> unfriend(int userId) async {
    await _dio.post('/friendships/unfriend/$userId');
  }

  Future<List<UserInfo>> searchUsers(String query) async {
    final response = await _dio.get('/users/search', queryParameters: {'q': query});
    return (response.data['data'] as List)
        .map((e) => UserInfo.fromJson(e))
        .toList();
  }
}
```

### UI Button Logic Based on Relationship Status
```dart
Widget buildActionButton(UserInfo user, RelationshipStatus status) {
  // Check if target user is host or guide
  final isHostOrGuide = user.roles.contains('host') || user.roles.contains('guide');

  if (status.status == 'none') {
    // No relationship - show Add Friend or Follow button
    return ElevatedButton(
      onPressed: () => isHostOrGuide 
          ? friendshipRepo.follow(user.id)
          : friendshipRepo.sendRequest(user.id),
      child: Text(isHostOrGuide ? 'Follow' : 'Add Friend'),
    );
  }

  if (status.status == 'pending') {
    if (status.isSender == true) {
      // You sent the request - show Cancel button
      return OutlinedButton(
        onPressed: () => friendshipRepo.cancelRequest(user.id),
        child: Text('Cancel Request'),
      );
    } else {
      // You received the request - show Accept/Reject
      return Row(
        children: [
          ElevatedButton(
            onPressed: () => friendshipRepo.acceptRequest(user.id),
            child: Text('Accept'),
          ),
          SizedBox(width: 8),
          OutlinedButton(
            onPressed: () => friendshipRepo.rejectRequest(user.id),
            child: Text('Reject'),
          ),
        ],
      );
    }
  }

  if (status.status == 'accepted') {
    if (status.type == 'follow') {
      // Following - show Unfollow button
      return OutlinedButton(
        onPressed: () => friendshipRepo.unfollow(user.id),
        child: Text('Following ✓'),
      );
    } else {
      // Friends - show Unfriend button
      return OutlinedButton(
        onPressed: () => friendshipRepo.unfriend(user.id),
        child: Text('Friends ✓'),
      );
    }
  }

  // Rejected - allow sending new request
  return ElevatedButton(
    onPressed: () => friendshipRepo.sendRequest(user.id),
    child: Text(isHostOrGuide ? 'Follow' : 'Add Friend'),
  );
}
```

---

## UI Requirements

### Friends Screen
- **Tabs**: Friends | Followers | Following | Requests
- **Friends Tab**: List of mutual friends with unfriend option
- **Followers Tab**: Users following you (for Hosts/Guides)
- **Following Tab**: Hosts/Guides you follow with unfollow option
- **Requests Tab**: 
  - Sub-tabs: Received | Sent
  - Received: Accept/Reject buttons
  - Sent: Cancel button

### User Profile Screen
Display appropriate button based on relationship:
| Status | Type | is_sender | Button |
|--------|------|-----------|--------|
| none | - | - | "Add Friend" or "Follow" |
| pending | friend | true | "Cancel Request" |
| pending | friend | false | "Accept" / "Reject" |
| pending | follow | true | "Cancel Request" |
| pending | follow | false | "Accept" / "Reject" |
| accepted | friend | - | "Friends ✓" (tap to unfriend) |
| accepted | follow | - | "Following ✓" (tap to unfollow) |

### Search Screen
- Search bar with debounce
- Show user cards with role badge (Host/Guide/User)
- Show relationship button based on status

---

## Error Handling

| HTTP Code | Meaning |
|-----------|---------|
| 400 | Bad request (e.g., cannot send request to yourself) |
| 401 | Unauthorized - Invalid/expired token |
| 404 | User not found or request not found |
| 422 | Validation failed |
| 500 | Server error |

**Error Response Format:**
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## Best Practices

1. **Cache relationship status**: Store locally and update on actions
2. **Optimistic UI**: Update UI immediately, rollback on error
3. **Debounce search**: Wait 300ms before API call
4. **Pull to refresh**: Allow refreshing friends/requests lists
5. **Badge count**: Show unread request count on tab/navigation
6. **Role badges**: Clearly indicate Host/Guide users with badges
