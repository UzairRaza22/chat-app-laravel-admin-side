# Laravel Telescope - Setup & Testing Guide

## What is Laravel Telescope?

**Laravel Telescope** is a debugging and monitoring tool for Laravel applications. It provides real-time insights into:
- **HTTP Requests & Responses** - See all incoming API requests with headers, parameters, and responses
- **Database Queries** - Monitor all database/MongoDB queries with execution time
- **Logs & Exceptions** - View application logs and error exceptions
- **Authentication Events** - Track login/logout attempts
- **Mail** - See all sent emails (with preview capability)
- **Cache Operations** - Monitor cache hits and misses
- **Events** - Track Laravel event dispatches
- **Jobs** - Monitor queued jobs and their status

**Think of it as:** A browser developer tools for your backend API.

---

## Installation & Configuration

### ✅ Already Done (In This Project)

1. **Installed** Telescope via Composer
   ```bash
   composer require laravel/telescope --dev
   ```

2. **Published** configuration files
   - `config/telescope.php` - Main configuration
   - `app/Providers/TelescopeServiceProvider.php` - Service provider

3. **Configured** for MongoDB
   - Updated storage to use `mongodb` connection
   - Configured sensitive fields hiding (tokens, passwords)

4. **Security Setup**
   - Gate configured to allow access in local environment
   - Sensitive data hidden in production

---

## How to Access Telescope

### 1. **Local Development Access**
```
http://localhost/telescope
```

Telescope is automatically available on your local machine because:
- `APP_DEBUG=true` in `.env` (development environment)
- Local environment bypasses authentication gate

### 2. **What You'll See**

The Telescope dashboard shows:

```
┌─────────────────────────────────────────────────────────┐
│  TELESCOPE DASHBOARD (http://localhost/telescope)       │
├─────────────────────────────────────────────────────────┤
│  • Requests    - All API requests with full details      │
│  • Queries     - Database/MongoDB queries + timing       │
│  • Logs        - Application log entries                 │
│  • Exceptions  - Errors and stack traces                 │
│  • Mail        - Sent emails (dev environment)           │
│  • Cache       - Cache operations                        │
│  • Events      - Event dispatches                        │
│  • Jobs        - Queued jobs status                      │
└─────────────────────────────────────────────────────────┘
```

---

## Testing Telescope Step-by-Step

### **Step 1: Prepare Your Environment**

Ensure `.env` has:
```env
APP_DEBUG=true          # Enable debug mode
TELESCOPE_ENABLED=true  # Enable Telescope
```

### **Step 2: Start Your Development Server**

```bash
cd c:\xampp\htdocs\combine\chat-app-laravel-project-stages
php artisan serve
```

Or use XAMPP if already configured:
```
http://localhost/chat-app-laravel-project-stages/public
```

### **Step 3: Open Telescope Dashboard**

Go to:
```
http://localhost/telescope
```

You should see a clean dashboard with no requests yet.

---

## Example: Test with Your API Endpoints

### **Test 1: Monitor Admin Login Request**

1. **Open Postman** or curl
2. **Send Request:**
   ```
   POST http://localhost/chat-app-laravel-project-stages/public/api/admin/login
   
   Headers:
   Content-Type: application/json
   
   Body:
   {
     "email": "admin@example.com",
     "password": "password123"
   }
   ```

3. **Watch in Telescope:**
   - Go to `Requests` tab
   - You'll see your login request
   - Click it to view:
     - **Request details:** Headers, method, URL, body
     - **Response:** Status code, response data
     - **Timing:** How long the request took
     - **Middleware:** Which middleware ran

### **Test 2: Monitor Database Queries**

1. **Send a Read Request:**
   ```
   GET http://localhost/chat-app-laravel-project-stages/public/api/admin/users/read?page=1
   
   Headers:
   Authorization: Bearer {your_admin_token}
   ```

2. **View in Telescope → Queries Tab:**
   - See each MongoDB query executed
   - View query text and parameters
   - Check execution time (Slow queries highlighted in red)
   - Spot N+1 query problems

   Example queries you'll see:
   ```
   // MongoDB collection query
   db.users.find({...conditions...})  -- 45ms
   ```

### **Test 3: Monitor Authentication**

1. **Send Multiple Auth Attempts:**
   ```bash
   # First request - invalid token
   curl -H "Authorization: Bearer invalid_token" \
        http://localhost/chat-app-laravel-project-stages/public/api/admin/users/read
   
   # Second request - valid token
   curl -H "Authorization: Bearer valid_admin_token" \
        http://localhost/chat-app-laravel-project-stages/public/api/admin/users/read
   ```

2. **View in Telescope:**
   - First request will fail (unauthorized)
   - See your middleware validating the token
   - Check error logs in `Logs` tab

### **Test 4: Monitor Response Times & Slow Queries**

1. **Send a Complex Read Request:**
   ```
   GET http://localhost/chat-app-laravel-project-stages/public/api/admin/workspaces/read
   ```

2. **In Telescope → Requests:**
   - See total request duration
   - Click "Queries" sub-tab
   - Find any queries taking > 100ms (configured as "slow")
   - Slow queries appear in **orange/red**

### **Test 5: Test Impersonation Token System**

1. **Generate Token:**
   ```
   GET http://localhost/chat-app-laravel-project-stages/public/api/admin/impersonate/read?user_id=123
   
   Headers:
   Authorization: Bearer {admin_token}
   ```

2. **Monitor in Telescope:**
   - **Requests tab:** See the token generation request
   - **Sensitive fields:** Notice tokens are hidden (not logged)
   - **Response:** See token, user_id, expires_in

3. **Use Token:**
   ```
   GET http://localhost/chat-app-laravel-project-stages/public/api/impersonate/dashboard
   
   Headers:
   X-Impersonation-Token: {returned_token}
   ```

4. **Monitor in Telescope:**
   - See middleware validating the impersonation token
   - Check user context changed to impersonated user

---

## Key Telescope Features Explained

### **1. Requests Tab**
```
Shows every HTTP request:
- Method (GET, POST, PUT, DELETE)
- URL & Query parameters
- Request headers
- Request body
- Response status code
- Response body
- Request duration
- Middleware executed
```

**Example:** When you call `/api/admin/users/read`, you'll see:
- Method: `GET`
- URL: `/api/admin/users/read?page=1`
- Auth header: `Authorization: Bearer token...` (parsed)
- Response: `200 OK` with user list JSON
- Duration: `125ms`

### **2. Queries Tab**
```
Shows every database query:
- Query text (MongoDB find, insert, update, delete)
- Bindings/Parameters
- Execution time
- File & line number where query ran
- Stack trace showing code path
```

**Example:** Reading users will show:
```
Collection: users
Operation: find
Time: 45ms
Query: db.users.find({ 
  "active": true,
  "role": "admin"
})
```

### **3. Logs Tab**
```
Shows all Log::info(), Log::error(), etc.
- Log level (info, warning, error, critical)
- Log message
- Context data
- Timestamp
```

**Example from authentication:**
```
[INFO] Admin login attempt: admin@example.com
[ERROR] Invalid token provided
[INFO] User impersonation started: user_123
```

### **4. Exceptions Tab**
```
Shows all thrown exceptions:
- Exception type
- Error message
- Stack trace
- File & line number
- Code context
```

**Example:**
```
ValidationException: Validation failed
Token is required.

Stack trace shows:
- middleware/CheckAdminTokenMiddleware.php:47
- app/Http/Kernel.php:35
...
```

### **5. Gates/Authorization Tab**
```
Shows all Laravel Gate checks:
- Gate name (viewTelescope)
- Pass/Fail result
- User info
- Time taken
```

**Example:**
```
Gate: viewTelescope
Result: PASSED
Duration: 5ms
```

---

## Real-World Debugging Scenario

### **Scenario: Admin Complains - "API is Slow"**

**Step 1: Open Telescope**
```
http://localhost/telescope → Requests tab
```

**Step 2: Find the slow request**
```
GET /api/admin/workspaces/read
Duration: 2,500ms (RED - Very slow!)
Status: 200
```

**Step 3: Click the request → View Queries**
```
Query 1: GET workspace_1    -- 50ms
Query 2: GET workspace_2    -- 45ms
Query 3: GET workspace_3    -- 48ms
...
Query 50: GET workspace_50  -- 50ms
```

**Problem Found:** N+1 query problem! Loading 50 workspaces executed 50 queries.

**Solution:** Use eager loading in controller:
```php
$workspaces = Workspace::with('teams', 'channels')->get();  // 3 queries instead of 51
```

**Step 4: Test again in Telescope**
```
Duration: NEW = 150ms (FAST! ✓)
Queries: 3 instead of 51
```

---

## What Gets Hidden/Removed for Security

In **production** (non-local), Telescope hides:

**Request Parameters:**
- `_token` - CSRF tokens
- `password` - User passwords
- `password_confirmation` - Password confirmations
- `token` - Any token field
- `admin_login_token` - Your custom admin token
- `impersonation_token` - User impersonation token

**Request Headers:**
- `cookie` - Session cookies
- `x-csrf-token` - CSRF protection header
- `x-xsrf-token` - XSRF protection header
- `authorization` - Bearer tokens
- `x-impersonation-token` - Impersonation token

**Why?** So sensitive data isn't exposed in logs, even if someone gains access to Telescope in production.

---

## Common Testing Workflows

### **Test 1: Validate Throttling (30 requests/minute)**

```bash
# Send 31 requests rapidly
for i in {1..31}; do
  curl -H "Authorization: Bearer token" \
       http://localhost/telescope
done
```

**Check Telescope:**
- First 30 requests: Status `200 OK`
- Request 31: Status `429 Too Many Requests`
- Header: `Retry-After: 45` (seconds until quota resets)

### **Test 2: Check Middleware Execution Order**

1. Send request:
   ```
   GET /api/admin/users/read
   ```

2. Go to **Telescope → Requests tab**
3. Click the request → **Middleware sub-tab**
4. See execution order:
   ```
   1. HandleCors ✓
   2. ThrottleRequests (30:1) ✓
   3. Authentication (check.admin.auth) ✓
   4. Validation (check.admin.read.validation) ✓
   5. Response → 200 OK
   ```

### **Test 3: Monitor Validation Errors**

1. Send invalid request:
   ```
   GET /api/admin/users/read?per_page=invalid_number
   ```

2. **Telescope → Requests:**
   - Status: `422` (Validation failed)
   - Click → See response body with validation errors

3. **Telescope → Exceptions:**
   - See `ValidationException`
   - Error details: `per_page must be numeric`

### **Test 4: Track User Impersonation**

1. Generate impersonation token (admin):
   ```
   GET /api/admin/impersonate/read?user_id=456
   ```

2. Use token (as frontend):
   ```
   GET /api/impersonate/dashboard
   Header: X-Impersonation-Token: {token}
   ```

3. **Telescope → Requests (2 requests visible):**
   - Request 1: Token generation (admin context)
   - Request 2: Dashboard access (user_456 context)

4. **Telescope → Logs:**
   ```
   [INFO] User impersonation started: user_456
   [INFO] Dashboard accessed by: user_456
   ```

---

## Performance Tips Based on Telescope Findings

| Finding | Cause | Solution |
|---------|-------|----------|
| 50+ queries | N+1 problem | Use `with()` for eager loading |
| 2000ms+ requests | Slow database | Index MongoDB collections |
| Repeated same query | Unnecessary duplication | Cache or load once |
| High memory usage | Large response | Paginate results |
| Timeout errors | Missing timeout | Add timeout middleware |

---

## Disabling Telescope When Not Needed

**Option 1: Via .env**
```env
TELESCOPE_ENABLED=false
```

**Option 2: Via Code**
```php
// In TelescopeServiceProvider.php
if (! $this->app->environment('local')) {
    Telescope::night();  // Disable Telescope in production
}
```

**Option 3: Purge Old Data** (Telescope uses disk space)
```bash
php artisan telescope:prune  # Keeps last 7 days of data
```

---

## Troubleshooting

### **Problem: "Access denied" when accessing /telescope**
**Solution:** Ensure you're in `local` environment with `APP_DEBUG=true`

### **Problem: "No requests showing in Telescope"**
**Solution:** 
- Make sure `TELESCOPE_ENABLED=true`
- Wait a few seconds after request
- Check browser console for errors

### **Problem: "Telescope dashboard is slow"**
**Solution:**
- Run `php artisan telescope:prune`
- Disable unnecessary watchers in `config/telescope.php`

### **Problem: Can't see MongoDB queries**
**Solution:**
- Ensure `TELESCOPE_QUERY_WATCHER=true` in config
- Check Telescope connection is set to `mongodb`

---

## Summary

✅ **Telescope is now installed and configured**

**Access it at:** `http://localhost/telescope`

**Key Points:**
- Live monitoring of all API requests
- See database queries and performance
- Track authentication and errors
- Sensitive data is automatically hidden
- Perfect for debugging and optimization

**Next Steps:**
1. Make some API requests from Postman
2. Watch them appear in Telescope
3. Click requests to see full details
4. Identify slow queries and optimize
5. Use logs tab to understand flow

**Remember:** Telescope is a development tool. In production, set `APP_DEBUG=false` to disable it completely.
