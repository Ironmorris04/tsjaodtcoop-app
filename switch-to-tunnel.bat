@echo off
REM Switch environment to Cloudflare Tunnel mode
echo ========================================
echo Switching to Cloudflare Tunnel Mode
echo ========================================
echo.

REM Check if .env.local backup exists
if not exist ".env.local" (
    echo Creating backup of current .env as .env.local...
    copy .env .env.local >nul
    echo Backup created successfully!
) else (
    echo Backup .env.local already exists, skipping backup...
)

echo.
set /p TUNNEL_URL="Enter your Cloudflare Tunnel URL (e.g., https://your-app.trycloudflare.com): "

if "%TUNNEL_URL%"=="" (
    echo Error: Tunnel URL cannot be empty!
    pause
    exit /b 1
)

echo.
echo Updating .env file...

REM Create temporary file with updated values
(
    for /f "tokens=* delims=" %%a in (.env) do (
        set "line=%%a"
        setlocal enabledelayedexpansion

        REM Replace APP_URL
        if "!line:~0,8!"=="APP_URL=" (
            echo APP_URL=%TUNNEL_URL%
        ) else if "!line:~0,22!"=="SESSION_SECURE_COOKIE=" (
            echo SESSION_SECURE_COOKIE=true
        ) else if "!line:~0,17!"=="SESSION_SAME_SITE=" (
            echo SESSION_SAME_SITE=none
        ) else (
            echo !line!
        )
        endlocal
    )
) > .env.tmp

move /y .env.tmp .env >nul

echo.
echo ========================================
echo Configuration Updated Successfully!
echo ========================================
echo APP_URL set to: %TUNNEL_URL%
echo SESSION_SECURE_COOKIE set to: true
echo SESSION_SAME_SITE set to: none
echo.
echo IMPORTANT: Clear your application cache:
echo   php artisan config:clear
echo   php artisan cache:clear
echo   php artisan route:clear
echo   php artisan view:clear
echo.
echo To revert back to local configuration, run: switch-to-local.bat
echo ========================================
pause
