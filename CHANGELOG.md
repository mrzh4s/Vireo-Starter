# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2026-02-02

### Changed
- Requires Vireo Framework ^1.0.2
- Reorganized database migrations with sequential numbering (001, 002, etc.)
- Implemented PostgreSQL schema organization:
  - `auth` schema: users, roles, groups, permissions, sessions
  - `system` schema: logs, activities, traffic, cache
  - `email` schema: queue, templates, campaigns, tracking
- Updated seeders to use schema-prefixed table names
- Set `AUTO_MIGRATE=false` by default in `.env.example`
- Added `Infrastructure/Http/View/Components/` directory

### Fixed
- PSR-4 autoloading warnings for migrations and seeders
- Post-install key generation script

## [1.0.0] - 2026-02-01

### Added
- Initial release of Vireo Starter template
- Requires Vireo Framework ^1.0.0
- Vertical Slice Architecture with organized feature modules
- Authentication system (Login, Register, Forgot Password, Reset Password)
- User Activity tracking and analytics
- API Monitoring with traffic statistics and performance metrics
- Inertia.js integration with React frontend
- TailwindCSS v4 styling
- PostgreSQL, MySQL, SQLite, and SQL Server support
- Multiple cache drivers (File, Redis, Memcached)
- File storage support (Local, S3, FTP, SFTP)
- Rate limiting middleware
- Input validation and sanitization
- PSR-4 autoloading with clean namespace structure
- PHPUnit test setup
- Vite build configuration for frontend assets
- CLI commands via `vireo` binary

### Infrastructure
- HTTP middleware stack (Auth, Validation, Sanitization, Throttling)
- Database migrations and seeders
- Environment configuration via `.env`

[1.0.1]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.0.1
[1.0.0]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.0.0
