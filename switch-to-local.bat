@echo off
REM Switch environment back to Local mode
echo ========================================
echo Switching to Local Development Mode
echo ========================================
echo.

REM Check if .env.local backup exists
if not exist ".env.local" (
    echo Error: No .env.local backup found!
    echo Please manually configure your .env file.
    pause
    exit /b 1
)

echo Restoring .env from .env.local backup...
copy /y .env.local .env >nul

echo.
echo ========================================
echo Configuration Restored Successfully!
echo ========================================
echo Your .env has been restored to local configuration.
echo.
echo IMPORTANT: Clear your application cache:
echo   php artisan config:clear
echo   php artisan cache:clear
echo   php artisan route:clear
echo   php artisan view:clear
echo.
echo ========================================
pause
