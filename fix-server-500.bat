@echo off
echo 🚨 Fixing HTTP 500 Error on Live Server...
echo.

REM Server details
set SERVER_HOST=178.104.58.236
set SERVER_USER=root
set PROJECT_PATH=/var/www/staging/admin_side/backend

echo 📋 Server Information:
echo    Server: %SERVER_HOST%
echo    User: %SERVER_USER%
echo    Path: %PROJECT_PATH%
echo.

echo 🔍 Step 1: Checking server logs...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && echo '=== LARAVEL LOG ===' && tail -20 storage/logs/laravel.log"
ssh %SERVER_USER%@%SERVER_HOST% "echo '=== PHP-FPM LOG ===' && tail -10 /var/log/php8.2-fpm.log"
ssh %SERVER_USER%@%SERVER_HOST% "echo '=== NGINX ERROR LOG ===' && tail -10 /var/log/nginx/error.log"

echo.
echo 🔄 Step 2: Backing up current configuration...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && cp .env .env.broken.backup"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && cp bootstrap/providers.php bootstrap/providers.php.broken.backup"

echo.
echo 🔄 Step 3: Uploading safe configuration files...
scp .env.production %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/.env
scp app/Providers/TelescopeServiceProvider.minimal.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/app/Providers/TelescopeServiceProvider.php

echo.
echo 🔄 Step 4: Removing Telescope temporarily to fix the error...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && sed -i '/TelescopeServiceProvider/d' bootstrap/providers.php"

echo.
echo 🔄 Step 5: Clearing all caches...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan cache:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan view:clear"

echo.
echo 🔄 Step 6: Testing basic Laravel functionality...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan --version"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:show app.name"

echo.
echo 🔄 Step 7: Fixing permissions...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chown -R www-data:www-data storage bootstrap/cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chmod -R 775 storage bootstrap/cache"

echo.
echo 🔄 Step 8: Restarting services...
ssh %SERVER_USER%@%SERVER_HOST% "systemctl reload nginx"
ssh %SERVER_USER%@%SERVER_HOST% "systemctl restart php8.2-fpm"

echo.
echo 🔄 Step 9: Testing the application...
echo Testing main application...
curl -I http://%SERVER_HOST%:81/

echo.
echo Testing API health endpoint...
curl -s http://%SERVER_HOST%:81/api/health

echo.
echo 🎉 Basic fix completed! 
echo.
echo 📋 Next Steps:
echo    1. Test: http://%SERVER_HOST%:81
echo    2. Test: http://%SERVER_HOST%:81/api/health  
echo    3. If working, we can re-enable Telescope safely
echo.
echo 🔧 If still getting 500 error, check the logs above for specific errors.
pause