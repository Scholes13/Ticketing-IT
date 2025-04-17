<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Werkudara IT Support Ticketing System

## About The Project

Werkudara IT Support Ticketing System is a web application built with Laravel that allows employees to submit IT support tickets and IT staff to manage and respond to those tickets. The system includes features such as ticket creation, status tracking, email notifications, and staff assignment.

## Features

- **Ticket Management**: Create, update, and track status of IT support tickets
- **User Authentication**: Secure login system for employees and IT staff
- **Role-Based Access**: Different views and permissions for regular users and IT staff
- **Status Tracking**: Monitor tickets through their lifecycle (waiting, in progress, done)
- **Automatic Email Notifications**: Users receive emails when:
  - A new ticket is created
  - Ticket status changes (from waiting to in progress, from in progress to done)
  - Comments are added to their tickets
- **Responsive Design**: Mobile-friendly interface for accessibility across devices

## Installation

```bash
# Clone the repository
git clone [repository-url]

# Navigate to the project directory
cd Ticketing-IT

# Install dependencies
composer install
npm install

# Copy .env file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=wgtiket
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seed the database
php artisan migrate --seed

# Compile assets
npm run dev
```

## Email Configuration

Configure the email settings in your `.env` file:

```
MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=your-mail-port
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="your-from-address"
MAIL_FROM_NAME="Your Application Name"
```

### Automatic Email Notifications

The system is configured to automatically send email notifications to users when:

1. A ticket is created (confirmation email)
2. A ticket's status changes from "waiting" to "in progress"
3. A ticket's status changes from "in progress" to "done"
4. Comments are added to tickets

These emails are processed using Laravel's queue system to prevent delays in the web interface. To ensure proper functioning of this feature:

1. Make sure your email configuration is correct in the `.env` file
2. Set up the queue system as described in the next section
3. For production use, configure Supervisor to manage the queue workers (see "Setting Up Supervisor in Production" section)

## Queue Configuration

The application uses Laravel's queue system for processing emails and other background tasks. By default, it's configured to use the database driver.

Ensure the database driver is configured in your `.env` file:

```
QUEUE_CONNECTION=database
```

Run the migration to create the jobs table if you haven't already:

```bash
php artisan queue:table
php artisan migrate
```

## Setting Up Supervisor in Production

In a production environment, it's recommended to use Supervisor to manage and monitor the Laravel queue workers. Supervisor ensures that queue workers continue to run even after a server reboot or if the queue worker fails.

### 1. Install Supervisor

For Debian/Ubuntu:

```bash
sudo apt-get update
sudo apt-get install supervisor
```

For RHEL/CentOS:

```bash
sudo yum install supervisor
sudo systemctl enable supervisord
sudo systemctl start supervisord
```

### 2. Configure Supervisor for Laravel Queue

Create a new configuration file for your Laravel queue worker:

```bash
sudo nano /etc/supervisor/conf.d/werkudara-worker.conf
```

Add the following configuration:

```ini
[program:werkudara-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

Adjust the following parameters as needed:
- `/path/to/your/project/`: Replace with the actual path to your Laravel application
- `user`: Replace with the user that runs your web server (often `www-data`, `nginx`, or `apache`)
- `numprocs`: The number of worker processes to run (adjust based on server capacity)
- `stdout_logfile`: Path where worker logs will be stored

### 3. Update Supervisor Configuration

After creating the configuration file, update Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

### 4. Manage Supervisor Workers

Check the status of your workers:

```bash
sudo supervisorctl status
```

Start, stop, or restart workers:

```bash
sudo supervisorctl start werkudara-worker:*
sudo supervisorctl stop werkudara-worker:*
sudo supervisorctl restart werkudara-worker:*
```

### 5. Logging and Monitoring

Supervisor logs are typically found in:
- Configuration logs: `/var/log/supervisor/supervisord.log`
- Application logs: The path specified in your configuration (e.g., `/path/to/your/project/storage/logs/worker.log`)

Check these logs if you encounter any issues with the queue workers.

## Restarting Queue Workers After Deployment

After deploying new code, restart the queue workers to ensure they pick up the changes:

```bash
sudo supervisorctl restart werkudara-worker:*
```

You can also add this command to your deployment script for automatic restart.

## License

This project is proprietary software developed for Werkudara.

## Contact

IT Department - [it@werkudara.com](mailto:it@werkudara.com)

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
