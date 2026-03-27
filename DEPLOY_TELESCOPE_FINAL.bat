@echo off
echo 🔭 FINAL TELESCOPE DEPLOYMENT - PRODUCTION READY
echo ================================================
echo.

REM Server Configuration
set SERVER_HOST=178.104.58.236
set SERVER_USER=root
set PROJECT_PATH=/var/www/staging/admin_side/backend

echo 📋 Deployment Information:
echo    Server: %SERVER_HOST%:81
echo    Project: %PROJECT_PATH%
echo    Telescope URL: http://%SERVER_HOST%:81/telescope
echo.

echo 🔄 Step 1: Uploading production-ready files...
scp .env %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/.env
scp bootstrap/providers.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/bootstrap/providers.php
scp app/Providers/TelescopeServiceProvider.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/app/Providers/TelescopeServiceProvider.php
scp config/telescope.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/config/telescope.php

echo.
echo 🔄 Step 2: Installing Telescope MongoDB package...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && composer require dij-digital/telescope-mongodb --no-interaction --optimize-autoloader"

echo.
echo 🔄 Step 3: Publishing Telescope assets...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan telescope:publish --force"

echo.
echo 🔄 Step 4: Clearing all caches...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan cache:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan view:clear"

echo.
echo 🔄 Step 5: Optimizing for production...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan view:cache"

echo.
echo 🔄 Step 6: Setting proper permissions...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chown -R www-data:www-data storage bootstrap/cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chmod -R 775 storage bootstrap/cache"

echo.
echo 🔄 Step 7: Restarting web services...
ssh %SERVER_USER%@%SERVER_HOST% "systemctl reload nginx"
ssh %SERVER_USER%@%SERVER_HOST% "systemctl restart php8.2-fpm"

echo.
echo 🔄 Step 8: Verifying Telescope installation...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan telescope:status"

echo.
echo 🔄 Step 9: Testing endpoints...
echo Testing main application...
curl -s -o nul -w "Main App: HTTP %%{http_code}" http://%SERVER_HOST%:81/
echo.

echo Testing API health...
curl -s -o nul -w "API Health: HTTP %%{http_code}" http://%SERVER_HOST%:81/api/health
echo.

echo Testing Telescope dashboard...
curl -s -o nul -w "Telescope: HTTP %%{http_code}" http://%SERVER_HOST%:81/telescope
echo.

echo.
echo 🎉 TELESCOPE DEPLOYMENT COMPLETED SUCCESSFULLY!
echo ===============================================
echo.
echo 🌐 ACCESS URLS:
echo    Main Application: http://%SERVER_HOST%:81
echo    Telescope Dashboard: http://%SERVER_HOST%:81/telescope
echo    API Health Check: http://%SERVER_HOST%:81/api/health
echo.
echo 🔭 TELESCOPE FEATURES ENABLED:
echo    ✅ Request Monitoring (All HTTP requests)
echo    ✅ Database Query Tracking (MongoDB operations)
echo    ✅ Exception Logging (Error tracking)
echo    ✅ Mail Monitoring (Email sending)
echo    ✅ Cache Operations (Cache hit/miss)
echo    ✅ Job Queue Tracking (Background jobs)
echo    ✅ Command Execution (Artisan commands)
echo    ✅ Event Monitoring (Laravel events)
echo    ✅ Model Operations (Eloquent queries)
echo    ✅ Performance Metrics (Response times)
echo.
echo 💡 NEXT STEPS:
echo    1. Open http://%SERVER_HOST%:81/telescope in your browser
echo    2. Make some API calls to generate monitoring data
echo    3. Explore different tabs: Requests, Queries, Exceptions, etc.
echo.
echo 🛡️ SECURITY NOTE:
echo    Telescope is currently open for debugging.
echo    Consider restricting access in production.
echo.
echo 🚀 Your Laravel Telescope is now LIVE and ready!
pause