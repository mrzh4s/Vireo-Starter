# Vireo Starter

A modern PHP application starter template built on the [Vireo Framework](https://github.com/mrzh4s/vireo-framework) with Vertical Slice Architecture and Inertia.js.

## Features

- **Vertical Slice Architecture** - Organized by feature, not by layer
- **Inertia.js + React** - Modern SPA experience without building an API
- **TailwindCSS v4** - Utility-first CSS framework
- **Authentication System** - Login, registration, password reset
- **User Activity Tracking** - Monitor user actions with geolocation support
- **API Traffic Monitoring** - Track and analyze API usage
- **Role & Permission Based Access** - Fine-grained authorization
- **Rate Limiting** - Protect endpoints from abuse
- **Input Validation & Sanitization** - Built-in security features
- **Multiple Database Support** - PostgreSQL, MySQL, SQLite, SQL Server
- **Multiple Cache Drivers** - File, Redis, Memcached, Database
- **Multiple Storage Drivers** - Local, FTP, SFTP, S3, DigitalOcean Spaces, MinIO

## Requirements

- PHP 8.4+
- Composer
- Node.js 18+
- npm or yarn

## Installation

```bash
# Create a new project
composer create-project vireo/starter my-app

# Navigate to project directory
cd my-app

# Install JavaScript dependencies
npm install

# Copy environment file (done automatically by composer)
cp .env.example .env

# Generate application key
./vireo key:generate

# Run database migrations
./vireo migrate

# Seed the database (optional)
./vireo db:seed
```

## Development

Start the development servers:

```bash
# Start PHP development server
./vireo serve

# In a separate terminal, start Vite dev server
npm run dev

# Or run both with the dev command
composer dev
```

Visit `http://localhost:8000` in your browser.

## Project Structure

```
├── Config/              # Application configuration
├── Features/            # Feature modules (Vertical Slices)
│   ├── Auth/            # Authentication feature
│   ├── UserActivity/    # User activity tracking
│   └── ApiMonitoring/   # API traffic monitoring
├── Infrastructure/      # Infrastructure code
│   ├── Http/            # Controllers, middleware, routes
│   ├── Persistence/     # Migrations, seeds, repositories
│   └── Resources/       # Frontend assets (JS, CSS)
└── Tests/               # Test suite
```

## Available Commands

```bash
# Development
./vireo serve              # Start development server
./vireo dev                # Start dev server with Vite HMR
npm run dev               # Start Vite dev server
npm run build             # Build frontend assets

# Database
./vireo migrate            # Run migrations
./vireo migrate:rollback   # Rollback last migration
./vireo migrate:status     # Show migration status
./vireo db:seed            # Run database seeders

# Testing
composer test             # Run PHPUnit tests
```

## Configuration

All configuration is done through environment variables. Copy `.env.example` to `.env` and adjust the values:

- **Database**: Configure your database connection (PostgreSQL recommended for production)
- **Cache**: Choose between file, Redis, Memcached, or database caching
- **Session**: Configure session storage driver
- **Storage**: Set up file storage (local, S3, FTP, etc.)
- **Rate Limiting**: Configure API rate limits

See `.env.example` for detailed documentation of all available options.

## Testing

```bash
# Run all tests
composer test

# Run specific test file
./vendor/bin/phpunit Tests/Unit/Validation/ValidatorTest.php
```

## Documentation

- [Vireo Framework Documentation](https://github.com/mrzh4s/vireo-framework#readme)
- [Inertia.js Documentation](https://inertiajs.com/)
- [React Documentation](https://react.dev/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
