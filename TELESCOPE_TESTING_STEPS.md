# Real-Time Telescope Testing - Step-by-Step

## 🚀 Quick Start (5 Minutes)

### Step 1: Start Development Server

**Option A: PHP Built-in Server (Recommended)**
```bash
cd c:\xampp\htdocs\combine\chat-app-laravel-project-stages
php artisan serve
```

You should see:
```
   INFO  Server running on http://127.0.0.1:8000
```

**Option B: Using XAMPP**
- Start Apache from XAMPP Control Panel
- Access: `http://localhost/chat-app-laravel-project-stages/public`

---

### Step 2: Open Two Browser Tabs

**Tab 1: Telescope Dashboard**
```
http://localhost:8000/telescope
```

**Tab 2: Your API** (Keep for testing)
```
http://localhost:8000/api/health
```

---

### Step 3: Install & Open Postman

**Download:** https://www.postman.com/downloads/

**Why Postman?**
- Send API requests easily
- Add headers without typing
- Save requests for later
- See response formatted nicely

---

## 📝 Test Requests - Copy & Paste These

### **Test 1: Health Check (No Auth Required)**

**Request:**
```
GET http://localhost:8000/up
```

**In Postman:**
1. Click `+` to create new tab
2. Change method to `GET`
3. Enter URL: `http://localhost:8000/up`
4. Click **Send**

**Expected Response:**
```
{
  "status": "ok"
}
```

**Watch in Telescope:**
- Go to **Requests** tab
- You'll see the `/up` request appear instantly
- Status: `200 OK`
- Duration: ~5ms

---

### **Test 2: API Health Check (No Auth)**

**Request:**
```
GET http://localhost:8000/api/health
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/health
Headers: None needed
```

**Expected Response:**
```
Status: 200
{
  "message": "API is running"
}
```

**Watch in Telescope:**
- Appears in **Requests** tab
- Click it to see full details
- No throttle applied (health endpoint exempt)

---

### **Test 3: Admin Login (Get Token)**

**Request:**
```
POST http://localhost:8000/api/admin/login
```

**In Postman:**
```
Method: POST
URL: http://localhost:8000/api/admin/login
Headers:
  Content-Type: application/json

Body (raw JSON):
{
  "email": "your_admin_email@example.com",
  "password": "your_admin_password"
}
```

**Expected Response:**
```
Status: 200
{
  "message": "Login successful",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "admin_id": "507f1f77bcf86cd799439011",
    "admin_email": "your_admin_email@example.com"
  }
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ POST /api/admin/login
├─ Status: 200 OK ✓
├─ Duration: 125ms
├─ Headers: Check Content-Type, Authorization response
└─ Response: See token generated

Queries Tab:
├─ db.admins.find() -- Check admin by email
├─ Duration: 45ms
└─ Parameters visible

Logs Tab:
├─ [INFO] Admin login attempt: your_admin_email@example.com
├─ [INFO] Login successful for: your@email.com
└─ Timestamp shown
```

**⚠️ Save the token!** You'll need it for next tests.

---

### **Test 4: Read Users with Valid Token**

**Request:**
```
GET http://localhost:8000/api/admin/users/read?page=1&per_page=10
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/users/read?page=1&per_page=10
Headers:
  Authorization: Bearer {YOUR_TOKEN_FROM_TEST_3}
  Content-Type: application/json
```

**Expected Response:**
```
Status: 200
{
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": "507f1f77bcf86cd799439012",
      "name": "John Doe",
      "email": "john@example.com",
      ...
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 50
  }
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ GET /api/admin/users/read
├─ Status: 200 OK ✓
├─ Duration: 150ms
├─ Headers: Authorization header shown (but token value hidden)
├─ Query Params: page=1, per_page=10 visible
└─ Response: See user list data

Middleware Tab:
├─ ThrottleRequests:30,1 ✓
├─ CheckAdminAuth ✓
├─ CheckAdminReadValidation ✓
└─ ResponseHandler ✓

Queries Tab:
├─ db.users.find({...conditions...}) -- 45ms
├─ db.users.countDocuments() -- 15ms
└─ Total: 60ms

Database:
├─ Query Count: 2 queries
├─ Execution Time: 60ms total
└─ Slow Query Check: All < 100ms ✓
```

---

### **Test 5: Invalid Request (No Token)**

**Request:**
```
GET http://localhost:8000/api/admin/users/read
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/users/read
Headers:
  (Leave empty - no Authorization header)
```

**Expected Response:**
```
Status: 401 Unauthorized
{
  "message": "Token is required.",
  "error": "Unauthorized"
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ GET /api/admin/users/read
├─ Status: 401 UNAUTHORIZED ⚠️
├─ Duration: 5ms (Fast rejection)
├─ Headers: No Authorization header shown
└─ Response: "Token is required"

Exceptions Tab:
├─ AuthorizationException
├─ Message: "Token is required"
├─ Stack trace: Points to middleware
└─ File: CheckAdminTokenMiddleware.php:47

Logs Tab:
└─ [ERROR] Unauthorized access attempt (no token)
```

---

### **Test 6: Invalid Token**

**Request:**
```
GET http://localhost:8000/api/admin/users/read
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/users/read
Headers:
  Authorization: Bearer invalid_token_xyz_123
```

**Expected Response:**
```
Status: 401 Unauthorized
{
  "message": "Invalid or expired token.",
  "error": "Unauthorized"
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ Status: 401 ⚠️
├─ Duration: 25ms
└─ Response: "Invalid or expired token"

Exceptions Tab:
├─ AuthorizationException
├─ "Invalid or expired token"
└─ Stack trace shows token validation

Logs Tab:
└─ [ERROR] Invalid token validation failed
```

---

### **Test 7: Pagination & Query Monitoring**

**Request (Large Page):**
```
GET http://localhost:8000/api/admin/users/read?page=1&per_page=100
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/users/read?page=1&per_page=100
Headers:
  Authorization: Bearer {YOUR_TOKEN}
```

**Watch in Telescope → Queries Tab:**
```
Number of Queries: Should be ONLY 2:
1. db.users.find(conditions) -- Fetch users
2. db.users.countDocuments() -- Count total

❌ BAD (N+1 Problem):
101 queries (1 per user + 1 for count)

✓ GOOD (Eager Loading):
2 queries (all users at once + count)
```

---

### **Test 8: Test Throttling (30 requests/minute)**

**Rapid Request Script (PowerShell):**

```powershell
# Save this as test-throttle.ps1

$token = "YOUR_TOKEN_HERE"
$url = "http://localhost:8000/api/admin/users/read"

# Send 31 requests rapidly
for ($i = 1; $i -le 31; $i++) {
    Write-Host "Request $i..." -NoNewline
    $response = Invoke-WebRequest -Uri $url `
        -Headers @{"Authorization"="Bearer $token"} `
        -ErrorAction SilentlyContinue
    
    $status = $response.StatusCode
    Write-Host " Status: $status"
    
    if ($status -eq 429) {
        Write-Host "⚠️ Throttled! Request $i blocked" -ForegroundColor Yellow
        break
    }
}
```

**Run in PowerShell:**
```bash
powershell -ExecutionPolicy Bypass -File test-throttle.ps1
```

**Watch in Telescope:**
```
Requests 1-30: Status 200 ✓
Request 31: Status 429 ⚠️ (Too Many Requests)

Response Headers (on request 31):
├─ Retry-After: 45
├─ X-RateLimit-Limit: 30
├─ X-RateLimit-Remaining: 0
└─ Message: "Too Many Requests"
```

---

### **Test 9: Test Impersonation System**

**Step A: Generate Impersonation Token (as Admin)**

**Request:**
```
GET http://localhost:8000/api/admin/impersonate/read?user_id=507f1f77bcf86cd799439012
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/impersonate/read?user_id=507f1f77bcf86cd799439012
Headers:
  Authorization: Bearer {ADMIN_TOKEN}
```

**Expected Response:**
```
Status: 200
{
  "message": "User impersonation token generated successfully",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user_id": "507f1f77bcf86cd799439012",
    "user_name": "John Doe",
    "expires_in": "604800 seconds (7 days)"
  }
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ GET /api/admin/impersonate/read
├─ Status: 200 ✓
├─ Query Param: user_id visible
└─ Response: Token generated (value hidden)

Logs Tab:
├─ [INFO] Impersonation token generated for user: 507f1f77bcf86cd799439012
└─ Timestamp: Just now

⚠️ Notice: Token value is HIDDEN in Telescope for security!
```

**Step B: Use Impersonation Token (as User)**

**Request:**
```
GET http://localhost:8000/api/impersonate/dashboard
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/impersonate/dashboard
Headers:
  X-Impersonation-Token: {TOKEN_FROM_STEP_A}
```

**Expected Response:**
```
Status: 200
{
  "message": "User impersonation data retrieved successfully",
  "data": {
    "user_id": "507f1f77bcf86cd799439012",
    "name": "John Doe",
    "email": "john@example.com",
    "workspaces": [...],
    "teams": [...],
    "channels": [...],
    "messages": [...]
  }
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ GET /api/impersonate/dashboard
├─ Status: 200 ✓
├─ Headers: X-Impersonation-Token shown (value hidden)
└─ Response: User's data retrieved

Logs Tab:
├─ [INFO] User impersonation started: user_507f1f77bcf86cd799439012
├─ [INFO] Dashboard accessed by: John Doe
└─ [INFO] Retrieved 5 workspaces, 12 teams, 25 channels

Key Insight:
Access is now as "John Doe" (the impersonated user)
NOT as admin anymore!
```

---

### **Test 10: Validation Error**

**Request (Invalid Parameters):**
```
GET http://localhost:8000/api/admin/users/read?per_page=invalid&page=abc
```

**In Postman:**
```
Method: GET
URL: http://localhost:8000/api/admin/users/read?per_page=invalid&page=abc
Headers:
  Authorization: Bearer {YOUR_TOKEN}
```

**Expected Response:**
```
Status: 422 Unprocessable Entity
{
  "message": "Validation failed",
  "errors": {
    "page": ["The page must be numeric"],
    "per_page": ["The per_page must be numeric"]
  }
}
```

**Watch in Telescope:**
```
Requests Tab:
├─ Status: 422 ⚠️
├─ Duration: 35ms
└─ Response: Validation errors shown

Exceptions Tab:
├─ ValidationException
├─ File: CheckAdminReadValidationMiddleware.php:52
├─ Errors: 
│  ├─ page: "must be numeric"
│  └─ per_page: "must be numeric"
└─ Stack trace visible

Logs Tab:
└─ [ERROR] Validation failed for /api/admin/users/read
```

---

## 🎯 What to Look For - Real-Time Monitoring

### Dashboard Appearance

**When you send requests, you'll see:**

```
┌─────────────────────────────────────┐
│ TELESCOPE DASHBOARD                 │
├─────────────────────────────────────┤
│ Live Monitoring:                    │
│ ✓ Green request appeared instantly  │
│ ✓ "200 OK" status visible           │
│ ✓ Time: 125ms shown                 │
│ ✓ Query count: 2 databases hits      │
└─────────────────────────────────────┘
```

### Click Any Request to See:

**Basic Info:**
- Request method (GET, POST, etc.)
- URL and all query parameters
- Response status code
- Time taken in milliseconds

**Request Details:**
- Full headers sent
- Body (if POST/PUT)
- Authorization header (value hidden)

**Response Details:**
- Full response body (JSON formatted)
- Status code explanation
- Response headers

**Middleware:**
- What middleware ran
- Order of execution
- Timing for each

**Queries:**
- Every database query
- MongoDB syntax
- Time per query
- Slow query warnings (red)

---

## 📊 Sample Telescope Screen

```
LEFT SIDEBAR (Navigation):
├─ Requests ← Click here first
├─ Queries
├─ Logs
├─ Exceptions
├─ Gates
├─ Cache
├─ Events
├─ Jobs
└─ Mail

MAIN AREA (Request List):
GET  /api/admin/users/read        200  45ms
GET  /api/admin/users/read        401  5ms
POST /api/admin/login             200  125ms
GET  /api                          200  8ms
```

Click any row to expand and see full details.

---

## 🔍 Common Patterns You'll See

### Pattern 1: Successful Request
```
GET /api/admin/users/read
Status: 200 ✓
Queries: 2 (fast)
Time: 90ms
Middleware: All pass ✓
```

### Pattern 2: Authentication Failure
```
GET /api/admin/users/read
Status: 401 ⚠️
Queries: 0 (rejected before DB)
Time: 5ms (fast - no processing)
Exception: Unauthorized
```

### Pattern 3: Validation Error
```
GET /api/admin/users/read?per_page=abc
Status: 422 ⚠️
Queries: 1 (validate, then reject)
Time: 35ms
Exception: ValidationException
```

### Pattern 4: Slow Request
```
GET /api/admin/users/read
Status: 200 ✓
Queries: 51 (N+1 problem!) ❌
Time: 2500ms (very slow!)
Queries tab: Many repeated queries
Action: Need to optimize!
```

---

## ✅ Checklist - Testing Complete When:

- [ ] Telescope dashboard loads at `http://localhost:8000/telescope`
- [ ] You can see requests appear in real-time
- [ ] Clicking a request shows full details
- [ ] You can see database queries in Queries tab
- [ ] Authentication (token) works correctly
- [ ] Invalid requests show proper error codes
- [ ] Throttling appears after 31 requests
- [ ] Impersonation tokens are hidden (security)
- [ ] Logs show your API activities
- [ ] You understand request flow

---

## 🎓 Learning Path

**After completing tests above:**

1. **Optimize:** Find slow queries, add indexes
2. **Monitor:** Check requests during development
3. **Debug:** Use exceptions tab when errors occur
4. **Profile:** Compare request times before/after changes
5. **Track:** Monitor authentication flow
6. **Audit:** See all database operations

---

## 💡 Pro Tips

**Tip 1: Compare Requests**
- Send same request twice
- Check if second is faster (caching)
- Time difference visible in Telescope

**Tip 2: Find Bottlenecks**
- Look for requests > 500ms
- Check Queries tab for slow ones
- Optimize those first

**Tip 3: Monitor Auth Flow**
- Send request without token
- See failure in 5ms
- Send with token
- See success in 100ms
- Difference shows auth overhead

**Tip 4: Test Middleware Order**
- Middleware tab shows execution order
- Some middleware can be optimized
- Move expensive checks last

**Tip 5: Understand N+1 Problems**
- Load 10 users = should be 1 query (all together)
- If you see 10 queries = N+1 problem
- Telescope makes these obvious

---

## 🚨 Troubleshooting

### Problem: "Telescope URL not found"

**Solution:**
```bash
# Make sure server is running
php artisan serve

# Should show:
#   INFO  Server running on http://127.0.0.1:8000
```

Then visit: `http://localhost:8000/telescope`

### Problem: "No requests appearing"

**Solution:**
- Give Telescope 2-3 seconds to load
- Reload page with F5
- Send request again
- Check if request was actually sent

### Problem: "Can't see query details"

**Solution:**
- Click the main request first
- Then click **Queries** sub-tab
- Make sure you're looking at right request

### Problem: "Token value missing"

**Solution:**
- It's intentional! Tokens are hidden for security
- You can still see you sent a token
- See request was authenticated (200 OK response proves it)

---

## Next: Optimize Based on Findings

Once you've run these tests and understood Telescope:

1. **Identify slow requests** (> 500ms)
2. **Check query count** (should be minimal)
3. **Look for N+1 problems** (repeated queries)
4. **Add MongoDB indexes** to slow queries
5. **Use eager loading** with `with()`
6. **Retest and compare** using Telescope
7. **Document performance baselines**

---

**Ready to test? Start with Test 1 (Health Check) and work your way up! 🚀**
