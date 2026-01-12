#!/bin/bash

# Transport Coop System - Linux/Mac Setup Script
# This script automates the setup process for Linux and macOS systems

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored messages
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[OK]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo ""
    echo "========================================"
    echo "$1"
    echo "========================================"
    echo ""
}

# Check if running with proper permissions
if [ "$EUID" -eq 0 ]; then
    print_warning "Please do not run this script as root/sudo"
    print_info "Run it as a normal user: ./setup.sh"
    exit 1
fi

print_header "Transport Coop System - Setup Script"

# Check if PHP is installed
print_info "Checking for PHP..."
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed"
    print_info "Install PHP 8.1+ with: sudo apt-get install php8.1 (Ubuntu/Debian)"
    print_info "Or: brew install php@8.1 (macOS)"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
print_success "PHP $PHP_VERSION is installed"

# Check if Composer is installed
print_info "Checking for Composer..."
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed"
    print_info "Install from: https://getcomposer.org/"
    exit 1
fi
print_success "Composer is installed"

# Check if Node.js is installed
print_info "Checking for Node.js..."
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed"
    print_info "Install from: https://nodejs.org/"
    exit 1
fi

NODE_VERSION=$(node -v)
print_success "Node.js $NODE_VERSION is installed"

# Check if npm is installed
print_info "Checking for npm..."
if ! command -v npm &> /dev/null; then
    print_error "npm is not installed"
    exit 1
fi
print_success "npm is installed"

# Step 1: Install Composer dependencies
print_header "Step 1: Installing PHP dependencies..."
composer install
if [ $? -ne 0 ]; then
    print_error "Composer install failed"
    exit 1
fi
print_success "PHP dependencies installed"

# Step 2: Install npm dependencies
print_header "Step 2: Installing JavaScript dependencies..."
npm install
if [ $? -ne 0 ]; then
    print_error "npm install failed"
    exit 1
fi
print_success "JavaScript dependencies installed"

# Step 3: Setup environment file
print_header "Step 3: Setting up environment file..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        print_success ".env file created from .env.example"
    else
        print_error ".env.example file not found"
        exit 1
    fi
else
    print_info ".env file already exists, skipping..."
fi

# Step 4: Generate application key
print_header "Step 4: Generating application key..."
php artisan key:generate
print_success "Application key generated"

# Step 5: Database setup
print_header "Step 5: Database setup..."
echo ""
echo "Choose your database:"
echo "1. SQLite (simple, no server needed)"
echo "2. MySQL (requires MySQL server)"
echo ""
read -p "Enter your choice (1 or 2): " DB_CHOICE

if [ "$DB_CHOICE" = "1" ]; then
    print_info "Setting up SQLite database..."

    # Create database file
    if [ ! -f database/database.sqlite ]; then
        touch database/database.sqlite
        print_success "SQLite database file created"
    else
        print_info "SQLite database file already exists"
    fi

    # Update .env file for SQLite
    sed -i.bak 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
    sed -i.bak "s|DB_DATABASE=.*|DB_DATABASE=$(pwd)/database/database.sqlite|" .env

    # Remove MySQL-specific lines from .env
    sed -i.bak '/DB_HOST=/d' .env
    sed -i.bak '/DB_PORT=/d' .env
    sed -i.bak '/DB_USERNAME=/d' .env
    sed -i.bak '/DB_PASSWORD=/d' .env

    print_success ".env configured for SQLite"

elif [ "$DB_CHOICE" = "2" ]; then
    print_info "Using MySQL database..."
    echo "Please ensure MySQL is running and update .env file with your credentials"
    echo "Default values:"
    echo "  DB_CONNECTION=mysql"
    echo "  DB_HOST=127.0.0.1"
    echo "  DB_PORT=3306"
    echo "  DB_DATABASE=transport_coop_system"
    echo "  DB_USERNAME=root"
    echo "  DB_PASSWORD="
    echo ""

    read -p "Do you want to create the MySQL database now? (y/n): " CREATE_DB
    if [ "$CREATE_DB" = "y" ] || [ "$CREATE_DB" = "Y" ]; then
        read -p "Enter MySQL username (default: root): " MYSQL_USER
        MYSQL_USER=${MYSQL_USER:-root}

        read -sp "Enter MySQL password: " MYSQL_PASS
        echo ""

        mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "CREATE DATABASE IF NOT EXISTS transport_coop_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

        if [ $? -eq 0 ]; then
            print_success "Database created successfully"

            # Update .env with MySQL credentials
            sed -i.bak "s/DB_USERNAME=.*/DB_USERNAME=$MYSQL_USER/" .env
            sed -i.bak "s/DB_PASSWORD=.*/DB_PASSWORD=$MYSQL_PASS/" .env
        else
            print_error "Failed to create database"
            print_info "You may need to create it manually"
        fi
    fi
else
    print_warning "Invalid choice, skipping database setup"
    print_info "Please configure database manually in .env file"
fi

# Step 6: Run migrations
print_header "Step 6: Running database migrations..."
read -p "Run migrations now? (y/n): " RUN_MIGRATIONS
if [ "$RUN_MIGRATIONS" = "y" ] || [ "$RUN_MIGRATIONS" = "Y" ]; then
    php artisan migrate
    if [ $? -eq 0 ]; then
        print_success "Migrations completed successfully"
    else
        print_error "Migration failed"
        print_info "Please check your database configuration in .env"
    fi
else
    print_info "Skipping migrations - you can run them later with: php artisan migrate"
fi

# Step 7: Set permissions
print_header "Step 7: Setting file permissions..."
chmod -R 775 storage bootstrap/cache
print_success "Permissions set for storage and cache directories"

# Step 8: Create storage link
print_header "Step 8: Creating storage symbolic link..."
php artisan storage:link
print_success "Storage link created"

# Step 9: Build frontend assets
print_header "Step 9: Building frontend assets..."
echo ""
echo "Choose build mode:"
echo "1. Development (faster, with source maps)"
echo "2. Production (optimized, minified)"
echo ""
read -p "Enter your choice (1 or 2): " BUILD_CHOICE

if [ "$BUILD_CHOICE" = "2" ]; then
    npm run build
    print_success "Production assets built"
else
    npm run dev
    print_success "Development assets built"
fi

# Step 10: Admin user setup
print_header "Step 10: Admin user setup..."
read -p "Create admin user now? (y/n): " CREATE_ADMIN
if [ "$CREATE_ADMIN" = "y" ] || [ "$CREATE_ADMIN" = "Y" ]; then
    echo ""
    print_info "Creating admin user via Tinker..."

    php artisan tinker --execute="
        \$user = new App\Models\User();
        \$user->first_name = 'Admin';
        \$user->last_name = 'User';
        \$user->email = 'admin@tsjaodt.coop';
        \$user->password = bcrypt('password123');
        \$user->role = 'admin';
        \$user->email_verified_at = now();
        \$user->save();
        echo 'Admin user created successfully';
    "

    if [ $? -eq 0 ]; then
        print_success "Admin user created"
    else
        print_error "Failed to create admin user"
        print_info "You can create it manually later using: php artisan tinker"
    fi
fi

# Step 11: Clear caches
print_header "Step 11: Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

# Setup complete
print_header "SETUP COMPLETE!"

echo ""
echo -e "${GREEN}Your application is ready to use.${NC}"
echo ""
echo "To start the development server, run:"
echo -e "  ${BLUE}php artisan serve${NC}"
echo ""
echo "Then visit: http://localhost:8000"
echo ""
echo "Default admin credentials (if created):"
echo "  Email: admin@tsjaodt.coop"
echo "  Password: password123"
echo ""
echo -e "${YELLOW}IMPORTANT: Change the default password after first login!${NC}"
echo ""
echo "For production deployment, see SETUP.md"
echo ""
