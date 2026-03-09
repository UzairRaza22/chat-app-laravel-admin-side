# Admin Authentication Testing Guide

## Prerequisites

1. **Laravel Application Running**
   ```bash
   php artisan serve
   # Application should be running on http://localhost:8000
   ```

2. **Database Setup**
   ```bash
   php artisan migrate
   # Ensure MongoDB connection is working
   ```

3. **Postman Setup**
   - Import the `Admin_API_Testing.postman_collection.json` file
   - Create a new environment in Postman with these variables:
     - `base_url`: `http://localhost:8000`
     - `admin_token`: (will be auto-populated after login)
     - `workspace_id`: (set manually from workspace data)
     - `team_id`: (set manually from team data)
     - `channel_id`: (set manually from channel data)
     - `message_id`: (set manually from message data)
     - `user_id`: (set manually from user data)

## Testing Steps

### Step 1: Health Check
1. Run "API Health Check" request
2. **Expected Result**: 200 OK with status "ok"

### Step 2: Admin Authentication Flow

#### 2.1 Admin Signup
1. Run "Admin Signup" request
2. **Expected Result**: 200 OK with success message
3. **Note**: Check your email/logs for verification token

#### 2.2 Admin Verify Signup
1. Get the verification token from email/logs
2. Set it in the request body
3. Run "Admin Verify Signup" request
4. **Expected Result**: 200 OK with verification success

#### 2.3 Admin Login
1. Run "Admin Login" request
2. **Expected Result**: 200 OK with admin token
3. **Auto Action**: Token should be saved to `admin_token` environment variable

### Step 3: Admin Read Operations Testing

#### 3.1 Test All Read Endpoints
Run each of these requests in order:
1. "Read All Workspaces"
2. "Read All Teams"
3. "Read All Channels"
4. "Read All Messages"
5. "Read All Users"

**Expected Results for each:**
- 200 OK status
- JSON response with `success: true`
- `data` array containing resources (may be empty if no data exists)

#### 3.2 Test Specific Resource Reading
1. From the "Read All" responses, copy an ID from any resource
2. Set the appropriate environment variable (workspace_id, team_id, etc.)
3. Run the corresponding "Read Specific" request
4. **Expected Result**: 200 OK with single resource data

### Step 4: Impersonation Testing
1. Get a user ID from "Read All Users" response
2. Set `user_id` environment variable
3. Run "Get User for Impersonation"
4. Run "Stop Impersonation"
5. **Expected Results**: Both should return 200 OK

### Step 5: Error Handling Testing

#### 5.1 Test Without Authentication
1. Run "Test Without Token"
2. **Expected Result**: 401 Unauthorized with "Token is required" message

#### 5.2 Test With Invalid Token
1. Run "Test With Invalid Token"
2. **Expected Result**: 401 Unauthorized with "Invalid or expired token" message

#### 5.3 Test With Non-existent Resource
1. Run "Test With Non-existent Resource ID"
2. **Expected Result**: 404 Not Found or validation error

## Common Issues and Solutions

### Issue 1: "Token is required" Error
**Cause**: Missing Authorization header
**Solution**: Ensure the admin_token environment variable is set after login

### Issue 2: "Invalid or expired token" Error
**Cause**: Token expired or invalid
**Solution**: Re-run the admin login request to get a fresh token

### Issue 3: "Admin not found" Error
**Cause**: Admin account not properly created or activated
**Solution**: Check admin signup and verification process

### Issue 4: Validation Errors
**Cause**: Missing or invalid request parameters
**Solution**: Check request body format and required fields

### Issue 5: 500 Internal Server Error
**Cause**: Database connection or model issues
**Solution**: Check Laravel logs and database connectivity

## Expected Response Formats

### Successful Authentication Response
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "admin": {
            "id": "admin_id_here",
            "name": "Admin User",
            "email": "admin@test.com"
        }
    }
}
```

### Successful Read Response
```json
{
    "success": true,
    "message": "Workspace(s) retrieved successfully!",
    "data": [
        {
            "id": "workspace_id_here",
            "name": "Test Workspace",
            "description": "Test Description",
            "creator_id": "creator_id_here",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### Error Response
```json
{
    "success": false,
    "message": "Token is required."
}
```

## Performance Testing

### Load Testing Commands
```bash
# Test admin login endpoint
curl -X POST http://localhost:8000/api/admin/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"password123"}'

# Test admin read endpoint with token
curl -X GET http://localhost:8000/api/admin/workspaces/read \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## Debugging Tips

1. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Enable Query Logging** (in AppServiceProvider)
   ```php
   DB::listen(function ($query) {
       Log::info($query->sql, $query->bindings);
   });
   ```

3. **Check Middleware Execution**
   - Add logging in CheckAdminTokenMiddleware
   - Verify token validation process

4. **Database Verification**
   ```bash
   # Check if admin exists
   php artisan tinker
   >>> App\Models\Admin\Admin::all()
   
   # Check admin session tokens
   >>> App\Models\Admin\AdminSessionToken::all()
   ```

## Success Criteria

✅ **All tests pass if:**
- Health check returns 200 OK
- Admin can signup, verify, and login successfully
- Admin token is generated and saved
- All read endpoints return 200 OK with proper data structure
- Error handling works correctly (401 for missing/invalid tokens)
- Impersonation endpoints work properly
- No 500 internal server errors occur

❌ **Tests fail if:**
- Any endpoint returns 500 internal server error
- Authentication flow doesn't work
- Admin token is not generated or accepted
- Read operations don't return expected data structure
- Error handling doesn't work as expected