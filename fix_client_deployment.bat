@echo off
echo ========================================
echo  Transport Coop System - Quick Fix
echo ========================================
echo.
echo This will fix common deployment issues:
echo  - Generate APP_KEY
echo  - Clear all caches
echo  - Create storage link
echo  - Verify configuration
echo.
pause
echo.

echo Changing to project directory...
cd /d "C:\laragon\www\transport-coop-system"

echo Current directory: %CD%
echo.

if not exist "artisan" (
    echo ========================================
    echo  ERROR: artisan file not found!
    echo ========================================
    echo.
    echo This means you're not in the right directory.
    echo.
    echo Please make sure:
    echo  1. Project is extracted to C:\laragon\www\transport-coop-system
    echo  2. The artisan file exists in that folder
    echo.
    echo Current location: %CD%
    echo.
    pause
    exit /b 1
)

echo ✓ Found artisan file - we're in the right directory!
echo.

echo [1/7] Checking .env file...
if not exist ".env" (
    echo    .env file is MISSING!
    echo    Copying from .env.example...
    copy ".env.example" ".env"
    echo    ✓ Created .env file
) else (
    echo    ✓ .env file exists
)
echo.

echo [2/7] Generating application key...
php artisan key:generate
echo.

echo [3/7] Clearing configuration cache...
php artisan config:clear
echo.

echo [4/7] Clearing application cache...
php artisan cache:clear
echo.

echo [5/7] Clearing view cache...
php artisan view:clear
echo.

echo [6/7] Clearing route cache...
php artisan route:clear
echo.

echo [7/7] Creating storage link...
php artisan storage:link
echo.

echo ========================================
echo  Fix Complete!
echo ========================================
echo.
echo Your .env configuration:
echo ----------------------------------------
findstr /C:"APP_KEY=" .env
findstr /C:"DB_DATABASE=" .env
findstr /C:"DB_USERNAME=" .env
echo ----------------------------------------
echo.
echo Next steps:
echo  1. Make sure Laragon is running (green icons)
echo  2. Open browser
echo  3. Go to: http://localhost/transport-coop-system/public
echo  4. You should see the login page
echo.
echo If still not working:
echo  - Check Laragon MySQL is running (green icon)
echo  - Verify database "transportcoop" exists in phpMyAdmin
echo  - Check browser console (F12) for errors
echo.
pause
