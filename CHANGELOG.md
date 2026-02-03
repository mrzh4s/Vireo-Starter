# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2026-02-03

### Added
- **Flash Message System**
  - `FlashMessages` component for automatic toast notifications across the application
  - `AppWrapper` component providing flash message context to all pages
  - Centralized toast handling using Sonner library
  - Support for success, error, warning, and info message types
  - Duplicate message prevention with Set-based tracking
  - Memory leak protection with automatic cleanup after 50 messages

### Changed
- **Authentication Controllers**
  - Migrated from client-side error handling to server-side flash messages
  - `LoginController` now uses `flash_success()` and `flash_error()` helpers
  - `RegisterController` uses flash messages for all error scenarios
  - Implemented proper HTTP 303 redirects for Inertia.js POST-Redirect-GET pattern
  - Replaced direct `$_SESSION` manipulation with `session_set()` helpers
  - Validation errors now display as toast notifications plus field-specific errors

- **Authentication Pages**
  - `SignIn.jsx` simplified with removed client-side error handling
  - `SignUp.jsx` removed Alert component and error state management
  - Both pages now rely on automatic flash message display
  - Cleaner component code with reduced complexity

### Technical Details
- Flash messages flow: Backend `flash_*()` helpers → Session → Inertia props → React toast
- Toast durations: Success (4s), Error (5s), Info (4s), Warning (4s)
- Uses Inertia's built-in flash message protocol via `props.flash`
- Proper session persistence across redirects with 303 status codes

## [1.1.2] - 2026-02-03

### Changed
- **Build Output Directory**
  - Vite now outputs compiled assets to `build/` directory instead of `assets/`
  - Updated all asset references in app.php from `/assets/` to `/build/`
  - Removed `/assets/` from .gitignore to allow storing static media files
  - Build artifacts location: `Infrastructure/Http/Public/build/`
  - This change enables committing media files in `assets/` directory to GitHub

### Technical Details
- Added `assetsDir: 'build'` configuration to vite.config.js
- Updated manifest path to `build/.vite/manifest.json`
- All JS and CSS references now use `/build/` prefix

## [1.1.1] - 2026-02-03

### Fixed
- **Repository Naming Standardization**
  - Renamed `UserRepository` to `PgUserRepository` to follow framework conventions
  - Renamed `UserDetailsRepository` to `PgUserDetailsRepository` for consistency
  - Ensures proper auto-discovery by Router's dependency injection container
  - Follows the `Pg{Name}Repository` naming pattern required by framework
  - Eliminates dependency injection errors for repository interfaces

### Changed
- Repository class names now consistently use `Pg` prefix for PostgreSQL implementations
- Improved framework convention compliance for automatic service binding

## [1.1.0] - 2026-02-03

### Added
- **Unified Layout System**
  - `AppLayout` - Main application layout with sidebar and header navigation
  - `BlankLayout` - Minimal layout without navigation
  - `AuthLayout` - Flexible authentication layout with three variants (simple, classic, branded)
  - Centralized layout exports via `@/components/layouts/index.js`
  - Comprehensive layout documentation and migration guide

- **UserDetails Domain Model**
  - Rich domain entity for `auth.user_details` table
  - Personal information management (first name, last name, phone, DOB, gender)
  - Address management with full address formatting
  - Professional information (employee ID, Telegram ID)
  - Profile information (bio, picture, JSON preferences)
  - Repository pattern implementation with CRUD operations
  - Factory methods (`create()`, `fromArray()`)
  - Conversion methods (`toArray()`, `toJson()`)
  - Validation helpers (`isProfileComplete()`, `hasCompleteAddress()`)
  - Fluent setter interface for easy chaining

- **Enhanced SignUp Form**
  - Organized form sections (User Details, Account Details, Terms)
  - Separate first name and last name fields
  - Optional company/domain field with icon
  - Improved password validation (uppercase, lowercase, number, special character required)
  - Password strength requirements displayed inline
  - Server-side validation error integration
  - Auto-complete attributes for better UX
  - Responsive grid layout for name fields
  - Form reset on successful registration

- **Documentation**
  - Layout system README with usage examples and best practices
  - Auth module README with SignUp form structure documentation
  - UserDetails domain README with comprehensive examples
  - Migration guides for transitioning from old layouts

### Changed
- **Dashboard**
  - Now uses `AppLayout` with `.layout` property pattern
  - Removed inline header/layout code
  - Consistent with auth pages layout approach

- **Authentication Pages**
  - All auth pages now use unified `AuthLayout` with `classic` variant
  - Migrated from separate `ClassicLayout` and `BrandedLayout`
  - Consistent layout pattern across all authentication flows

- **Application Structure**
  - Updated `app.php` template with flex classes for proper layout rendering
  - Enhanced validation schemas with stricter rules
  - Email normalization (auto-lowercase)
  - Name validation (letters, spaces, hyphens, apostrophes only)

### Fixed
- Blank page rendering issue caused by missing flex/height classes in HTML template
- Dashboard layout inconsistency with authentication pages
- Form validation error mapping from backend to frontend

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

[1.2.0]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.2.0
[1.1.2]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.1.2
[1.1.1]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.1.1
[1.1.0]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.1.0
[1.0.1]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.0.1
[1.0.0]: https://github.com/mrzh4s/Vireo-Starter/releases/tag/v1.0.0
