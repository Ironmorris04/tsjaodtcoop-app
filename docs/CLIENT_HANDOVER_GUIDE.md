# Client Handover Guide
## Transport Cooperative Management System

**Version:** 1.0.0
**Date:** November 2025
**Client:** Tacloban San Jose Airport Operators Drivers Transport Cooperative (TSJAODT)

---

## Table of Contents
1. [System Overview](#system-overview)
2. [Pre-Installation Checklist](#pre-installation-checklist)
3. [Installation Steps](#installation-steps)
4. [Initial Configuration](#initial-configuration)
5. [Data Migration](#data-migration)
6. [User Training](#user-training)
7. [Backup Procedures](#backup-procedures)
8. [Maintenance](#maintenance)
9. [Troubleshooting](#troubleshooting)
10. [Support & Contact](#support--contact)

---

## System Overview

### What This System Does
The Transport Cooperative Management System is a web-based application that helps manage:
- **Member Operations**: Track operators, drivers, and vehicles
- **Financial Records**: Manage transactions, receipts, and disbursements
- **Meetings**: Schedule meetings, track attendance, manage penalties
- **Documents**: Monitor expiring licenses and registrations
- **Reports**: Generate financial and operational reports
- **Audit Trail**: Track all system activities for transparency

### User Roles
1. **Admin** - Full system control, user management
2. **President** - Executive oversight, meeting management
3. **Treasurer** - Financial management, transaction processing
4. **Auditor** - View-only access for compliance monitoring
5. **Operator** - Self-service portal for member information

---

## Pre-Installation Checklist

### Server Requirements

#### Minimum Hardware
- **Processor**: Dual-core 2.0 GHz or better
- **RAM**: 4 GB minimum (8 GB recommended)
- **Storage**: 20 GB free space minimum
- **Internet**: Broadband connection (for cloud deployment)

#### Software Requirements
- [ ] **Operating System**: Windows 10/11, Linux (Ubuntu 20.04+), or macOS
- [ ] **PHP**: Version 8.2 or higher
- [ ] **MySQL**: Version 8.0 or higher
- [ ] **Web Server**: Apache 2.4+ or Nginx
- [ ] **Composer**: Latest version
- [ ] **Node.js**: Version 18.x or higher

#### Recommended Local Development Environments
Choose ONE of the following:
- **Laragon** (Windows) - Easiest for beginners
- **XAMPP** (Windows/Mac/Linux)
- **MAMP** (Mac/Windows)
- **Docker** (All platforms) - For advanced users

### What You'll Receive
- [ ] System source code (ZIP file or Git repository)
- [ ] Database backup file (`.sql`)
- [ ] Documentation folder
- [ ] Environment configuration template
- [ ] User manuals for each role

---

## Installation Steps

### Option A: Fresh Installation (No Existing Data)

#### Step 1: Install Development Environment

**For Windows (Laragon - Recommended):**
1. Download Laragon from https://laragon.org/download/
2. Install Laragon (use default settings)
3. Start Laragon
4. Ensure Apache and MySQL are running (green indicators)

**For Windows (XAMPP):**
1. Download XAMPP from https://www.apachefriends.org/
2. Install XAMPP
3. Start Apache and MySQL from XAMPP Control Panel

#### Step 2: Extract System Files
```bash
# For Laragon users:
# Extract to: C:\laragon\www\transport-coop-system

# For XAMPP users:
# Extract to: C:\xampp\htdocs\transport-coop-system
```

#### Step 3: Install Dependencies
Open Command Prompt or Terminal in the project folder:
```bash
cd C:\laragon\www\transport-coop-system

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build assets
npm run build
```

#### Step 4: Configure Environment
```bash
# Copy environment template
copy .env.example .env

# Generate application key
php artisan key:generate
```

#### Step 5: Configure Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create new database:
   - Name: `transport_coop_system`
   - Collation: `utf8mb4_unicode_ci`

3. Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=transport_coop_system
DB_USERNAME=root
DB_PASSWORD=          # Leave blank for Laragon/XAMPP default
```

#### Step 6: Run Database Migrations
```bash
php artisan migrate
```

#### Step 7: Create Admin User
```bash
php artisan db:seed --class=AdminUserSeeder
```

#### Step 8: Set Up Storage
```bash
php artisan storage:link
```

#### Step 9: Test Installation
```bash
# Start development server
php artisan serve

# Open browser and visit:
# http://localhost:8000
```

### Option B: Migration with Existing Data

#### Step 1-4: Same as Option A

#### Step 5: Import Database
```bash
# Option 1: Using phpMyAdmin
1. Open http://localhost/phpmyadmin
2. Create database 'transport_coop_system'
3. Select the database
4. Click 'Import' tab
5. Choose your backup.sql file
6. Click 'Go'

# Option 2: Using Command Line
mysql -u root -p transport_coop_system < backup.sql
```

#### Step 6: Configure .env
Update database credentials as shown in Option A, Step 5

#### Step 7: Set Up Storage
```bash
php artisan storage:link
```

#### Step 8: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Initial Configuration

### 1. First Login
```
URL: http://localhost:8000
Email: admin@example.com
Password: password123
```

### 2. Change Admin Password
1. Login as admin
2. Go to Profile → Change Password
3. Enter new secure password
4. Save changes

### 3. Update Cooperative Information
1. Navigate to Settings → General Information
2. Fill in your cooperative's details:
   - Registration number
   - Cooperative name
   - Address
   - Contact information
   - Business permit details
3. Save changes

### 4. Configure Email Settings (Optional)
Edit `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcooperative.com
MAIL_FROM_NAME="TSJAODT Cooperative"
```

### 5. Set Timezone
Already configured to Asia/Manila (Philippine Time)

### 6. Create Additional Users
1. Go to Admin → User Management
2. Click "Add New User"
3. Fill in details for each role:
   - President
   - Treasurer
   - Auditor
4. Save and send credentials to users

---

## Data Migration

### Migrating from Spreadsheets/Manual Records

#### 1. Prepare Your Data
Create CSV files for:
- Operators list
- Drivers list
- Units/Vehicles list
- Transaction records

#### 2. Import Operators
1. Login as Admin
2. Go to Operators → Import
3. Upload operators CSV
4. Map columns
5. Review and confirm

#### 3. Import Units
1. Go to Units → Import
2. Upload units CSV
3. Ensure plate numbers are unique
4. Review and confirm

#### 4. Import Drivers
1. Go to Drivers → Import
2. Upload drivers CSV
3. Link to operators
4. Review and confirm

#### 5. Import Transactions
1. Login as Treasurer
2. Go to Transactions → Import
3. Upload transactions CSV
4. Verify amounts and dates
5. Review and confirm

**Note**: For large imports, contact support for assistance.

---

## User Training

### Training Materials Provided
- [ ] Admin User Manual
- [ ] President User Manual
- [ ] Treasurer User Manual
- [ ] Auditor User Manual
- [ ] Operator User Manual
- [ ] Video tutorials (if available)

### Recommended Training Schedule

**Week 1: Admin & Officers**
- Day 1-2: Admin training (system configuration, user management)
- Day 3: President training (meetings, reports)
- Day 4: Treasurer training (transactions, financial reports)
- Day 5: Auditor training (audit trail, reports)

**Week 2: Operators**
- Day 1-2: Operator portal overview
- Day 3-4: Adding drivers and units
- Day 5: Q&A and troubleshooting

### Training Checklist

**Admin Training:**
- [ ] User account creation
- [ ] Approval workflows
- [ ] System settings configuration
- [ ] Backup procedures
- [ ] Report generation

**Treasurer Training:**
- [ ] Recording transactions
- [ ] Processing payments
- [ ] Managing penalties
- [ ] Financial reports
- [ ] Cash book management

**President Training:**
- [ ] Creating meetings
- [ ] Managing attendance
- [ ] Viewing reports
- [ ] Operator oversight

**Operator Training:**
- [ ] Logging in
- [ ] Updating profile
- [ ] Adding drivers
- [ ] Adding units
- [ ] Viewing transactions

---

## Backup Procedures

### Automated Daily Backups (Recommended)

#### Setup Windows Task Scheduler
1. Create backup script `backup.bat`:
```batch
@echo off
cd C:\laragon\www\transport-coop-system
php artisan backup:run
```

2. Open Task Scheduler
3. Create new task:
   - **Name**: Daily Backup
   - **Trigger**: Daily at 2:00 AM
   - **Action**: Start program → backup.bat

#### Setup Linux Cron Job
```bash
# Edit crontab
crontab -e

# Add this line (runs daily at 2 AM)
0 2 * * * cd /var/www/transport-coop-system && php artisan backup:run
```

### Manual Backup

#### Database Only
```bash
# Windows (Command Prompt)
cd C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin
mysqldump -u root transport_coop_system > C:\Backups\db-backup-%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql

# Linux/Mac
mysqldump -u root -p transport_coop_system > ~/backups/db-backup-$(date +%Y%m%d).sql
```

#### Full System Backup
```bash
# Backup database
mysqldump -u root -p transport_coop_system > backup.sql

# Backup files (excluding vendor and node_modules)
zip -r system-backup-$(date +%Y%m%d).zip . -x "vendor/*" "node_modules/*"
```

### Backup Storage Recommendations
- [ ] **Local**: External hard drive or USB drive
- [ ] **Cloud**: Google Drive, Dropbox, or OneDrive
- [ ] **Both**: Keep 3 copies (3-2-1 backup rule)

### Backup Schedule
- **Daily**: Automated database backup
- **Weekly**: Full system backup
- **Monthly**: Offsite/cloud backup
- **Before Updates**: Always backup before system updates

---

## Maintenance

### Daily Tasks
- [ ] Check system is running
- [ ] Verify backup completed
- [ ] Review error logs (if any)

### Weekly Tasks
- [ ] Check disk space
- [ ] Review audit trail for unusual activity
- [ ] Test backup restoration

### Monthly Tasks
- [ ] Update system dependencies
- [ ] Clear old log files
- [ ] Review user accounts
- [ ] Performance optimization

### Quarterly Tasks
- [ ] Full system review
- [ ] User training refresher
- [ ] Security audit
- [ ] Database optimization

### Maintenance Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database maintenance
php artisan db:show
php artisan migrate:status

# View logs
tail -f storage/logs/laravel.log
```

---

## Troubleshooting

### Common Issues

#### 1. Cannot Access System (404 Error)
**Problem**: Page not found error
**Solution**:
```bash
# Check if server is running
php artisan serve

# For Apache, check .htaccess exists in public folder
# For Nginx, check configuration
```

#### 2. Database Connection Error
**Problem**: "Could not connect to database"
**Solution**:
- Check MySQL is running (green indicator in Laragon/XAMPP)
- Verify `.env` database credentials
- Test connection: `php artisan db:show`

#### 3. 500 Internal Server Error
**Problem**: White screen or 500 error
**Solution**:
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Set correct permissions
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
```

#### 4. Images Not Displaying
**Problem**: Uploaded images don't show
**Solution**:
```bash
# Create storage link
php artisan storage:link

# Check storage/app/public folder exists
```

#### 5. Login Issues
**Problem**: Cannot login or password doesn't work
**Solution**:
```bash
# Reset password via database or tinker
php artisan tinker
>>> $user = App\Models\User::where('email', 'admin@example.com')->first();
>>> $user->password = Hash::make('newpassword123');
>>> $user->save();
```

#### 6. Slow Performance
**Problem**: System is slow
**Solution**:
```bash
# Clear and rebuild cache
php artisan optimize:clear
php artisan optimize

# Check database indexes
# Review large file uploads
# Consider upgrading server resources
```

### Error Log Locations
- **Laravel Logs**: `storage/logs/laravel.log`
- **Apache Logs**: `C:\laragon\etc\apache2\logs\error.log`
- **MySQL Logs**: Check phpMyAdmin or MySQL error log

### Getting Help
1. Check documentation in `docs/` folder
2. Review error logs for specific errors
3. Search Laravel documentation
4. Contact system administrator

---

## Support & Contact

### System Information
- **Version**: 1.0.0
- **Framework**: Laravel 11.x
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+

### Documentation Files
- `README.md` - System overview
- `SETUP.md` - Detailed setup guide
- `docs/ADMIN_MANUAL.md` - Admin user guide
- `docs/TREASURER_MANUAL.md` - Treasurer user guide
- `docs/PRESIDENT_MANUAL.md` - President user guide
- `docs/OPERATOR_MANUAL.md` - Operator user guide
- `docs/TROUBLESHOOTING.md` - Common issues and solutions

### Technical Support
For technical assistance:
1. Check documentation first
2. Review troubleshooting guide
3. Check system logs for errors
4. Contact system developer/administrator

### Useful Resources
- Laravel Documentation: https://laravel.com/docs
- PHP Manual: https://www.php.net/manual/
- MySQL Documentation: https://dev.mysql.com/doc/

---

## Handover Checklist

### Before Handover
- [ ] System fully installed and tested
- [ ] All migrations completed successfully
- [ ] Admin account created and tested
- [ ] Sample data loaded (if requested)
- [ ] All documentation provided
- [ ] Backup procedures configured
- [ ] Training materials prepared

### During Handover
- [ ] Walk through installation process
- [ ] Demonstrate key features
- [ ] Review user roles and permissions
- [ ] Show backup and restore process
- [ ] Explain maintenance procedures
- [ ] Answer questions
- [ ] Provide emergency contact information

### After Handover
- [ ] Provide 30-day support period
- [ ] Monitor system stability
- [ ] Address any issues promptly
- [ ] Collect feedback
- [ ] Schedule follow-up training

---

## Next Steps

1. **Complete Installation**
   - Follow installation steps above
   - Verify system is working
   - Create user accounts

2. **Data Migration**
   - Prepare existing data
   - Import to system
   - Verify accuracy

3. **User Training**
   - Schedule training sessions
   - Distribute user manuals
   - Practice common tasks

4. **Go Live**
   - Announce to members
   - Monitor closely for first week
   - Collect feedback

5. **Ongoing Support**
   - Regular backups
   - Monthly maintenance
   - Continuous training

---

**Congratulations on your new Transport Cooperative Management System!**

For immediate assistance, refer to the Quick Start guide or contact your system administrator.

---

*Last Updated: November 2025*
