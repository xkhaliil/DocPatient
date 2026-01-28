# DocPat - Medical Appointment Management System

A comprehensive Laravel-based medical appointment management system designed to streamline healthcare scheduling and patient management.

## 🏥 Project Overview

DocPat is a modern medical appointment management system built with Laravel 12.37, featuring:

- **Appointment Scheduling**: Efficient booking and management of medical appointments
- **Multi-Role System**: Admin, Doctor, and Patient roles with appropriate permissions
- **Real-time Features**: Livewire components for dynamic user interactions
- **Location Services**: IP-based location detection for personalized experiences
- **Health Tips API**: Curated health information with filtering capabilities
- **Google Maps Integration**: Nearby medical center location services
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Media Management**: File upload capabilities with Spatie Media Library

## 🚀 Core Technologies

- **Backend**: Laravel 12.37 (PHP 8.3+)
- **Frontend**: Tailwind CSS 3.1, Alpine.js 3.4, Vite 7.0
- **Real-time**: Livewire 4.1
- **Database**: SQLite (default), MySQL/PostgreSQL supported
- **Testing**: Pest PHP 4.1
- **Backup**: Spatie Laravel Backup 9.3
- **Media**: Spatie Laravel Media Library 11.17

## 📋 Prerequisites

- PHP 8.3 or higher
- Composer 2.0+
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL

## 🛠️ Installation Guide

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd docpat
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### Step 6: Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 7: Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔧 Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME=DocPat
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=docpat
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=log
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mailtrap.io
# MAIL_PORT=2525

# IP Geolocation Service
IPGEOLOCATION_API_KEY=your_api_key_here
```

### IP Geolocation Setup

The system uses ipgeolocation.io for location services:

1. Sign up at [ipgeolocation.io](https://ipgeolocation.io)
2. Get your free API key
3. Add to `.env`: `IPGEOLOCATION_API_KEY=your_actual_key`

## 📡 API Documentation

### Health Tips API

**Base URL**: `/api/health-tips`

#### Get All Health Tips

```http
GET /api/health-tips
```

**Parameters:**

- `category` (optional): Filter by category
- `source` (optional): Filter by source
- `limit` (optional): Limit results (default: 10)

**Response:**

```json
{
    "data": [
        {
            "id": 1,
            "title": "10 Tips for Better Sleep",
            "content": "Getting quality sleep is essential for...",
            "category": "sleep",
            "source": "Health Magazine",
            "author": "Dr. Smith",
            "published_at": "2025-01-15",
            "read_more_url": "https://example.com/article/1"
        }
    ]
}
```

#### Get Single Health Tip

```http
GET /api/health-tips/{id}
```

### Authentication API

The system uses Laravel Breeze for authentication with standard endpoints:

- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout
- `GET /profile` - User profile

## 🧪 Testing

Run the test suite using Pest PHP:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🚀 Deployment

### Production Checklist

1. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

2. **Optimize for Production**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    npm run build
    ```

3. **Database Migration**

    ```bash
    php artisan migrate --force
    ```

4. **Storage Link**
    ```bash
    php artisan storage:link
    ```

### Vercel Deployment

The project includes Vercel configuration:

```bash
# Install Vercel CLI
npm i -g vercel

# Deploy
vercel --prod
```

## 🐛 Troubleshooting

### Common Issues

#### 1. "Unknown, Unknown" Location

**Problem**: Location shows "unknown, unknown"
**Solution**:

- Verify IPGEOLOCATION_API_KEY in .env
- Check if using localhost (uses fallback)
- Test with public IP: Visit `/debug/ipinfo`

#### 2. Missing App Key

**Problem**: `MissingAppKeyException`
**Solution**:

```bash
php artisan key:generate
```

#### 3. Database Connection Issues

**Problem**: Database connection failed
**Solution**:

- Check .env database configuration
- Ensure database server is running
- For SQLite: `touch database/database.sqlite`

#### 4. Permission Issues

**Problem**: Storage/logs permission denied
**Solution**:

```bash
chmod -R 775 storage bootstrap/cache
```

#### 5. Node Modules Issues

**Problem**: npm install fails
**Solution**:

```bash
rm -rf node_modules package-lock.json
npm install
```

### Debug Routes

Access debug information:

- `/debug/ipinfo` - Test IP geolocation service
- Check Laravel logs: `storage/logs/laravel.log`

## 📁 Project Structure

```
docpat/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Livewire/            # Livewire components
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── routes/                  # Application routes
├── tests/                   # Test files
└── storage/                 # Logs, cache, uploads
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

## � License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:

- Check the troubleshooting section above
- Review Laravel documentation: https://laravel.com/docs
- Check existing issues in the repository

## 🙏 Acknowledgments

- Laravel Framework - The PHP framework for web artisans
- Tailwind CSS - A utility-first CSS framework
- Livewire - A full-stack framework for Laravel
- Spatie Packages - Quality Laravel packages
- ipgeolocation.io - Free IP geolocation service

---

**Built with ❤️ for the healthcare community**
