# User Activity Tracking

Tracks user behavior and project workflows across the system.

## Features
- User activity logging with location/device tracking
- Project activity workflow tracking
- Historical activity queries

## Usage

Via helper functions:

```php
// Log user activity
log_user_activity('User logged in', $userId);

// Get user activity history
$lastActivity = get_user_activity();
$recentActivities = get_user_activity(10);

// Log project activity
log_project_activity($systemId, $flow, 'Status updated');
log_project_activity($systemId, $flow, 'Authority approved', $authorityId);

// Get project activity history
$projectHistory = get_project_activity($systemId);
```

## Helpers

Located in `Helpers/activity.php`, automatically loaded by the framework during bootstrap.

### Available Helper Functions

- **`activity($action, $data = [])`** - Main activity helper with multiple operations
- **`log_user_activity($message, $userId = null)`** - Quick user activity logging
- **`get_user_activity($limit = 1)`** - Get user activity history
- **`log_project_activity($systemId, $currentFlow, $details, $authorityId = null)`** - Log project activity
- **`get_project_activity($systemId = null)`** - Get project activity history
- **`auto_activity($message, $context = [])`** - Auto-log based on current context
- **`batch_activity($activities)`** - Batch activity logging

The helpers are automatically discovered and loaded from `Features/UserActivity/Helpers/` during framework bootstrap. No manual registration required.

## Database Tables

### user_activities
Logs user behavior with IP, location, device tracking:
- `id` - Primary key
- `user_id` - User identifier
- `ip_address` - IP address (IPv4/IPv6)
- `url` - Current URL
- `location` - Geolocation data (JSON)
- `device` - User agent string
- `message` - Activity description
- `action_at` - Timestamp

### project_activities
Tracks project workflow progression:
- `id` - Primary key
- `system_id` - Project identifier
- `current_flow` - Workflow stage
- `username` - User performing action
- `details` - Activity details
- `authority_id` - Optional authority identifier
- `flow_timestamp` - Timestamp

## Architecture

This feature implements **Vertical Slice Architecture** with domain-driven design:

```
Features/UserActivity/
├── Shared/
│   └── Domain/
│       └── Activity.php     # Main domain class (singleton)
├── Helpers/
│   └── activity.php        # Helper functions (auto-loaded)
└── README.md               # This file
```

Future enhancements may include:
- Command/Query separation (CQRS)
- Repository pattern with ports/adapters
- Value objects for type safety
- API endpoints for activity dashboards

## Integration

Activity tracking integrates with the logging system:
- Uses `logger('activity')` for error logging
- Database errors logged with full context
- Performance-optimized with prepared statements

## Configuration

Geolocation API configuration (in `.env` or config files):
```
LOCATION_API_URL=https://api.ipgeolocation.io/v2/ipgeo
LOCATION_API_KEY=your_api_key_here
```
