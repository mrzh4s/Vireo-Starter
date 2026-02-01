<?php
/**
 * TrafficRepositoryInterface
 * Port for traffic data access
 * File: Features/ApiMonitoring/Shared/Ports/TrafficRepositoryInterface.php
 */
namespace Features\ApiMonitoring\Shared\Ports;

interface TrafficRepositoryInterface
{
    /**
     * Save API traffic log
     */
    public function saveTraffic(
        string $traffic,
        string $url,
        string $method,
        string $headers,
        string $body,
        string $response,
        string $status,
        ?float $responseTime = null
    ): bool;

    /**
     * Get traffic logs with filters
     */
    public function getTraffic(array $filters = []): array;

    /**
     * Get traffic statistics
     */
    public function getStats(string $period = '24h'): array;

    /**
     * Get traffic errors
     */
    public function getErrors(int $limit = 100): array;

    /**
     * Get dashboard data
     */
    public function getDashboard(): array;

    /**
     * Get traffic by endpoint
     */
    public function getByEndpoint(string $endpoint, int $limit = 50): array;

    /**
     * Export traffic data
     */
    public function export(array $filters = [], string $format = 'json'): string|false;

    /**
     * Cleanup old traffic logs
     */
    public function cleanup(int $daysToKeep = 30): array;
}
