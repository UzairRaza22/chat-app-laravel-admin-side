@echo off
echo 🚀 Starting Laravel Telescope Deployment...
echo.

REM Server details
set SERVER_HOST=178.104.58.236
set SERVER_USER=root
set PROJECT_PATH=/var/www/staging/admin_side/backend

echo 📋 Deployment Configuration:
echo    Server: %SERVER_HOST%
echo    User: %SERVER_USER%
echo    Path: %PROJECT_PATH%
echo    Telescope URL: http://%SERVER_HOST%:81/telescope
echo.

echo 🔄 Step 1: Copying files to server...
scp .env %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/.env
scp bootstrap/providers.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/bootstrap/providers.php
scp app/Providers/TelescopeServiceProvider.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/app/Providers/TelescopeServiceProvider.php
scp config/telescope.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/config/telescope.php

echo.
echo 🔄 Step 2: Installing Telescope on server...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && composer require dij-digital/telescope-mongodb --no-interaction"

echo.
echo 🔄 Step 3: Publishing Telescope assets...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan telescope:publish"

echo.
echo 🔄 Step 4: Clearing caches...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan cache:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:clear"

echo.
echo 🔄 Step 5: Optimizing for production...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:cache"

echo.
echo 🔄 Step 6: Setting permissions...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chown -R www-data:www-data storage bootstrap/cache"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && chmod -R 775 storage bootstrap/cache"

echo.
echo 🔄 Step 7: Restarting services...
ssh %SERVER_USER%@%SERVER_HOST% "systemctl reload nginx"
ssh %SERVER_USER%@%SERVER_HOST% "systemctl restart php8.2-fpm"

echo.
echo 🎉 Telescope Deployment Completed!
echo.
echo 📋 Access Information:
echo    🌐 Main Application: http://%SERVER_HOST%:81
echo    🔭 Telescope Dashboard: http://%SERVER_HOST%:81/telescope
echo    📊 API Health Check: http://%SERVER_HOST%:81/api/health
echo.
echo ⚠️  Open http://%SERVER_HOST%:81/telescope in your browser to access Telescope!
pause