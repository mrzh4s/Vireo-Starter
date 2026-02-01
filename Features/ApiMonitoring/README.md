# API Traffic Monitoring

Monitors and logs API requests, responses, and performance metrics.

## Features
- API traffic logging with headers/body/response
- Performance tracking (response time)
- Traffic statistics and analytics
- Export capabilities (JSON, CSV, XML)
- Error tracking and monitoring
- Dashboard metrics

## Usage

Via helper functions:

```php
// Log API traffic
log_traffic('outbound', $url, 'POST', $headers, $body, $response, 'success');

// Get traffic logs
$logs = get_traffic(['traffic' => 'outbound', 'limit' => 100]);
$logs = get_traffic(['method' => 'POST', 'status' => 'success']);

// Get traffic statistics
$stats = traffic_stats();       // Last 24h
$stats = traffic_stats('7d');   // Last 7 days

// Get traffic errors
$errors = traffic_errors();
$errors = traffic_errors(50);   // Limit to 50 errors

// Get traffic dashboard
$dashboard = traffic_dashboard();

// Get traffic by endpoint
$logs = traffic_endpoint('/api/users');
$logs = traffic_endpoint('/api/users', 25);

// Export traffic data
$json = export_traffic();
$csv = export_traffic(['method' => 'POST'], 'csv');
$xml = export_traffic([], 'xml');

// Clean old traffic logs
clean_traffic_logs();     // Keep 30 days (default)
clean_traffic_logs(7);    // Keep only 7 days
```

## Fluent Query Builder

Use the fluent interface for advanced queries:

```php
// Get outbound POST requests with errors
$logs = traffic_query()
    ->outbound()
    ->method('POST')
    ->errors()
    ->limit(50)
    ->fetch();

// Get traffic statistics for last 7 days
$stats = traffic_query()
    ->period('7d')
    ->stats();

// Get dashboard data
$dashboard = traffic_query()
    ->dashboard();

// Export filtered data
$csv = traffic_query()
    ->inbound()
    ->success()
    ->from('2026-01-01')
    ->to('2026-01-31')
    ->export('csv');
```

## Helpers

Located in `Helpers/traffic.php`, automatically loaded by the framework during bootstrap.

### Available Helper Functions

- **`traffic($action, $data = [])`** - Main traffic helper with multiple operations
- **`log_traffic($traffic, $url, $method, $headers, $body, $response, $status)`** - Log API traffic
- **`traffic_stats($period = '24h')`** - Get traffic statistics
- **`traffic_errors($limit = 100)`** - Get traffic errors
- **`traffic_dashboard()`** - Get dashboard data
- **`traffic_endpoint($endpoint, $limit = 50)`** - Get traffic by endpoint
- **`clean_traffic_logs($daysToKeep = 30)`** - Clean old logs
- **`export_traffic($filters = [], $format = 'json')`** - Export traffic data
- **`get_traffic($filters = [])`** - Get filtered traffic
- **`traffic_query()`** - Fluent query builder

The helpers are automatically discovered and loaded from `Features/ApiMonitoring/Helpers/` during framework bootstrap. No manual registration required.

## Database Tables

### api_traffic
Logs all API requests and responses:
- `id` - Primary key
- `traffic` - Direction (inbound/outbound)
- `url` - API endpoint URL
- `method` - HTTP method (GET, POST, etc.)
- `headers` - Request headers (JSON/text)
- `body` - Request body (JSON/text)
- `response` - API response (JSON/text)
- `status` - Status (success/error/failed)
- `response_time` - Response time in milliseconds
- `created_at` - Timestamp

Indexes on `traffic`, `method`, `status`, and `created_at` for query performance.

## Architecture

This feature implements **Vertical Slice Architecture** with domain-driven design:

```
Features/ApiMonitoring/
├── Shared/
│   └── Domain/
│       ├── Traffic.php                      # Main domain class (singleton)
│       └── Traits/
│           └── TrafficQueryBuilder.php      # Fluent query builder
├── Helpers/
│   └── traffic.php                          # Helper functions (auto-loaded)
└── README.md                                # This file
```

Future enhancements may include:
- Command/Query separation (LogApiTraffic, GetTrafficStats)
- Repository pattern with ports/adapters
- Value objects (TrafficRecord)
- API controllers for traffic dashboard
- Real-time monitoring with WebSockets
- Alerting for error rates

## Integration

Traffic monitoring integrates with the logging system:
- Uses `logger('api')` for error logging
- Database errors logged with full context
- Performance-optimized with prepared statements
- Automatic cleanup of old logs

## Performance Metrics

Dashboard provides:
- Total requests (all time)
- Today's requests
- Error rate (last 24h)
- Top endpoints by request count
- Recent errors (last 5)

Statistics include:
- Request count by traffic type
- Request count by HTTP method
- Request count by status
- Average response time

## Export Formats

Supported export formats:
- **JSON** - Structured data with pretty printing
- **CSV** - Spreadsheet-compatible format
- **XML** - Hierarchical XML structure

## Best Practices

1. **Log selectively**: Don't log sensitive data in headers/body
2. **Clean regularly**: Use `clean_traffic_logs()` to manage database size
3. **Monitor errors**: Set up alerts for high error rates
4. **Analyze patterns**: Use dashboard metrics to identify issues
5. **Filter queries**: Use query builder for efficient data retrieval

## Configuration

Optional environment variables:
```
LOG_API_TRAFFIC=true              # Enable/disable traffic logging
LOG_API_RESPONSE_TIME=true        # Track response times
TRAFFIC_CLEANUP_DAYS=30           # Days to keep traffic logs
```
