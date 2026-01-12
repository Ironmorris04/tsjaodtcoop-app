# Production Deployment Checklist
## Transport Cooperative Management System

Use this checklist when deploying the system to a production server.

---

## Pre-Deployment Checklist

### 1. Server Preparation
- [ ] Server meets minimum requirements (PHP 8.2+, MySQL 8.0+)
- [ ] SSL certificate installed (HTTPS enabled)
- [ ] Domain name configured and pointing to server
- [ ] Firewall configured (allow ports 80, 443)
- [ ] Backup server access credentials documented

### 2. Code Preparation
- [ ] All features tested in staging environment
- [ ] No debug code or console.log statements
- [ ] All environment-specific code removed
- [ ] Database migrations tested and verified
- [ ] Git repository up to date (if using version control)

### 3. Security Review
- [ ] All default passwords changed
- [ ] Sensitive files not in public directory
- [ ] `.env` file not tracked in git
- [ ] Debug mode disabled
- [ ] Security headers configured
- [ ] CSRF protection enabled
- [ ] SQL injection prevention verified

---

## Deployment Steps

### Step 1: Prepare Production Environment

#### A. Upload Files
```bash
# Using FTP/SFTP or Git
# Exclude: vendor/, node_modules/, .env, storage/logs/*

# Recommended structure:
/var/www/your-domain/
├── public/          # Web root (point domain here)
├── app/
├── resources/
├── database/
└── ...
```

#### B. Install Dependencies
```bash
# SSH into server
cd /var/www/your-domain

# Install PHP dependencies (production only)
composer install --optimize-autoloader --no-dev

# Install and build frontend assets
npm install
npm run build
```

### Step 2: Configure Environment

#### A. Create .env File
```bash
# Copy template
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### B. Update .env Settings
```env
# Application
APP_NAME="Transport Cooperative System"
APP_ENV=production
APP_DEBUG=false  # IMPORTANT: Must be false in production!
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transport_coop_prod
DB_USERNAME=your_db_user
DB_PASSWORD=strong_secure_password_here

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail (if using email notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# Timezone
APP_TIMEZONE=Asia/Manila
```

### Step 3: Set Up Database

#### A. Create Production Database
```sql
CREATE DATABASE transport_coop_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'coop_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON transport_coop_prod.* TO 'coop_user'@'localhost';
FLUSH PRIVILEGES;
```

#### B. Run Migrations
```bash
# Fresh installation
php artisan migrate --force

# With existing data
mysql -u coop_user -p transport_coop_prod < backup.sql
```

### Step 4: Set Permissions

#### Linux/Unix Permissions
```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/your-domain

# Set directory permissions
sudo find /var/www/your-domain -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/your-domain -type f -exec chmod 644 {} \;

# Set storage and cache permissions
sudo chmod -R 775 /var/www/your-domain/storage
sudo chmod -R 775 /var/www/your-domain/bootstrap/cache
```

### Step 5: Configure Web Server

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName your-domain.com
    ServerAlias www.your-domain.com

    DocumentRoot /var/www/your-domain/public

    <Directory /var/www/your-domain/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/coop-error.log
    CustomLog ${APACHE_LOG_DIR}/coop-access.log combined

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
    SSLCertificateChainFile /path/to/your/chain.crt
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/your-domain/public;

    index index.php index.html;

    # SSL Configuration
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 6: Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Step 7: Set Up Storage

```bash
# Create symbolic link for storage
php artisan storage:link

# Ensure storage directories exist
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
```

### Step 8: Configure Automated Backups

#### Create Backup Script
```bash
#!/bin/bash
# /var/www/scripts/backup-coop.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/coop"
DB_NAME="transport_coop_prod"
DB_USER="coop_user"
DB_PASS="your_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup uploaded files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/your-domain/storage/app/public

# Keep only last 30 days of backups
find $BACKUP_DIR -name "db_*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "files_*.tar.gz" -mtime +30 -delete

# Upload to cloud storage (optional)
# rclone copy $BACKUP_DIR remote:backups/coop
```

#### Set Up Cron Job
```bash
# Edit crontab
sudo crontab -e

# Add daily backup at 2 AM
0 2 * * * /var/www/scripts/backup-coop.sh >> /var/log/coop-backup.log 2>&1
```

---

## Post-Deployment Checklist

### 1. Functionality Testing
- [ ] Homepage loads correctly
- [ ] All user roles can login
- [ ] Registration system works
- [ ] File uploads work
- [ ] Database queries execute properly
- [ ] Reports generate successfully
- [ ] Email notifications send (if configured)

### 2. Security Testing
- [ ] HTTPS working on all pages
- [ ] HTTP redirects to HTTPS
- [ ] SQL injection protection working
- [ ] XSS protection working
- [ ] CSRF tokens present on forms
- [ ] File upload restrictions working
- [ ] Direct file access blocked (.env, etc.)

### 3. Performance Testing
- [ ] Page load times acceptable (< 3 seconds)
- [ ] Database queries optimized
- [ ] Images optimized and loading
- [ ] Caching working properly
- [ ] No memory leaks

### 4. User Acceptance Testing
- [ ] Admin can manage users
- [ ] Treasurer can record transactions
- [ ] President can manage meetings
- [ ] Operators can update profiles
- [ ] All reports accessible and accurate

### 5. Backup Testing
- [ ] Automated backup runs successfully
- [ ] Backup files are valid
- [ ] Restore procedure tested and works
- [ ] Backup storage has sufficient space

### 6. Monitoring Setup
- [ ] Error logging configured
- [ ] Log rotation configured
- [ ] Disk space monitoring
- [ ] Uptime monitoring (optional)
- [ ] Performance monitoring (optional)

---

## Security Hardening

### File Permissions
```bash
# Secure .env file
chmod 600 .env

# Secure storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Protect sensitive files
chmod 600 config/database.php
```

### Disable Dangerous PHP Functions
In `php.ini`:
```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

### Configure Security Headers
Add to `.htaccess` (Apache):
```apache
# Security Headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
```

### Rate Limiting
Laravel's built-in throttling is already configured. Monitor logs for abuse.

---

## Monitoring & Maintenance

### Daily Checks
```bash
# Check system status
php artisan about

# Check disk space
df -h

# Check recent errors
tail -100 storage/logs/laravel.log

# Check backup status
ls -lh /var/backups/coop
```

### Weekly Tasks
- Review audit trail for suspicious activity
- Check database size and performance
- Review error logs
- Test backup restoration
- Update dependencies (if needed)

### Monthly Tasks
- Security audit
- Performance optimization
- Database cleanup/optimization
- Review user accounts
- Update documentation

### Log Files to Monitor
- `/var/log/apache2/error.log` (Apache errors)
- `/var/log/nginx/error.log` (Nginx errors)
- `/var/www/your-domain/storage/logs/laravel.log` (Application errors)
- `/var/log/mysql/error.log` (Database errors)

---

## Rollback Plan

If something goes wrong during deployment:

### Quick Rollback Steps
```bash
# 1. Switch to maintenance mode
php artisan down

# 2. Restore previous code version
# (Using Git)
git checkout previous-version-tag

# 3. Restore database backup
mysql -u coop_user -p transport_coop_prod < backup_before_deployment.sql

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 5. Bring system back online
php artisan up
```

---

## Maintenance Mode

### Enable Maintenance Mode
```bash
# With custom message
php artisan down --message="System maintenance in progress" --retry=60

# With secret bypass token
php artisan down --secret="maintenance-bypass-token"
# Access via: https://your-domain.com/maintenance-bypass-token
```

### Disable Maintenance Mode
```bash
php artisan up
```

---

## Emergency Contacts

| Role | Name | Contact | Email |
|------|------|---------|-------|
| System Administrator | | | |
| Database Administrator | | | |
| Hosting Provider Support | | | |
| Developer | | | |

---

## Final Pre-Launch Checklist

- [ ] All deployment steps completed
- [ ] All post-deployment tests passed
- [ ] Automated backups configured and tested
- [ ] Monitoring and alerting set up
- [ ] Admin credentials secured and documented
- [ ] Rollback plan tested
- [ ] Emergency contacts documented
- [ ] User training scheduled
- [ ] Go-live announcement prepared
- [ ] Support plan in place

---

## Launch Day Protocol

1. **Pre-Launch (1 day before)**
   - [ ] Final backup of staging system
   - [ ] Notify users of launch time
   - [ ] Prepare support team

2. **Launch Day**
   - [ ] Deploy during low-traffic hours (if possible)
   - [ ] Monitor closely for first 4 hours
   - [ ] Be ready for quick rollback
   - [ ] Have support team on standby

3. **Post-Launch (First Week)**
   - [ ] Daily monitoring
   - [ ] Quick response to issues
   - [ ] Collect user feedback
   - [ ] Document any issues and resolutions

---

## Success Criteria

System deployment is successful when:
- [ ] All users can access the system
- [ ] No critical errors in logs
- [ ] All core features working
- [ ] Performance meets requirements
- [ ] Backups running automatically
- [ ] Users trained and comfortable
- [ ] Support processes in place

---

**Remember**: Always test in a staging environment before deploying to production!

---

*Last Updated: November 2025*
