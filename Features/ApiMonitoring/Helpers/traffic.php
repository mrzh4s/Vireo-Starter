<?php
/**
 * ApiMonitoring Helper Functions
 * Provides convenient global functions for API traffic monitoring (VSA pattern)
 * File: Features/ApiMonitoring/Helpers/traffic.php
 */

use Features\ApiMonitoring\LogApiTraffic\LogApiTrafficCommand;
use Features\ApiMonitoring\LogApiTraffic\LogApiTrafficHandler;
use Features\ApiMonitoring\GetApiTraffic\GetApiTrafficQuery;
use Features\ApiMonitoring\GetApiTraffic\GetApiTrafficHandler;
use Features\ApiMonitoring\GetTrafficStats\GetTrafficStatsQuery;
use Features\ApiMonitoring\GetTrafficStats\GetTrafficStatsHandler;
use Features\ApiMonitoring\GetTrafficErrors\GetTrafficErrorsQuery;
use Features\ApiMonitoring\GetTrafficErrors\GetTrafficErrorsHandler;
use Features\ApiMonitoring\GetTrafficDashboard\GetTrafficDashboardQuery;
use Features\ApiMonitoring\GetTrafficDashboard\GetTrafficDashboardHandler;
use Features\ApiMonitoring\ExportTraffic\ExportTrafficCommand;
use Features\ApiMonitoring\ExportTraffic\ExportTrafficHandler;
use Features\ApiMonitoring\CleanupOldTraffic\CleanupOldTrafficCommand;
use Features\ApiMonitoring\CleanupOldTraffic\CleanupOldTrafficHandler;
use Features\ApiMonitoring\Shared\Adapters\PgTrafficRepository;
use Features\ApiMonitoring\Shared\Domain\Traits\TrafficQueryBuilder;

/**
 * Log API traffic
 */
if (!function_exists('log_traffic')) {
    function log_traffic(
        string $traffic,
        string $url,
        string $method,
        string $headers,
        string $body,
        string $response,
        string $status,
        ?float $responseTime = null
    ): bool {
        $command = new LogApiTrafficCommand(
            traffic: $traffic,
            url: $url,
            method: $method,
            headers: $headers,
            body: $body,
            response: $response,
            status: $status,
            responseTime: $responseTime
        );

        $repository = new PgTrafficRepository(db());
        $handler = new LogApiTrafficHandler($repository);

        return $handler->handle($command);
    }
}

/**
 * Get API traffic logs with filters
 */
if (!function_exists('get_traffic')) {
    function get_traffic(array $filters = []): array {
        $query = new GetApiTrafficQuery($filters);

        $repository = new PgTrafficRepository(db());
        $handler = new GetApiTrafficHandler($repository);

        return $handler->handle($query);
    }
}

/**
 * Get traffic statistics
 */
if (!function_exists('traffic_stats')) {
    function traffic_stats(string $period = '24h'): array {
        $query = new GetTrafficStatsQuery($period);

        $repository = new PgTrafficRepository(db());
        $handler = new GetTrafficStatsHandler($repository);

        return $handler->handle($query);
    }
}

/**
 * Get traffic errors
 */
if (!function_exists('traffic_errors')) {
    function traffic_errors(int $limit = 100): array {
        $query = new GetTrafficErrorsQuery($limit);

        $repository = new PgTrafficRepository(db());
        $handler = new GetTrafficErrorsHandler($repository);

        return $handler->handle($query);
    }
}

/**
 * Get traffic dashboard data
 */
if (!function_exists('traffic_dashboard')) {
    function traffic_dashboard(): array {
        $query = new GetTrafficDashboardQuery();

        $repository = new PgTrafficRepository(db());
        $handler = new GetTrafficDashboardHandler($repository);

        return $handler->handle($query);
    }
}

/**
 * Get traffic by endpoint
 */
if (!function_exists('traffic_endpoint')) {
    function traffic_endpoint(string $endpoint, int $limit = 50): array {
        $repository = new PgTrafficRepository(db());
        return $repository->getByEndpoint($endpoint, $limit);
    }
}

/**
 * Export traffic data
 */
if (!function_exists('export_traffic')) {
    function export_traffic(array $filters = [], string $format = 'json'): string|false {
        $command = new ExportTrafficCommand($filters, $format);

        $repository = new PgTrafficRepository(db());
        $handler = new ExportTrafficHandler($repository);

        return $handler->handle($command);
    }
}

/**
 * Clean old traffic logs
 */
if (!function_exists('clean_traffic_logs')) {
    function clean_traffic_logs(int $daysToKeep = 30): array {
        $command = new CleanupOldTrafficCommand($daysToKeep);

        $repository = new PgTrafficRepository(db());
        $handler = new CleanupOldTrafficHandler($repository);

        return $handler->handle($command);
    }
}

/**
 * Main traffic helper function - handles multiple operations (backward compatibility)
 */
if (!function_exists('traffic')) {
    function traffic(string $action, array $data = []) {
        return match($action) {
            'log' => log_traffic(
                traffic: $data['traffic'] ?? 'outbound',
                url: $data['url'] ?? '',
                method: $data['method'] ?? 'GET',
                headers: $data['headers'] ?? '{}',
                body: $data['body'] ?? '',
                response: $data['response'] ?? '',
                status: $data['status'] ?? 'success',
                responseTime: $data['response_time'] ?? null
            ),
            'get', 'logs' => get_traffic($data),
            'stats', 'statistics' => traffic_stats($data['period'] ?? '24h'),
            'errors' => traffic_errors($data['limit'] ?? 100),
            'dashboard' => traffic_dashboard(),
            'endpoint' => traffic_endpoint(
                endpoint: $data['endpoint'] ?? '',
                limit: $data['limit'] ?? 50
            ),
            'export' => export_traffic(
                filters: $data['filters'] ?? [],
                format: $data['format'] ?? 'json'
            ),
            'cleanup', 'clean' => clean_traffic_logs($data['days_to_keep'] ?? 30),
            default => null
        };
    }
}

/**
 * Fluent query builder for traffic
 */
if (!function_exists('traffic_query')) {
    function traffic_query(): TrafficQueryBuilder {
        return new TrafficQueryBuilder();
    }
}
