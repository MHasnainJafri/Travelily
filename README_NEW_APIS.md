# New APIs Implementation Guide

## 🎯 Quick Start

### 1. Run Database Migrations
```bash
php artisan migrate
```

This will create:
- `jam_expenses` table for budget tracking
- `jam_interests` pivot table for jam-interest relationships
- `reports` table for content moderation
- Add 30+ new columns to existing tables

### 2. Import Postman Collection
- Open Postman
- Import `Travelily_Missing_APIs.postman_collection.json`
- Set environment variables:
  - `base_url`: Your API URL (e.g., `http://localhost:8000`)
  - `access_token`: Your authentication token
  - `jamId`, `userId`, `postId`, etc.: Test IDs from your database

### 3. Test New APIs
Start testing the 50+ new endpoints organized in these categories:
- Trip Budget & Expenses (5 endpoints)
- Jam Invitations & Permissions (4 endpoints)
- Calendar & Schedule (2 endpoints)
- Stories (2 endpoints)
- Posts - Likes & Comments (3 endpoints)
- Advertisements (2 endpoints)
- Experiences/Tours (4 endpoints)
- Reviews (3 endpoints)
- Host & Listings (3 endpoints)
- Wallet & Income (1 endpoint)
- Reports & Safety (1 endpoint)
- Search (4 endpoints)
- Friendship (1 endpoint)
- User Profile Extensions (3 endpoints)

## 📋 All New API Endpoints

### Budget & Expenses
```
POST   /api/v1/jams/{jamId}/expenses        - Add expense to trip
GET    /api/v1/jams/{jamId}/budget          - Get budget overview with breakdown
PUT    /api/v1/expenses/{expenseId}         - Update expense
DELETE /api/v1/expenses/{expenseId}         - Delete expense
PATCH  /api/v1/jams/{jamId}/budget          - Update budget slider
```

### Calendar & Schedule
```
GET    /api/v1/jams/{jamId}/calendar        - Get trip timeline (flights, hotels, activities)
GET    /api/v1/jams/{jamId}/schedule        - Get day-by-day schedule view
```

### Stories (24-hour content)
```
GET    /api/v1/stories                      - Get active stories from friends
POST   /api/v1/stories                      - Upload new story
```

### Posts Enhancement
```
GET    /api/v1/posts/{postId}/likes         - Get users who liked with follow status
GET    /api/v1/posts/{postId}/comments      - Get threaded comments with replies
GET    /api/v1/locations/search             - Search locations for check-in
```

### Advertisements (Promoted Content)
```
GET    /api/v1/advertisements               - Get my advertisements
POST   /api/v1/advertisements               - Create new advertisement
```

### Experiences/Tours (Guide Feature)
```
GET    /api/v1/experiences                  - Get my experiences
POST   /api/v1/experiences                  - Create new experience/tour
GET    /api/v1/experiences/{id}             - Get experience details
GET    /api/v1/users/{userId}/experiences   - Get user's public experiences
```

### Reviews
```
POST   /api/v1/reviews                      - Submit review
GET    /api/v1/users/{userId}/reviews       - Get user reviews (sort: newest, top_rated, positive)
GET    /api/v1/tours/{tourId}/reviews       - Get tour reviews
```

### Host & Listings
```
GET    /api/v1/listings/my                  - Get my listings
POST   /api/v1/listings                     - Create new listing
GET    /api/v1/host/bookings                - Get incoming booking requests
```

### Wallet & Income
```
GET    /api/v1/wallet/balance               - Get balance and transaction history
```

### Reports & Safety
```
POST   /api/v1/reports                      - Report inappropriate content
```

### Search
```
GET    /api/v1/search/global                - Search users, trips, posts
GET    /api/v1/jams/{jamId}/users/search    - Search jam members
GET    /api/v1/friends/search               - Search friends
GET    /api/v1/guides/search                - Search guides/hosts
```

### Friendship
```
GET    /api/v1/friendships/suggestions      - Get friend suggestions
```

### Jam User Management
```
DELETE /api/v1/jams/{jamId}/users/{userId}  - Remove user from jam
GET    /api/v1/jams/{jamId}/users/removed   - Get removed users list
```

### User Profile Extensions
```
POST   /api/v1/profile/video                - Upload short video
DELETE /api/v1/profile/video                - Delete short video
DELETE /api/v1/profile/gallery/{mediaId}    - Delete gallery item
```

## 🗄️ Database Changes Summary

### New Tables
1. **jam_expenses** - Track trip expenses with split functionality
2. **jam_interests** - Link jams to interest categories
3. **reports** - Content moderation system

### Modified Tables
1. **itineraries** - Added time fields and amenities
2. **jam_invitations** - Added permission fields
3. **jams** - Added traveler limit and time fields
4. **tasks** - Added start_date for date ranges
5. **jam_users** - Added status (active/left/removed)
6. **listings** - Added offerings and approval status
7. **bookings** - Added experience_id and guide_id
8. **user_profiles** - Added guide_price and hosting_count

## 🎨 Key Features Implemented

### 1. Budget Tracking System
- Add expenses with categories (food, transport, stay, activity, other)
- Split expenses among multiple users
- Real-time budget remaining calculation
- Expense breakdown by category
- Recent expenses list

### 2. Enhanced Calendar
- Unified timeline view combining flights, hotels, and activities
- Day-by-day schedule with time ranges
- Event type indicators

### 3. Stories Feature
- 24-hour expiring content
- Media upload support
- Friend stories feed

### 4. Social Enhancements
- Post likes with follow status
- Threaded comment replies
- Location check-ins with search

### 5. Guide/Host Platform
- Tour/experience creation
- Advertisement system
- Listing management
- Booking request handling
- Income tracking

### 6. Review System
- Multi-filter support (newest, top rated, positive)
- User and tour reviews
- Rating system

### 7. Advanced Search
- Global search across users, trips, posts
- Context-specific searches (jam members, friends, guides)

### 8. User Management
- Remove/restore jam members
- Permission-based invitations
- Friend suggestions based on mutual connections

## 📝 Example Request Bodies

### Create Expense
```json
{
  "title": "Group Dinner at Restaurant",
  "amount": 120.50,
  "currency": "USD",
  "category": "food",
  "date": "2024-06-16",
  "paid_by_user_id": 5,
  "split_with": [2, 5, 8]
}
```

### Create Advertisement
```json
{
  "title": "Museum Tour Special",
  "duration_days": 10,
  "locations": ["Paris", "London"],
  "target_audience": {
    "age_range": [18, 35],
    "gender": ["male", "female"],
    "interests": ["Culture", "History"]
  },
  "payment_method_id": "pm_12345"
}
```

### Create Experience
```json
{
  "title": "Louvre Museum Tour",
  "description": "Skip the line access with expert guide",
  "location": "Paris, France",
  "start_date": "2023-08-17",
  "end_date": "2023-08-23",
  "min_price": 15,
  "max_price": 50
}
```

### Create Listing
```json
{
  "title": "Cozy Downtown Apartment",
  "description": "Perfect for travelers",
  "location": "New York, USA",
  "amenities": [1, 4, 8],
  "house_rules": [2, 5],
  "max_guests": 6,
  "price_per_night": 200,
  "num_rooms": 3,
  "min_stay_days": 2
}
```

## ⚠️ Important Notes

### Authentication
All new endpoints require authentication via Bearer token:
```
Authorization: Bearer {your_access_token}
```

### File Uploads
For media endpoints, use `multipart/form-data`:
- Stories: Support images and videos
- Profile video: Max 50MB
- Gallery: Max 2MB per image

### Permissions
Some endpoints check user permissions:
- Budget updates require `can_edit_budget` permission
- Adding travelers requires `can_add_travelers` permission

### Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request / Validation Error
- `401` - Unauthorized
- `404` - Not Found
- `500` - Server Error

## 🔧 Troubleshooting

### Migration Issues
If migrations fail, check:
1. Database connection in `.env`
2. Existing column conflicts (run rollback if needed)
3. PHP version compatibility (requires PHP 8.0+)

### Route Not Found
If routes aren't working:
1. Clear route cache: `php artisan route:clear`
2. Clear config cache: `php artisan config:clear`
3. Check controller namespaces

### Media Upload Errors
If file uploads fail:
1. Check storage is linked: `php artisan storage:link`
2. Verify file permissions on storage folder
3. Check PHP upload limits in `php.ini`

## 📊 Testing Checklist

- [ ] Run all migrations successfully
- [ ] Import Postman collection
- [ ] Test authentication flow
- [ ] Create a test jam and add expenses
- [ ] Upload a story
- [ ] Create an experience/tour
- [ ] Test search functionality
- [ ] Create a listing (host feature)
- [ ] Submit a review
- [ ] Test calendar view
- [ ] Check wallet balance
- [ ] Test user removal/restoration

## 🚀 Next Steps

1. **Add Authorization Policies**: Implement Laravel policies for fine-grained access control
2. **Create Tests**: Write feature tests for all new endpoints
3. **Add Service Layer**: Refactor business logic into service classes
4. **Implement Notifications**: Add real-time notifications for bookings, invites, etc.
5. **Optimize Queries**: Add eager loading and database indexes
6. **Add API Documentation**: Generate Swagger/OpenAPI documentation
7. **Implement Rate Limiting**: Add throttling for public endpoints
8. **Add Logging**: Implement comprehensive error logging

## 📚 Additional Resources

- Original requirements: `Missing api.md`
- Implementation summary: `IMPLEMENTATION_SUMMARY.md`
- Postman collection: `Travelily_Missing_APIs.postman_collection.json`
- Migration files: `database/migrations/2025_12_22_*`
