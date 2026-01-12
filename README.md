# TSJAODT Transport Cooperative Management System

A comprehensive web-based management system for **Tacloban San Jose Airport Operators Drivers Transport Cooperative (TSJAODT)**.

## Overview

This system manages the daily operations of a transport cooperative including:
- Operator, driver, and transport unit registration
- Meeting attendance tracking
- Financial record-keeping (subsidiary journals, cash books)
- Document expiry monitoring
- Penalty management
- Role-based access control

## Quick Start

### Automated Setup (Recommended)

**Windows:**
```bash
setup-windows.bat
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

### Manual Setup
```bash
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

Visit: **http://localhost:8000**

📖 **For detailed instructions, see [QUICK-START.md](QUICK-START.md)**

## Documentation

| Document | Description |
|----------|-------------|
| [QUICK-START.md](QUICK-START.md) | Get started in 5 minutes |
| [SETUP.md](SETUP.md) | Complete setup guide with troubleshooting |
| [DEPLOYMENT-CHECKLIST.md](DEPLOYMENT-CHECKLIST.md) | Production deployment checklist |

## System Requirements

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher
- **Database**: MySQL 5.7+ or SQLite 3
- **Web Server**: Apache/Nginx (production) or PHP built-in server (development)

## Features

### User Roles
- **Admin**: Complete system administration
- **President**: Meeting management, operator oversight
- **Treasurer**: Financial management and reporting
- **Auditor**: Read-only access to all records
- **Operator**: Manage own drivers and transport units

### Core Modules

#### 1. Operator Management
- Register and approve operators
- Track business information
- View operator statistics
- Manage operator status

#### 2. Driver Management
- Register drivers with complete information
- Track license numbers and expiry dates
- Monitor license status
- Assign drivers to operators

#### 3. Unit (Vehicle) Management
- Register transport units (jeepneys, buses, vans, taxis)
- Track detailed vehicle information:
  - Plate number, body number, engine number, chassis number
  - LTO CR/OR numbers and dates
  - Franchise information
  - Vehicle status (active, maintenance, inactive)
- Monitor registration expiry

#### 4. Meeting Management
- Schedule cooperative meetings
- Take attendance
- Track absences
- Generate attendance reports
- Automatic penalty calculation for absences

#### 5. Financial Management
- **Subsidiary Journal**: Track operator transactions
- **Cash Receipts Journal**: Record all incoming cash
- **Cash Disbursement Book**: Record all outgoing cash
- **Cash Book**: Combined view of receipts and disbursements
- **Book of Accounts**: Financial overview and reports

#### 6. Document Tracking
- Monitor expiring documents (licenses, registrations, insurance)
- Track pending document renewals
- Automatic notifications for expiring documents

#### 7. Activity Logging
- Track all system activities
- User action audit trail
- System-wide activity feed

## Technology Stack

- **Backend**: Laravel 10.x (PHP)
- **Frontend**: Blade templates, JavaScript, CSS
- **Database**: MySQL / SQLite
- **Build Tools**: Vite, npm
- **UI Framework**: Custom CSS with AdminLTE inspiration

## Default Credentials

After setup, create an admin user with these credentials:

- **Email**: admin@tsjaodt.coop
- **Password**: password123

⚠️ **IMPORTANT**: Change the default password immediately after first login!

## Project Structure

```
transport-coop-system/
├── app/
│   ├── Http/Controllers/     # Request handlers
│   │   ├── DashboardController.php
│   │   ├── OperatorController.php
│   │   ├── DriverController.php
│   │   ├── UnitController.php
│   │   └── MeetingController.php
│   ├── Models/               # Database models
│   │   ├── User.php
│   │   ├── Operator.php
│   │   ├── Driver.php
│   │   ├── Unit.php
│   │   └── Meeting.php
│   └── ...
├── database/
│   ├── migrations/          # Database structure
│   └── seeders/            # Sample data
├── public/
│   ├── images/             # Logo and assets
│   └── ...
├── resources/
│   └── views/              # Blade templates
│       ├── admin/
│       ├── president/
│       ├── treasurer/
│       ├── auditor/
│       └── operator/
├── routes/
│   └── web.php             # Application routes
├── .env.example            # Environment template
├── composer.json           # PHP dependencies
├── package.json            # JS dependencies
├── setup-windows.bat       # Windows setup script
├── setup.sh                # Linux/Mac setup script
└── README.md               # This file
```

## Key Features Implementation

### Unit Form Fields
The transport unit registration includes comprehensive Philippine LTO compliance fields:
- Basic: Plate number, color, year model
- Technical: Body number, engine number, chassis number
- LTO Documents: CR number & date, OR number & date
- Administrative: Franchise case number, MV file number
- Previous Year: MBP No., MCH No.
- Status tracking: Active, maintenance, inactive

### Dashboard Analytics
- Real-time statistics
- Document expiry monitoring
- Pending tasks tracking
- Recent activity feed
- Financial overview charts

### Mobile Responsive
- Fully responsive design
- Mobile-optimized layouts
- Touch-friendly interfaces
- Accessible on all devices

## Development

### Common Commands

```bash
# Start development server
php artisan serve

# Watch and compile assets
npm run dev

# Run migrations
php artisan migrate

# Clear all caches
php artisan config:clear && php artisan cache:clear

# Access database console
php artisan tinker
```

### Database Migrations

Recent migrations include:
- `2025_11_05_073531` - Add detailed fields to units table
- `2025_11_05_094303` - Make year field nullable in units table

### Adding New Features

1. Create migration: `php artisan make:migration create_table_name`
2. Create model: `php artisan make:model ModelName`
3. Create controller: `php artisan make:controller ControllerName`
4. Add routes in `routes/web.php`
5. Create views in `resources/views/`

## Deployment

### Development Environment
```bash
APP_ENV=local
APP_DEBUG=true
npm run dev
php artisan serve
```

### Production Environment
```bash
APP_ENV=production
APP_DEBUG=false
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

📖 **See [DEPLOYMENT-CHECKLIST.md](DEPLOYMENT-CHECKLIST.md) for complete deployment guide**

## Transferring to Another Device

### Quick Transfer Steps

1. **On Current Device:**
   ```bash
   # Export database (MySQL)
   mysqldump -u root -p transport_coop_system > backup.sql

   # Create archive (exclude vendor, node_modules)
   zip -r project.zip . -x "vendor/*" "node_modules/*" ".env"
   ```

2. **On New Device:**
   ```bash
   # Extract files
   unzip project.zip

   # Run setup script
   ./setup.sh    # or setup-windows.bat

   # Import database (if transferring data)
   mysql -u root -p transport_coop_system < backup.sql
   ```

📖 **See [SETUP.md](SETUP.md) for detailed transfer instructions**

## Troubleshooting

### Common Issues

**Database Connection Failed**
```bash
# Check .env database credentials
# Verify database server is running
# For SQLite, ensure file exists
```

**500 Error / White Screen**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan config:clear && php artisan cache:clear
```

**Assets Not Loading**
```bash
npm run dev
# or for production
npm run build
```

**Permission Errors (Linux/Mac)**
```bash
chmod -R 775 storage bootstrap/cache
```

📖 **See [SETUP.md](SETUP.md#troubleshooting) for more solutions**

## Security

- Password hashing with bcrypt
- CSRF protection on all forms
- Role-based access control
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templates)

### Security Best Practices
1. Change default admin password
2. Keep dependencies updated
3. Use strong database passwords
4. Enable HTTPS in production
5. Regular backups
6. Monitor error logs

## Contributing

When contributing to this project:

1. Follow Laravel coding standards
2. Write clear commit messages
3. Test all changes thoroughly
4. Update documentation as needed
5. Keep migrations reversible

## Support

### Getting Help

1. Check documentation files (SETUP.md, QUICK-START.md)
2. Review Laravel logs: `storage/logs/laravel.log`
3. Run system diagnostics: `php artisan about`
4. Clear caches and try again

### Useful Resources

- Laravel Documentation: https://laravel.com/docs
- PHP Documentation: https://www.php.net/docs.php
- Composer Documentation: https://getcomposer.org/doc/

## License

This system is proprietary software developed for TSJAODT Cooperative.

## Version

**Version**: 1.0.0
**Laravel**: 10.x
**PHP**: 8.1+
**Last Updated**: November 2025

---

## Acknowledgments

Developed for the Tacloban San Jose Airport Operators Drivers Transport Cooperative to streamline cooperative management and improve operational efficiency.

---

**For immediate assistance, refer to:**
- [QUICK-START.md](QUICK-START.md) - Get started quickly
- [SETUP.md](SETUP.md) - Detailed setup instructions
- [DEPLOYMENT-CHECKLIST.md](DEPLOYMENT-CHECKLIST.md) - Production deployment

**Ready to get started? Run the setup script now!**

Windows: `setup-windows.bat`
Linux/Mac: `./setup.sh`
