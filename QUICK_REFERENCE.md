# Quick Reference Guide - New APIs

## 🚀 Installation (3 Steps)

```bash
# 1. Run migrations
php artisan migrate

# 2. Clear caches
php artisan route:clear
php artisan config:clear

# 3. Test in Postman
# Import: Travelily_Missing_APIs.postman_collection.json
```

## 📁 Files Created

### Migrations (4 files)
```
database/migrations/2025_12_22_000001_create_jam_expenses_table.php
database/migrations/2025_12_22_000002_add_missing_fields_to_tables.php
database/migrations/2025_12_22_000003_create_jam_interests_table.php
database/migrations/2025_12_22_000004_create_reports_table.php
```

### Controllers (10 new)
```
app/Http/Controllers/Api/V1/ExpenseController.php
app/Http/Controllers/Api/V1/LocationController.php
app/Http/Controllers/Api/V1/AdvertisementController.php
app/Http/Controllers/Api/V1/ExperienceController.php
app/Http/Controllers/Api/V1/ReviewController.php
app/Http/Controllers/Api/V1/WalletController.php
app/Http/Controllers/Api/V1/ReportController.php
app/Http/Controllers/Api/V1/ListingController.php
app/Http/Controllers/Api/V1/CalendarController.php
app/Http/Controllers/Api/V1/SearchController.php
```

### Controllers Updated (3 existing)
```
app/Http/Controllers/Api/V1/FriendshipController.php - Added getSuggestions()
app/Http/Controllers/Api/V1/JamUserController.php - Added removeUser(), getRemovedUsers()
app/Http/Controllers/Api/V1/UserMediaController.php - Added uploadVideo(), deleteVideo(), deleteGalleryItem()
```

### Routes Updated
```
routes/api.php - Added 50+ new endpoints
```

### Documentation
```
IMPLEMENTATION_SUMMARY.md - Technical implementation details
README_NEW_APIS.md - Complete API reference with examples
QUICK_REFERENCE.md - This file
Travelily_Missing_APIs.postman_collection.json - Ready-to-use Postman collection
```

## 🎯 API Categories (50+ endpoints)

| Category | Endpoints | Controller |
|----------|-----------|------------|
| **Budget & Expenses** | 5 | ExpenseController |
| **Calendar & Schedule** | 2 | CalendarController |
| **Stories** | 2 | StoryController |
| **Post Enhancements** | 3 | PostController, LocationController |
| **Advertisements** | 2 | AdvertisementController |
| **Experiences/Tours** | 4 | ExperienceController |
| **Reviews** | 3 | ReviewController |
| **Host & Listings** | 3 | ListingController |
| **Wallet** | 1 | WalletController |
| **Reports** | 1 | ReportController |
| **Search** | 4 | SearchController |
| **Friendship** | 1 | FriendshipController |
| **Jam Management** | 2 | JamUserController |
| **Profile Media** | 3 | UserMediaController |

## 💾 Database Changes (Quick Overview)

### New Tables (3)
- `jam_expenses` - Trip expense tracking
- `jam_interests` - Jam-interest relationships
- `reports` - Content moderation

### New Columns
| Table | New Columns |
|-------|-------------|
| `itineraries` | time, start_time, end_time, amenities, activity_category |
| `jam_invitations` | can_edit_jamboard, can_add_travelers, can_edit_budget, can_add_destinations |
| `jams` | total_travelers_limit, start_time, stay_time_days |
| `tasks` | start_date |
| `jam_users` | status (enum) |
| `listings` | offerings, dates_available, approval_status |
| `bookings` | experience_id, guide_id |
| `user_profiles` | guide_price, hosting_count |

## 🔑 Common Endpoints Cheat Sheet

### Budget Management
```http
POST   /api/v1/jams/{jamId}/expenses
GET    /api/v1/jams/{jamId}/budget
PUT    /api/v1/expenses/{expenseId}
DELETE /api/v1/expenses/{expenseId}
PATCH  /api/v1/jams/{jamId}/budget
```

### Social Features
```http
GET    /api/v1/stories
POST   /api/v1/stories
GET    /api/v1/posts/{postId}/likes
GET    /api/v1/posts/{postId}/comments
GET    /api/v1/locations/search?query=Paris
```

### Guide/Host Platform
```http
GET    /api/v1/experiences
POST   /api/v1/experiences
GET    /api/v1/listings/my
POST   /api/v1/listings
GET    /api/v1/host/bookings?status=pending
GET    /api/v1/wallet/balance
```

### Search & Discovery
```http
GET    /api/v1/search/global?query=bali
GET    /api/v1/friends/search?query=john
GET    /api/v1/guides/search?query=maria
GET    /api/v1/friendships/suggestions
```

### User Management
```http
DELETE /api/v1/jams/{jamId}/users/{userId}
GET    /api/v1/jams/{jamId}/users/removed
POST   /api/v1/profile/video
DELETE /api/v1/profile/video
DELETE /api/v1/profile/gallery/{mediaId}
```

## 🧪 Testing Workflow

### 1. Setup
```bash
# Start server
php artisan serve

# In another terminal, watch logs
tail -f storage/logs/laravel.log
```

### 2. Postman Setup
```
base_url = http://localhost:8000
access_token = [Get from login endpoint]
jamId = 1
userId = 1
```

### 3. Test Flow
1. **Authentication** → Get access token
2. **Create Jam** → Get jamId
3. **Add Expense** → Test budget tracking
4. **Upload Story** → Test media uploads
5. **Create Experience** → Test guide features
6. **Search** → Test search functionality
7. **Get Calendar** → Test data aggregation

## 📝 Request Examples

### Add Expense
```bash
curl -X POST http://localhost:8000/api/v1/jams/1/expenses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Dinner",
    "amount": 50.00,
    "category": "food",
    "date": "2024-06-16",
    "paid_by_user_id": 1,
    "split_with": [1, 2, 3]
  }'
```

### Create Experience
```bash
curl -X POST http://localhost:8000/api/v1/experiences \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "City Tour",
    "description": "Guided city tour",
    "location": "Paris",
    "min_price": 20,
    "max_price": 50
  }'
```

### Global Search
```bash
curl -X GET "http://localhost:8000/api/v1/search/global?query=bali" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## ⚡ Performance Tips

1. **Database Indexes** (Add these manually):
```sql
CREATE INDEX idx_jam_expenses_jam_id ON jam_expenses(jam_id);
CREATE INDEX idx_jam_expenses_date ON jam_expenses(date);
CREATE INDEX idx_reports_status ON reports(status);
CREATE INDEX idx_jam_users_status ON jam_users(status);
```

2. **Cache Configuration**:
```php
// In config/cache.php - ensure Redis is configured for production
```

3. **Queue Jobs**:
```php
// Move heavy operations to queues:
// - Story processing
// - Notification sending
// - Report processing
```

## 🐛 Common Issues & Fixes

### Routes Not Found
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Migration Errors
```bash
# Rollback last batch
php artisan migrate:rollback

# Re-run
php artisan migrate
```

### Media Upload Fails
```bash
# Link storage
php artisan storage:link

# Check permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Namespace Issues
```bash
# Regenerate autoload
composer dump-autoload
```

## 📊 API Response Formats

### Success Response
```json
{
  "status": true,
  "message": "Success message",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": false,
  "message": "Error message",
  "errors": { ... }
}
```

### Paginated Response
```json
{
  "status": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  }
}
```

## 🔐 Security Checklist

- [ ] All routes use `auth:api` middleware
- [ ] Input validation on all endpoints
- [ ] SQL injection prevention (using Query Builder)
- [ ] File upload validation (size, type)
- [ ] Rate limiting configured
- [ ] CORS settings verified
- [ ] Environment variables secured
- [ ] API keys not in version control

## 📈 Monitoring

### Key Metrics to Track
- API response times
- Error rates by endpoint
- Database query performance
- Storage usage (media files)
- Active user sessions
- Failed authentication attempts

### Logging
```php
// Controllers already log errors
// Check: storage/logs/laravel.log
```

## 🎓 Next Development Steps

1. **Week 1**: Testing & Bug Fixes
2. **Week 2**: Add authorization policies
3. **Week 3**: Write automated tests
4. **Week 4**: Performance optimization
5. **Week 5**: Add notifications
6. **Week 6**: Production deployment

## 📞 Support

For implementation questions:
1. Check `IMPLEMENTATION_SUMMARY.md`
2. Check `README_NEW_APIS.md`
3. Review Postman collection
4. Check Laravel logs

## ✅ Verification Checklist

- [ ] All 4 migrations run successfully
- [ ] All 50+ routes accessible
- [ ] Postman collection imports
- [ ] Authentication works
- [ ] File uploads work
- [ ] Database queries optimized
- [ ] Error handling in place
- [ ] Documentation complete
