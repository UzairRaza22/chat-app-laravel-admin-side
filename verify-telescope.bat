@echo off
echo 🔍 TELESCOPE VERIFICATION SCRIPT
echo ================================
echo.

set SERVER_HOST=178.104.58.236

echo Testing all endpoints...
echo.

echo 🌐 Main Application:
curl -s -o nul -w "Status: %%{http_code} | Time: %%{time_total}s" http://%SERVER_HOST%:81/
echo.

echo 📊 API Health Check:
curl -s http://%SERVER_HOST%:81/api/health
echo.

echo 🔭 Telescope Dashboard:
curl -s -o nul -w "Status: %%{http_code} | Time: %%{time_total}s" http://%SERVER_HOST%:81/telescope
echo.

echo 🔐 Admin Auth Test:
curl -s -X POST http://%SERVER_HOST%:81/api/admin/auth/login -H "Content-Type: application/json" -d "{\"email\":\"admin@test.com\",\"password\":\"password123\"}" -w "Status: %%{http_code}"
echo.

echo.
echo 🎯 VERIFICATION COMPLETE!
echo.
echo If all status codes are 200 or 422 (for auth), everything is working!
echo Open http://%SERVER_HOST%:81/telescope to access your dashboard.
pause