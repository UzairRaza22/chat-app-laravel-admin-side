

echo "🚀 Starting Laravel Telescope Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Server details
SERVER_HOST="178.104.58.236"
SERVER_USER="root"
PROJECT_PATH="/var/www/staging/admin_side/backend"

echo -e "${BLUE}📋 Deployment Configuration:${NC}"
echo -e "   Server: ${SERVER_HOST}"
echo -e "   User: ${SERVER_USER}"
echo -e "   Path: ${PROJECT_PATH}"
echo -e "   Telescope URL: http://${SERVER_HOST}:81/telescope"
echo ""

# Function to execute commands on server
execute_remote() {
    echo -e "${YELLOW}🔧 Executing: $1${NC}"
    ssh ${SERVER_USER}@${SERVER_HOST} "cd ${PROJECT_PATH} && $1"
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Success${NC}"
    else
        echo -e "${RED}❌ Failed${NC}"
        exit 1
    fi
}

# Function to copy files to server
copy_file() {
    local_file=$1
    remote_file=$2
    echo -e "${YELLOW}📁 Copying: $local_file -> $remote_file${NC}"
    scp "$local_file" "${SERVER_USER}@${SERVER_HOST}:${PROJECT_PATH}/$remote_file"
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ File copied successfully${NC}"
    else
        echo -e "${RED}❌ File copy failed${NC}"
        exit 1
    fi
}

echo -e "${BLUE}🔄 Step 1: Backing up current configuration...${NC}"
execute_remote "cp .env .env.backup.$(date +%Y%m%d_%H%M%S)"
execute_remote "cp bootstrap/providers.php bootstrap/providers.php.backup"

echo -e "${BLUE}🔄 Step 2: Copying updated files...${NC}"
copy_file ".env" ".env"
copy_file "bootstrap/providers.php" "bootstrap/providers.php"
copy_file "app/Providers/TelescopeServiceProvider.php" "app/Providers/TelescopeServiceProvider.php"
copy_file "config/telescope.php" "config/telescope.php"

echo -e "${BLUE}🔄 Step 3: Installing/Updating Telescope...${NC}"
execute_remote "composer require dij-digital/telescope-mongodb --no-interaction"

echo -e "${BLUE}🔄 Step 4: Publishing Telescope assets...${NC}"
execute_remote "php artisan telescope:publish"

echo -e "${BLUE}🔄 Step 5: Clearing caches...${NC}"
execute_remote "php artisan config:clear"
execute_remote "php artisan cache:clear"
execute_remote "php artisan route:clear"
execute_remote "php artisan view:clear"

echo -e "${BLUE}🔄 Step 6: Optimizing for production...${NC}"
execute_remote "php artisan config:cache"
execute_remote "php artisan route:cache"
execute_remote "php artisan view:cache"

echo -e "${BLUE}🔄 Step 7: Setting proper permissions...${NC}"
execute_remote "chown -R www-data:www-data storage bootstrap/cache"
execute_remote "chmod -R 775 storage bootstrap/cache"

echo -e "${BLUE}🔄 Step 8: Restarting services...${NC}"
execute_remote "systemctl reload nginx"
execute_remote "systemctl restart php8.2-fpm"

echo -e "${BLUE}🔄 Step 9: Testing Telescope installation...${NC}"
execute_remote "php artisan telescope:status"

echo ""
echo -e "${GREEN}🎉 Telescope Deployment Completed Successfully!${NC}"
echo ""
echo -e "${BLUE}📋 Access Information:${NC}"
echo -e "   🌐 Main Application: http://${SERVER_HOST}:81"
echo -e "   🔭 Telescope Dashboard: http://${SERVER_HOST}:81/telescope"
echo -e "   📊 API Health Check: http://${SERVER_HOST}:81/api/health"
echo ""
echo -e "${BLUE}🔧 Telescope Features Enabled:${NC}"
echo -e "   ✅ Request Monitoring"
echo -e "   ✅ Database Query Tracking"
echo -e "   ✅ Exception Logging"
echo -e "   ✅ Mail Monitoring"
echo -e "   ✅ Cache Operations"
echo -e "   ✅ Job Queue Tracking"
echo -e "   ✅ Command Execution"
echo -e "   ✅ Event Monitoring"
echo ""
echo -e "${YELLOW}⚠️  Security Note:${NC}"
echo -e "   Telescope is currently open for debugging purposes."
echo -e "   Consider restricting access in production by updating"
echo -e "   the gate() method in TelescopeServiceProvider.php"
echo ""
echo -e "${GREEN}🚀 Ready to monitor your Laravel application!${NC}"