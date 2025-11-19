@echo off
REM IR-IPPI App - Octane Startup Script for Windows
REM This script starts the Laravel Octane server with proper configuration

cd /d "%~dp0"

cls
echo ================================================
echo Starting IR-IPPI Application with Laravel Octane
echo ================================================
echo.

REM Check if PHP is installed
php -v >nul 2>&1
if errorlevel 1 (
    echo Error: PHP is not installed or not in PATH
    pause
    exit /b 1
)

REM Check if Composer dependencies are installed
if not exist "vendor" (
    echo Installing Composer dependencies...
    call composer install
    if errorlevel 1 (
        echo Error installing dependencies
        pause
        exit /b 1
    )
)

REM Clear caches
echo.
echo Clearing caches...
php artisan cache:clear
php artisan view:clear
php artisan config:clear

REM Run migrations if needed
if "%1"=="--migrate" (
    echo.
    echo Running migrations...
    php artisan migrate
)

REM Start Octane server
echo.
echo ================================================
echo Starting Octane server...
echo Server will be available at: http://localhost:8000
echo ================================================
echo.

php artisan octane:start --host=127.0.0.1 --port=8000 --workers=4

if errorlevel 1 (
    echo.
    echo Error: Failed to start Octane server
    echo Make sure you have installed RoadRunner:
    echo   php artisan octane:install --server=roadrunner
    pause
    exit /b 1
)

pause
