@echo off
REM Transport Coop System - Windows Setup Script
REM This script automates the setup process for Windows systems

echo ========================================
echo Transport Coop System - Setup Script
echo ========================================
echo.

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP 8.1 or higher
    echo Download from: https://windows.php.net/download/
    pause
    exit /b 1
)

REM Check PHP version
echo [INFO] Checking PHP version...
php -v | findstr /C:"PHP 8"
if %ERRORLEVEL% NEQ 0 (
    echo [WARNING] PHP 8.1+ is recommended
)
echo.

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed or not in PATH
    echo Please install Composer from: https://getcomposer.org/
    pause
    exit /b 1
)

echo [OK] Composer is installed
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Node.js is not installed or not in PATH
    echo Please install Node.js from: https://nodejs.org/
    pause
    exit /b 1
)

echo [OK] Node.js is installed
echo.

REM Step 1: Install Composer dependencies
echo ========================================
echo Step 1: Installing PHP dependencies...
echo ========================================
call composer install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer install failed
    pause
    exit /b 1
)
echo [OK] PHP dependencies installed
echo.

REM Step 2: Install npm dependencies
echo ========================================
echo Step 2: Installing JavaScript dependencies...
echo ========================================
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] npm install failed
    pause
    exit /b 1
)
echo [OK] JavaScript dependencies installed
echo.

REM Step 3: Setup environment file
echo ========================================
echo Step 3: Setting up environment file...
echo ========================================
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo [OK] .env file created from .env.example
    ) else (
        echo [ERROR] .env.example file not found
        pause
        exit /b 1
    )
) else (
    echo [INFO] .env file already exists, skipping...
)
echo.

REM Step 4: Generate application key
echo ========================================
echo Step 4: Generating application key...
echo ========================================
php artisan key:generate
echo [OK] Application key generated
echo.

REM Step 5: Create database file for SQLite (optional)
echo ========================================
echo Step 5: Database setup...
echo ========================================
echo.
echo Choose your database:
echo 1. SQLite (simple, no server needed)
echo 2. MySQL (requires MySQL server)
echo.
set /p DB_CHOICE="Enter your choice (1 or 2): "

if "%DB_CHOICE%"=="1" (
    echo [INFO] Setting up SQLite database...
    if not exist database\database.sqlite (
        type nul > database\database.sqlite
        echo [OK] SQLite database file created
    ) else (
        echo [INFO] SQLite database file already exists
    )

    REM Update .env file for SQLite
    powershell -Command "(gc .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%CD%\database\database.sqlite' | Out-File -encoding ASCII .env"
    echo [OK] .env configured for SQLite

) else if "%DB_CHOICE%"=="2" (
    echo [INFO] Using MySQL database...
    echo Please ensure MySQL is running and update .env file with your credentials
    echo Default values:
    echo   DB_CONNECTION=mysql
    echo   DB_HOST=127.0.0.1
    echo   DB_PORT=3306
    echo   DB_DATABASE=transport_coop_system
    echo   DB_USERNAME=root
    echo   DB_PASSWORD=
    echo.
    set /p CREATE_DB="Do you want to create the MySQL database now? (y/n): "
    if /i "%CREATE_DB%"=="y" (
        set /p MYSQL_USER="Enter MySQL username (default: root): "
        if "%MYSQL_USER%"=="" set MYSQL_USER=root

        set /p MYSQL_PASS="Enter MySQL password: "

        echo CREATE DATABASE IF NOT EXISTS transport_coop_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; | mysql -u %MYSQL_USER% -p%MYSQL_PASS%
        if %ERRORLEVEL% EQU 0 (
            echo [OK] Database created successfully
        ) else (
            echo [ERROR] Failed to create database
            echo You may need to create it manually
        )
    )
) else (
    echo [WARNING] Invalid choice, skipping database setup
    echo Please configure database manually in .env file
)
echo.

REM Step 6: Run migrations
echo ========================================
echo Step 6: Running database migrations...
echo ========================================
set /p RUN_MIGRATIONS="Run migrations now? (y/n): "
if /i "%RUN_MIGRATIONS%"=="y" (
    php artisan migrate
    if %ERRORLEVEL% EQU 0 (
        echo [OK] Migrations completed successfully
    ) else (
        echo [ERROR] Migration failed
        echo Please check your database configuration in .env
    )
) else (
    echo [INFO] Skipping migrations - you can run them later with: php artisan migrate
)
echo.

REM Step 7: Create storage link
echo ========================================
echo Step 7: Creating storage symbolic link...
echo ========================================
php artisan storage:link
echo [OK] Storage link created
echo.

REM Step 8: Build frontend assets
echo ========================================
echo Step 8: Building frontend assets...
echo ========================================
echo.
echo Choose build mode:
echo 1. Development (faster, with source maps)
echo 2. Production (optimized, minified)
echo.
set /p BUILD_CHOICE="Enter your choice (1 or 2): "

if "%BUILD_CHOICE%"=="2" (
    call npm run build
    echo [OK] Production assets built
) else (
    call npm run dev
    echo [OK] Development assets built
)
echo.

REM Step 9: Create admin user (optional)
echo ========================================
echo Step 9: Admin user setup...
echo ========================================
set /p CREATE_ADMIN="Create admin user now? (y/n): "
if /i "%CREATE_ADMIN%"=="y" (
    echo Please run the following in Laravel Tinker:
    echo.
    echo   $user = new App\Models\User^(^);
    echo   $user-^>first_name = 'Admin';
    echo   $user-^>last_name = 'User';
    echo   $user-^>email = 'admin@tsjaodt.coop';
    echo   $user-^>password = bcrypt^('password123'^);
    echo   $user-^>role = 'admin';
    echo   $user-^>email_verified_at = now^(^);
    echo   $user-^>save^(^);
    echo.
    set /p OPEN_TINKER="Open Tinker now? (y/n): "
    if /i "%OPEN_TINKER%"=="y" (
        php artisan tinker
    )
)
echo.

REM Step 10: Clear caches
echo ========================================
echo Step 10: Clearing application caches...
echo ========================================
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo [OK] Caches cleared
echo.

REM Setup complete
echo ========================================
echo SETUP COMPLETE!
echo ========================================
echo.
echo Your application is ready to use.
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then visit: http://localhost:8000
echo.
echo Default admin credentials (if created):
echo   Email: admin@tsjaodt.coop
echo   Password: password123
echo.
echo IMPORTANT: Change the default password after first login!
echo.
echo For production deployment, see SETUP.md
echo.
pause
