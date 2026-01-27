<p align="center" style="font-size: 24px; margin-bottom: -25px; color: #EF3B2D;">
    <strong>Educational<br/> Starter Pack<br/></strong><span style="color:gray">for</span>
</p>
<p align="center">
    <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## About this Starter Pack

<div style="background-color: #f6f8fa; padding: 10px; border-radius: 5px;">
This is a starter pack for <strong>Laravel tailored for educational purposes</strong>.

It is aimed at helping students and beginners to quickly set up a Laravel development environment that allows for
learning the basics without the need to configure everything from scratch.

</div>

### Changes from the original Laravel repository

It provides a pre-configured environment with some opinionated settings and packages for the educational context.
Customisation was done based on Laravel version 12.x. (12.37.0 on November 9th, 2025).

- Added **barryvdh/laravel-debugbar** for debug info in the browser
- Altered **.env.example** for local development (SQLite database, debug mode on, cache and session set to file)
- Added **roave/security-advisories** to prevent installation of packages with known security issues
- Used **laravel/breeze** for authentication scaffolding with Blade templates (but moved all of the component views to a `components.breeze` subfolder for better organization)
- Replaced vite and related front-end dependencies by **CDN includes of Tailwind CSS and Alpine JS** to keep things simple
- Replaced PHP Unit by **Pest PHP** for testing, kept basic example tests
- Some other small tweaks in configuration files, routes, controller, and view organisation to better reflect the educational purpose (rigid structure)

---

## 📚 API Documentation

This application provides several REST API endpoints for health tips and news content.

### Health Tips API

#### Get All Health Tips

**GET** `/v1/health-tips`

Returns a list of all health tips in the database. Supports filtering by category, source, and search terms.

**Query Parameters:**

- `category` (optional): Filter by health tip category
- `source` (optional): Filter by source organization
- `search` (optional): Search in title and description fields

**Example Requests:**

```bash
# Get all health tips
curl http://docpat.test/v1/health-tips

# Filter by category
curl http://docpat.test/v1/health-tips?category=Nutrition

# Filter by source
curl http://docpat.test/v1/health-tips?source=WHO

# Search in title and description
curl http://docpat.test/v1/health-tips?search=water

# Combine filters
curl http://docpat.test/v1/health-tips?category=Nutrition&source=WHO&search=water
```

**Response:**

```json
[
    {
        "id": 1,
        "title": "Stay Hydrated",
        "description": "Drinking enough water daily supports digestion, circulation, and temperature regulation.",
        "category": "Nutrition",
        "source": "WHO"
    },
    {
        "id": 2,
        "title": "Exercise Regularly",
        "description": "Physical activity helps maintain healthy weight and reduces risk of chronic diseases.",
        "category": "Fitness",
        "source": "CDC"
    }
]
```

#### Get Random Health Tip

**GET** `/v1/health-tips/random`

Returns a single random health tip. Results are cached for 30 minutes.

**Response:**

```json
{
    "id": 9,
    "title": "Take Screen Breaks",
    "description": "Rest your eyes every 20 minutes to reduce eye strain.",
    "category": "Vision",
    "source": "AAO"
}
```

#### Get Available Categories

**GET** `/v1/health-tips/categories`

Returns a list of all unique categories available for filtering.

**Response:**

```json
["Fitness", "Mental Health", "Nutrition", "Vision", "Wellness"]
```

#### Get Available Sources

**GET** `/v1/health-tips/sources`

Returns a list of all unique sources available for filtering.

**Response:**

```json
["AAO", "CDC", "FDA", "NIH", "WHO"]
```

### News API

#### Get Random News Article

**GET** `/api/random-news`

Returns a random news article from cached news data. Articles are cached and updated periodically.

**Response:**

```json
{
    "id": "article_123",
    "title": "Latest Medical Research Breakthrough",
    "description": "Scientists discover new treatment method for common health condition.",
    "source_title": "Medical Journal",
    "pub_date": "2026-01-25T10:30:00Z",
    "creator": "Dr. Jane Smith",
    "article_link": "https://example.com/article/123"
}
```

### Error Responses

All endpoints may return the following error responses:

**404 Not Found:**

```json
{
    "message": "No health tips available"
}
```

**500 Internal Server Error:**

```json
null
```

### Testing the APIs

You can test these endpoints using curl, Postman, or any HTTP client:

```bash
# Get all health tips
curl http://docpat.test/v1/health-tips

# Get random health tip
curl http://docpat.test/v1/health-tips/random

# Get random news
curl http://docpat.test/api/random-news
```

---

Everything that follows below (and the shields in the header) are part of the original Laravel README.md file.

---

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
