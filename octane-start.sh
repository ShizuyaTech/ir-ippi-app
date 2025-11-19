#!/bin/bash

# IR-IPPI App - Octane Startup Script
# This script starts the Laravel Octane server with proper configuration

cd "$(dirname "$0")" || exit

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Starting IR-IPPI Application with Laravel Octane...${NC}"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}Error: PHP is not installed or not in PATH${NC}"
    exit 1
fi

# Check if Composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo -e "${YELLOW}Installing Composer dependencies...${NC}"
    composer install
fi

# Clear caches
echo -e "${YELLOW}Clearing caches...${NC}"
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Run migrations if needed
if [ "$1" = "--migrate" ]; then
    echo -e "${YELLOW}Running migrations...${NC}"
    php artisan migrate
fi

# Start Octane server
echo -e "${YELLOW}Starting Octane server...${NC}"
echo -e "${GREEN}Server will be available at: http://0.0.0.0:8000${NC}"
echo ""

php artisan octane:start \
    --server=roadrunner \
    --host=0.0.0.0 \
    --port=8000 \
    --workers=4 \
    --task-workers=6

# If we reach here, server was stopped
echo -e "${RED}Octane server stopped${NC}"
