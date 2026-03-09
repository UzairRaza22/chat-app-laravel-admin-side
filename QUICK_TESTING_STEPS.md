# Quick Testing Steps for Admin Authentication

## Step 1: Create Admin Account Manually (Skip Email Verification)

Since email verification might be complex to test, let's create an admin account directly in the database:

### Option A: Using Tinker (Recommended)
```bash
php artisan tinker
```

Then run these commands:
```php
// Create admin
$admin = App\Models\Admin\Admin::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password123'),
    'is_active' => true
]);

// Verify admin was created
App\Models\Admin\Admin::where('email', 'admin@test.com')->first();
```

### Option B: Using Database Seeder
Create a seeder file:
```bash
php artisan make:seeder AdminSeeder
```

## Step 2: Test Admin Login in Postman

### Request Details:
- **Method**: POST
- **URL**: `http://localhost:8000/api/admin/auth/login`
- **Headers**: 
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body** (raw JSON):
```json
{
    "email": "admin@test.com",
    "password": "password123"
}
```

### Expected Response:
```json
{
    "success": true,
    "message": "Login successful!",
    "access_token": "XYZ123ABC456...",
    "admin": {
        "id": "admin_id_here",
        "name": "Test Admin",
        "email": "admin@test.com"
    }
}
```

## Step 3: Copy the Token

From the login response, copy the **`access_token`** value (not the whole response, just the token string).

## Step 4: Test Admin Read Operations

### Request Details:
- **Method**: GET
- **URL**: `http://localhost:8000/api/admin/workspaces/read`
- **Headers**: 
  - `Authorization: Bearer {paste_your_access_token_here}`
  - `Accept: application/json`

### Expected Response:
```json
{
    "success": true,
    "message": "Workspace(s) retrieved successfully!",
    "data": []
}
```

## Important Notes:

1. **Token Format**: The token is a plain text string, not JWT
2. **Token Storage**: The system stores a hashed version in the database
3. **Token Type**: For read operations, we use `admin_login_token` type
4. **Token Validation**: The middleware hashes your token and compares with stored hash

## Common Issues:

### Issue: "Token is required"
- **Cause**: Missing Authorization header
- **Solution**: Add `Authorization: Bearer {your_token}` header

### Issue: "Invalid or expired token"
- **Cause**: Wrong token or token not found in database
- **Solution**: Re-login to get a fresh token

### Issue: "Admin not found"
- **Cause**: Admin account doesn't exist or is inactive
- **Solution**: Create admin account with `is_active: true`

## Testing Checklist:

- [ ] Admin account created and active
- [ ] Admin login successful
- [ ] Token received in response
- [ ] Token copied correctly (no extra spaces/characters)
- [ ] Authorization header set correctly
- [ ] Read operations return 200 OK