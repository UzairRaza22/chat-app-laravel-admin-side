# Telescope Quick Start - Testing in 5 Minutes

## Step 1: Ensure Environment is Set Correctly (1 min)

Check/Update your `.env` file:

```env
APP_DEBUG=true
TELESCOPE_ENABLED=true
DB_CONNECTION=mongodb
```

## Step 2: Start Development Server (1 min)

**Option A: Using PHP Built-in Server**
```bash
cd c:\xampp\htdocs\combine\chat-app-laravel-project-stages
php artisan serve
```

**Option B: Using XAMPP**
- Start Apache from XAMPP Control Panel
- Access: `http://localhost/chat-app-laravel-project-stages/public`

## Step 3: Access Telescope Dashboard (30 sec)

Open browser and go to:
```
http://localhost:8000/telescope
```

Or if using XAMPP:
```
http://localhost/chat-app-laravel-project-stages/public/telescope
```

You should see a dashboard with multiple tabs on the left side.

## Step 4: Quick Tests (2.5 min)

### Test A: Monitor a Simple Request

**Using Postman:**

1. Open Postman
2. Create new GET request:
   ```
   GET http://localhost:8000/api/admin/users/read
   ```
3. Add header:
   ```
   Authorization: Bearer {your_admin_token}
   ```
4. Click Send

**In Telescope Dashboard:**
- Watch the "Requests" tab
- You'll see your request appear
- Click on it to see:
  - Full request details (headers, body)
  - Full response (data, status)
  - Time taken
  - Database queries executed
  - Middleware that ran

### Test B: Monitor Database Queries

**Send another request:**
```
GET http://localhost:8000/api/admin/workspaces/read
```

**In Telescope Dashboard:**
- Go to "Queries" tab
- See each MongoDB query
- View query execution time
- Identify slow queries (yellow/red highlights)

### Test C: Monitor Errors

**Send a bad request:**
```
GET http://localhost:8000/api/admin/users/read
(Without Authorization header)
```

**In Telescope Dashboard:**
- Status: 401 Unauthorized
- Go to "Exceptions" tab
- See the exact error
- View stack trace

### Test D: Check Logs

**Any previous request with logs:**

**In Telescope Dashboard:**
- Go to "Logs" tab
- See all log entries
- Check log level (info, error, warning)
- View detailed context

## Understanding the Tabs

```
Request Tab
├─ Shows all HTTP requests
├─ Click to see full details
└─ Check response time, status, data

Queries Tab
├─ All database/MongoDB queries
├─ Slow queries highlighted
└─ See N+1 query problems

Logs Tab
├─ All application logs
├─ Filter by level (error, info, etc.)
└─ Search by keyword

Exceptions Tab
├─ All errors/exceptions thrown
├─ Full stack trace
└─ File and line number

Gates Tab
├─ Authorization checks
├─ Pass/Fail status
└─ Check authentication logic

Cache Tab
├─ Cache hit/miss operations
└─ Performance metrics
```

## Real Time Monitoring

**Best Workflow:**

1. Open Telescope in one browser tab
2. Keep it on "Requests" tab
3. Go to Postman in another window
4. Send API request
5. Watch it appear in Telescope **in real-time**
6. Click to drill down into details

## What to Look For

### ✓ Good Signs
- Requests under 200ms
- Database queries under 100ms
- No N+1 queries (one query per resource)
- Proper 200/201 status codes
- No unnecessary queries

### ⚠ Warning Signs
- Requests over 1000ms
- Queries over 500ms
- Repeated identical queries
- Unexpected 404/500 errors
- Large response payloads

## Key Shortcuts

- **Search Requests:** Type in search box at top
- **Filter by Method:** Click GET/POST/PUT/DELETE buttons
- **Filter by Status:** Click status codes
- **Real-time Toggle:** Enable/disable live monitoring
- **Purge Data:** Clear Telescope data (Telescope settings)

## Common First-Time Issues

### Issue 1: "No requests showing"
**Fix:** 
- Make sure you're on correct URL
- Confirm `TELESCOPE_ENABLED=true`
- Give it a few seconds to load

### Issue 2: "Telescope page not found (404)"
**Fix:**
- Verify `http://localhost:8000/telescope` (correct port)
- Check `php artisan serve` is running
- Not using XAMPP? Use `http://localhost/telescope`

### Issue 3: "Can't see query details"
**Fix:**
- Click on the request first (main tab)
- Then click "Queries" sub-tab
- Make sure query watcher is enabled

## Pro Tips

1. **Compare Request Times:** Send same request twice, see if second is faster (caching)
2. **Check Auth Flow:** Send with/without token, see middleware differences
3. **Monitor Throttling:** Send 31 requests, see 429 error on 31st
4. **Test Error Handling:** Send invalid data, see validation errors in Telescope
5. **Profile Endpoints:** Find slowest endpoint, optimize it

## Disabling for Production

When deploying to production:

```env
# In production .env
APP_DEBUG=false
TELESCOPE_ENABLED=false
```

This automatically:
- Hides Telescope dashboard
- Stops recording requests
- Saves memory and disk space
- Removes sensitive data exposure risk

## Next Steps

After 5-minute quick test:

1. Read `TELESCOPE_GUIDE.md` for detailed information
2. Set up Telescope alerts for slow requests
3. Create custom watchers for business logic
4. Use Telescope findings to optimize API

---

**Done!** You now have a powerful debugging tool for your Laravel API. Use it to:
- Monitor real user requests
- Find performance bottlenecks
- Debug complex issues
- Optimize database queries
- Track authentication flow
