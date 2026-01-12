@echo off
echo ========================================
echo  Cleaning up unnecessary files
echo ========================================
echo.

cd /d "c:\laragon\www\transport-coop-system"

echo [1/6] Removing node_modules folder...
if exist "node_modules" (
    rd /s /q "node_modules"
    echo      REMOVED: node_modules
) else (
    echo      SKIP: node_modules not found
)
echo.

echo [2/6] Removing test files and folders...
if exist "tests" (
    rd /s /q "tests"
    echo      REMOVED: tests folder
)
if exist "phpunit.xml" (
    del /q "phpunit.xml"
    echo      REMOVED: phpunit.xml
)
echo.

echo [3/6] Removing git files...
if exist ".git" (
    rd /s /q ".git"
    echo      REMOVED: .git folder
)
if exist ".gitignore" (
    del /q ".gitignore"
    echo      REMOVED: .gitignore
)
if exist ".gitattributes" (
    del /q ".gitattributes"
    echo      REMOVED: .gitattributes
)
echo.

echo [4/6] Removing development files...
if exist ".editorconfig" (
    del /q ".editorconfig"
    echo      REMOVED: .editorconfig
)
if exist ".styleci.yml" (
    del /q ".styleci.yml"
    echo      REMOVED: .styleci.yml
)
if exist "vite.config.js" (
    del /q "vite.config.js"
    echo      REMOVED: vite.config.js
)
if exist "package-lock.json" (
    del /q "package-lock.json"
    echo      REMOVED: package-lock.json
)
echo.

echo [5/6] Removing temporary/cache files...
if exist "storage\logs\*.log" (
    del /q "storage\logs\*.log"
    echo      REMOVED: Log files
)
if exist "bootstrap\cache\*.php" (
    del /q "bootstrap\cache\*.php"
    echo      REMOVED: Bootstrap cache
)
echo.

echo [6/6] Removing analysis/test scripts...
if exist "analyze_database_schema.php" (
    del /q "analyze_database_schema.php"
    echo      REMOVED: analyze_database_schema.php
)
if exist "analyze_system_functions.php" (
    del /q "analyze_system_functions.php"
    echo      REMOVED: analyze_system_functions.php
)
if exist "verify_model_relationships.php" (
    del /q "verify_model_relationships.php"
    echo      REMOVED: verify_model_relationships.php
)
if exist "test_critical_functions.php" (
    del /q "test_critical_functions.php"
    echo      REMOVED: test_critical_functions.php
)
if exist "cleanup_officer_accounts.php" (
    del /q "cleanup_officer_accounts.php"
    echo      REMOVED: cleanup_officer_accounts.php
)
if exist "cleanup_officer_accounts_auto.php" (
    del /q "cleanup_officer_accounts_auto.php"
    echo      REMOVED: cleanup_officer_accounts_auto.php
)
if exist "temp_operator_patch.txt" (
    del /q "temp_operator_patch.txt"
    echo      REMOVED: temp_operator_patch.txt
)
echo.

echo ========================================
echo  Cleanup Complete!
echo ========================================
echo.
echo Removed unnecessary files:
echo  - node_modules folder (can reinstall with npm)
echo  - Test files and folders
echo  - Git repository files
echo  - Development configuration files
echo  - Temporary cache and log files
echo  - Analysis and test scripts
echo.
echo Your system is now ready for deployment!
echo.
pause
