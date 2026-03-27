#!/bin/bash

# Telescope Testing Script
# Tests the live server Telescope installation

echo "🔭 Testing Laravel Telescope on Live Server..."

SERVER_URL="http://178.104.58.236:81"
TELESCOPE_URL="${SERVER_URL}/telescope"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Test function
test_endpoint() {
    local url=$1
    local description=$2
    
    echo -e "${YELLOW}Testing: $description${NC}"
    echo -e "URL: $url"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$url")
    
    if [ "$response" = "200" ]; then
        echo -e "${GREEN}✅ SUCCESS - HTTP $response${NC}"
    else
        echo -e "${RED}❌ FAILED - HTTP $response${NC}"
    fi
    echo ""
}

echo -e "${BLUE}🚀 Starting Telescope Tests...${NC}"
echo ""

# Test 1: Main application
test_endpoint "$SERVER_URL" "Main Application"

# Test 2: API Health Check
test_endpoint "$SERVER_URL/api/health" "API Health Check"

# Test 3: Telescope Dashboard
test_endpoint "$TELESCOPE_URL" "Telescope Dashboard"

# Test 4: Admin Auth Endpoint
echo -e "${YELLOW}Testing: Admin Authentication${NC}"
echo -e "URL: $SERVER_URL/api/admin/auth/login"

auth_response=$(curl -s -X POST "$SERVER_URL/api/admin/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"password123"}' \
  -w "%{http_code}")

if [[ "$auth_response" == *"200"* ]] || [[ "$auth_response" == *"422"* ]]; then
    echo -e "${GREEN}✅ SUCCESS - Auth endpoint responding${NC}"
else
    echo -e "${RED}❌ FAILED - Auth endpoint not responding${NC}"
fi
echo ""

# Test 5: Generate some traffic for Telescope
echo -e "${YELLOW}Generating test traffic for Telescope monitoring...${NC}"

for i in {1..5}; do
    curl -s "$SERVER_URL/api/health" > /dev/null
    curl -s "$SERVER_URL" > /dev/null
    echo -e "Generated request $i/5"
done

echo ""
echo -e "${GREEN}🎉 Testing Complete!${NC}"
echo ""
echo -e "${BLUE}📋 Access Your Telescope Dashboard:${NC}"
echo -e "   🔗 $TELESCOPE_URL"
echo ""
echo -e "${BLUE}💡 What to Check in Telescope:${NC}"
echo -e "   📊 Requests tab - See HTTP requests"
echo -e "   🗃️  Queries tab - Monitor database operations"
echo -e "   ⚠️  Exceptions tab - Track errors"
echo -e "   📧 Mail tab - Monitor email sending"
echo -e "   🔧 Commands tab - See artisan commands"
echo ""
echo -e "${YELLOW}⚡ Pro Tip:${NC} Make some API calls to see real-time monitoring!"