@echo off
echo 🔭 Safely Enabling Telescope (Run this ONLY after 500 error is fixed)...
echo.

REM Server details
set SERVER_HOST=178.104.58.236
set SERVER_USER=root
set PROJECT_PATH=/var/www/staging/admin_side/backend

echo 📋 Server Information:
echo    Server: %SERVER_HOST%
echo    Path: %PROJECT_PATH%
echo.

echo 🔄 Step 1: Checking if basic app is working...
curl -s -o nul -w "HTTP Status: %%{http_code}" http://%SERVER_HOST%:81/api/health
echo.

echo 🔄 Step 2: Installing Telescope MongoDB package...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && composer require dij-digital/telescope-mongodb --no-interaction"

echo.
echo 🔄 Step 3: Publishing Telescope assets...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan telescope:publish --force"

echo.
echo 🔄 Step 4: Adding TelescopeServiceProvider back...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && cp bootstrap/providers.php bootstrap/providers.php.backup"

REM Create a safe providers.php with Telescope
echo Creating safe providers.php...
(
echo ^<?php
echo.
echo return [
echo     App\Providers\AppServiceProvider::class,
echo     App\Providers\ResponseServiceProvider::class,
echo     App\Providers\TelescopeServiceProvider::class,
echo     MongoDB\Laravel\MongodbServiceProvider::class,
echo ];
) > temp_providers.php

scp temp_providers.php %SERVER_USER%@%SERVER_HOST%:%PROJECT_PATH%/bootstrap/providers.php
del temp_providers.php

echo.
echo 🔄 Step 5: Enabling Telescope in .env...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && sed -i 's/TELESCOPE_ENABLED=false/TELESCOPE_ENABLED=true/' .env"

echo.
echo 🔄 Step 6: Clearing caches...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan config:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan cache:clear"
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan route:clear"

echo.
echo 🔄 Step 7: Testing Telescope...
ssh %SERVER_USER%@%SERVER_HOST% "cd %PROJECT_PATH% && php artisan telescope:status"

echo.
echo 🔄 Step 8: Final test...
echo Testing main app...
curl -s -o nul -w "Main App HTTP Status: %%{http_code}" http://%SERVER_HOST%:81/
echo.

echo Testing Telescope...
curl -s -o nul -w "Telescope HTTP Status: %%{http_code}" http://%SERVER_HOST%:81/telescope
echo.

echo.
echo 🎉 Telescope Installation Complete!
echo.
echo 📋 Access URLs:
echo    🌐 Main App: http://%SERVER_HOST%:81
echo    🔭 Telescope: http://%SERVER_HOST%:81/telescope
echo    📊 API Health: http://%SERVER_HOST%:81/api/health
echo.
pause