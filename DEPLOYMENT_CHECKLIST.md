# Deployment Checklist - New APIs

## 📋 Pre-Deployment

### 1. Code Review
- [ ] All controllers follow Laravel best practices
- [ ] Input validation added to all endpoints
- [ ] Error handling implemented
- [ ] Database queries optimized (no N+1 issues)
- [ ] Code comments added where needed

### 2. Database
- [ ] Run migrations in staging: `php artisan migrate --pretend`
- [ ] Backup production database
- [ ] Test rollback: `php artisan migrate:rollback --step=1 --pretend`
- [ ] Verify foreign key constraints
- [ ] Add database indexes (see below)

### 3. Configuration
- [ ] Update `.env` file with production values
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure queue drivers
- [ ] Set up Redis/cache
- [ ] Configure file storage (AWS S3 or similar)
- [ ] Set appropriate `upload_max_filesize` in php.ini

### 4. Testing
- [ ] Test all 50+ endpoints in staging
- [ ] Test file uploads (stories, videos, gallery)
- [ ] Test search functionality
- [ ] Test expense calculations
- [ ] Test permissions system
- [ ] Load test critical endpoints
- [ ] Test error scenarios

### 5. Dependencies
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Verify all required PHP extensions installed
- [ ] Check PHP version (8.0+ required)
- [ ] Verify Laravel Spatie Media Library configured

## 🚀 Deployment Steps

### Step 1: Backup
```bash
# Backup database
php artisan db:backup

# Backup current code
git tag -a v1.0-pre-new-apis -m "Before new APIs deployment"
git push origin v1.0-pre-new-apis
```

### Step 2: Pull Code
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
```

### Step 3: Run Migrations
```bash
php artisan migrate --force
```

### Step 4: Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 5: Link Storage
```bash
php artisan storage:link
```

### Step 6: Set Permissions
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 7: Restart Services
```bash
# Restart PHP-FPM
sudo systemctl restart php8.1-fpm

# Restart queue workers
php artisan queue:restart

# Restart web server
sudo systemctl restart nginx
```

### Step 8: Verify
```bash
# Check application is up
curl -I https://your-domain.com/api/v1/health

# Check logs
tail -f storage/logs/laravel.log
```

## 🗄️ Database Indexes (Performance)

Run these after migrations to optimize queries:

```sql
-- Expenses
CREATE INDEX idx_jam_expenses_jam_id ON jam_expenses(jam_id);
CREATE INDEX idx_jam_expenses_date ON jam_expenses(date);
CREATE INDEX idx_jam_expenses_category ON jam_expenses(category);
CREATE INDEX idx_jam_expenses_paid_by ON jam_expenses(paid_by_user_id);

-- Reports
CREATE INDEX idx_reports_user_id ON reports(user_id);
CREATE INDEX idx_reports_target ON reports(target_type, target_id);
CREATE INDEX idx_reports_status ON reports(status);

-- Jam Users
CREATE INDEX idx_jam_users_status ON jam_users(status);

-- Bookings
CREATE INDEX idx_bookings_experience_id ON bookings(experience_id);
CREATE INDEX idx_bookings_guide_id ON bookings(guide_id);

-- Listings
CREATE INDEX idx_listings_approval_status ON listings(approval_status);
CREATE INDEX idx_listings_user_id ON listings(user_id);

-- Advertisements
CREATE INDEX idx_advertisements_user_id ON advertisements(user_id);
CREATE INDEX idx_advertisements_status ON advertisements(status);
CREATE INDEX idx_advertisements_dates ON advertisements(start_date, end_date);
```

## 🔒 Security Configuration

### .env Production Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=production_db
DB_USERNAME=prod_user
DB_PASSWORD=strong-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

### Rate Limiting
Add to `app/Http/Kernel.php`:
```php
'api' => [
    'throttle:60,1', // 60 requests per minute
    'bindings',
],
```

### CORS Configuration
Update `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['https://your-frontend-domain.com'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

## 📊 Monitoring Setup

### 1. Error Tracking
```bash
# Install Sentry (optional)
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=your-dsn
```

### 2. Application Monitoring
- [ ] Set up Laravel Telescope in production (read-only mode)
- [ ] Configure log aggregation (Papertrail, Loggly, etc.)
- [ ] Set up uptime monitoring (Pingdom, UptimeRobot)
- [ ] Configure performance monitoring (New Relic, Datadog)

### 3. Database Monitoring
- [ ] Enable slow query log
- [ ] Set up database connection pool monitoring
- [ ] Configure automated backups
- [ ] Set up replication monitoring (if applicable)

### 4. Storage Monitoring
- [ ] Monitor disk space usage
- [ ] Set up S3 bucket monitoring
- [ ] Configure CDN cache hit rates
- [ ] Monitor media upload success rates

## 🚨 Rollback Plan

### If Issues Arise

#### Quick Rollback (Code Only)
```bash
git revert HEAD
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
sudo systemctl restart php8.1-fpm
```

#### Full Rollback (Including Database)
```bash
# 1. Restore code
git checkout v1.0-pre-new-apis

# 2. Restore database
mysql -u user -p database < backup.sql

# 3. Clear caches
php artisan config:cache
php artisan route:cache

# 4. Restart services
sudo systemctl restart php8.1-fpm
```

#### Partial Rollback (Migrations Only)
```bash
# Rollback last 4 migrations
php artisan migrate:rollback --step=4

# Clear caches
php artisan config:cache
php artisan route:cache
```

## ✅ Post-Deployment Verification

### 1. Functional Tests
```bash
# Test authentication
curl -X POST https://your-domain.com/api/v1/register

# Test expense creation
curl -X POST https://your-domain.com/api/v1/jams/1/expenses \
  -H "Authorization: Bearer TOKEN"

# Test search
curl https://your-domain.com/api/v1/search/global?query=test
```

### 2. Performance Tests
- [ ] API response times < 200ms for simple queries
- [ ] Database query times < 50ms
- [ ] File upload works smoothly
- [ ] Search returns results in < 500ms
- [ ] No memory leaks

### 3. Monitoring Checks
- [ ] Error rate < 1%
- [ ] All queue workers running
- [ ] Database connections stable
- [ ] Cache hit ratio > 80%
- [ ] Storage writes successful

### 4. User Acceptance
- [ ] Mobile app can consume new endpoints
- [ ] Frontend integrates successfully
- [ ] No breaking changes to existing APIs
- [ ] User flows complete end-to-end

## 📧 Notification Plan

### Stakeholders to Notify
1. **Development Team** - Technical details, rollback procedures
2. **QA Team** - Testing checklist, known issues
3. **Product Team** - New features available
4. **Support Team** - New endpoints documentation
5. **Users** - Release notes (if customer-facing)

### Communication Template
```
Subject: API Deployment - New Features Live

Deployment completed successfully at [TIME]

New Features:
- Budget & expense tracking (5 endpoints)
- Stories feature (2 endpoints)
- Enhanced search (4 endpoints)
- Guide/Host platform (10 endpoints)
- [... full list ...]

Documentation:
- API Reference: [URL]
- Postman Collection: [URL]
- Known Issues: [URL]

Rollback Plan: Available if needed

Contact: [Your contact info]
```

## 🔄 Queue Workers

### Start Queue Workers
```bash
# Development
php artisan queue:work

# Production (using Supervisor)
# Add to /etc/supervisor/conf.d/laravel-worker.conf:

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## 📝 Documentation Updates

- [ ] Update API documentation
- [ ] Update README.md
- [ ] Update Postman collection version
- [ ] Create release notes
- [ ] Update changelog
- [ ] Document breaking changes (if any)
- [ ] Update SDK/client libraries (if applicable)

## 🎉 Success Criteria

Deployment is successful if:
- [ ] All migrations run without errors
- [ ] All endpoints return 200/201 for valid requests
- [ ] Error rate < 1% in first 24 hours
- [ ] No critical bugs reported
- [ ] Performance metrics within acceptable range
- [ ] Rollback plan tested and ready
- [ ] Team trained on new features

## 📞 Emergency Contacts

- **Technical Lead**: [Name & Contact]
- **DevOps**: [Name & Contact]
- **Database Admin**: [Name & Contact]
- **On-Call Engineer**: [Name & Contact]

## 📅 Timeline

| Time | Action | Owner |
|------|--------|-------|
| T-24h | Final staging tests | QA Team |
| T-2h | Database backup | DevOps |
| T-1h | Notify stakeholders | Product |
| T-0 | Deploy code | DevOps |
| T+15m | Run migrations | DevOps |
| T+30m | Verify functionality | QA Team |
| T+1h | Monitor metrics | DevOps |
| T+24h | Review & retrospective | All |

---

**Deployment Date**: _____________
**Deployed By**: _____________
**Sign-off**: _____________
