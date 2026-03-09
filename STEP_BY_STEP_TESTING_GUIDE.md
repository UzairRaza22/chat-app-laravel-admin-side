# Step-by-Step Admin API Testing Guide

## Prerequisites Setup

### 1. Start Your Laravel Application
```bash
php artisan serve
# Should run on http://localhost:8000
```

### 2. Create Admin Account (Using Tinker)
```bash
php artisan tinker
```

Run this command in tinker:
```php
$admin = App\Models\Admin\Admin::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com', 
    'password' => bcrypt('password123'),
    'is_active' => true
]);
```

### 3. Import Postman Files
1. Import `Complete_Admin_Testing.postman_collection.json`
2. Import `Admin_Testing_Environment.postman_environment.json`
3. Select "Admin Testing Environment" in Postman

## Testing Steps (Follow This Order)

### Step 1: Health Check ✅
**Folder**: `8. Health Check`
**Request**: `API Health Check`

**Expected Result**: 
```json
{
    "success": true,
    "data": {
        "status": "ok",
        "timestamp": "2024-01-01T00:00:00.000Z",
        "version": "1.0.0",
        "service": "Whistle IT API"
    }
}
```

### Step 2: Admin Authentication ✅
**Folder**: `1. Admin Authentication > Success Cases`

#### 2.1 Admin Login
**Request**: `Admin Login - Success`
**Expected Result**: 
- Status: 200 OK
- Response contains `access_token`
- Token automatically saved to environment

```json
{
    "success": true,
    "message": "Login successful!",
    "access_token": "your_token_here",
    "admin": {...}
}
```

#### 2.2 Test Authentication Failures
**Folder**: `1. Admin Authentication > Failure Cases`

Run these to verify error handling:
- `Admin Login - Wrong Password` → Should return 401/403
- `Admin Login - Non-existent Email` → Should return 404

### Step 3: Test All Read Operations ✅

#### 3.1 Workspaces Testing
**Folder**: `2. Workspaces > Success Cases`

1. **Read All Workspaces**
   - Request: `Read All Workspaces - Success`
   - Expected: 200 OK with workspace array
   - Auto-saves first workspace ID

2. **Read Specific Workspace**
   - Request: `Read Specific Workspace - Success`
   - Uses saved workspace_id
   - Expected: 200 OK with single workspace

**Folder**: `2. Workspaces > Failure Cases`

3. **Test Failures**:
   - `Read Workspaces - No Token` → 401 "Token is required"
   - `Read Workspaces - Invalid Token` → 401 "Invalid or expired token"
   - `Read Workspace - Non-existent ID` → 404 "Workspace not found"

#### 3.2 Teams Testing
**Folder**: `3. Teams`
- Follow same pattern as Workspaces
- Success cases should return team data
- Failure cases should return appropriate errors

#### 3.3 Channels Testing
**Folder**: `4. Channels`
- Follow same pattern as Workspaces
- Success cases should return channel data
- Failure cases should return appropriate errors

#### 3.4 Messages Testing
**Folder**: `5. Messages`
- Follow same pattern as Workspaces
- Success cases should return message data
- Failure cases should return appropriate errors

#### 3.5 Users Testing
**Folder**: `6. Users`
- Follow same pattern as Workspaces
- Success cases should return user data
- Failure cases should return appropriate errors

#### 3.6 Impersonation Testing
**Folder**: `7. Impersonation`

**Success Cases**:
1. `Get User for Impersonation - Success` → 200 OK with user data
2. `Stop Impersonation - Success` → 200 OK with success message

**Failure Cases**:
1. `Impersonate - No Token` → 401 Unauthorized
2. `Impersonate - Non-existent User` → 404 User not found

## Expected Response Formats

### Success Response Format
```json
{
    "success": true,
    "message": "Resource(s) retrieved successfully!",
    "data": [
        {
            "id": "resource_id",
            "name": "Resource Name",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### Error Response Formats
```json
// Authentication Error
{
    "message": "Token is required."
}

// Invalid Token
{
    "message": "Invalid or expired token."
}

// Resource Not Found
{
    "success": false,
    "message": "Workspace not found."
}
```

## Testing Checklist

### ✅ Authentication Tests
- [ ] Health check passes
- [ ] Admin login successful
- [ ] Token saved automatically
- [ ] Wrong password fails appropriately
- [ ] Non-existent email fails appropriately

### ✅ Authorization Tests (For Each Resource)
- [ ] No token returns 401
- [ ] Invalid token returns 401
- [ ] Valid token allows access

### ✅ Resource Tests (For Each Resource)
- [ ] Read all resources returns 200
- [ ] Read specific resource returns 200
- [ ] Non-existent resource ID returns 404
- [ ] Resource IDs auto-saved for next tests

### ✅ Data Validation
- [ ] Response format matches expected structure
- [ ] All required fields present in responses
- [ ] Error messages are clear and helpful

## Troubleshooting

### Issue: "Token is required"
**Solution**: Ensure admin login was successful and token is saved

### Issue: "Invalid or expired token"
**Solution**: Re-run admin login to get fresh token

### Issue: "Admin not found"
**Solution**: Verify admin account exists in database

### Issue: Empty data arrays
**Solution**: This is normal if no data exists - create some test data

### Issue: 500 Internal Server Error
**Solution**: Check Laravel logs: `tail -f storage/logs/laravel.log`

## Success Criteria

✅ **All tests pass if**:
- Health check returns 200
- Admin login returns token
- All read operations return 200 with proper structure
- All failure cases return appropriate error codes
- No 500 internal server errors

## Quick Commands for Testing

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check admin in database
php artisan tinker
>>> App\Models\Admin\Admin::where('email', 'admin@test.com')->first()

# Check admin tokens
>>> App\Models\Admin\AdminSessionToken::all()
```

Follow this guide step by step, and your admin API will be fully tested and verified!