<?php
/**
 * PgTrafficRepository
 * PostgreSQL implementation of traffic repository
 * File: Features/ApiMonitoring/Shared/Adapters/PgTrafficRepository.php
 */
namespace Features\ApiMonitoring\Shared\Adapters;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;
use PDO;
use Exception;
use SimpleXMLElement;

class PgTrafficRepository implements TrafficRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveTraffic(
        string $traffic,
        string $url,
        string $method,
        string $headers,
        string $body,
        string $response,
        string $status,
        ?float $responseTime = null
    ): bool {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO api_traffic
                (traffic, url, method, headers, body, response, status, response_time, created_at)
                VALUES
                (:traffic, :url, :method, :headers, :body, :response, :status, :response_time, CURRENT_TIMESTAMP)
            ");

            return $stmt->execute([
                'traffic' => $traffic,
                'url' => $url,
                'method' => $method,
                'headers' => $headers,
                'body' => $body,
                'response' => $response,
                'status' => $status,
                'response_time' => $responseTime
            ]);

        } catch (Exception $e) {
            logger('api')->error('Failed to save API traffic', [
                'traffic' => $traffic,
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getTraffic(array $filters = []): array
    {
        try {
            $query = "SELECT * FROM api_traffic";
            $params = [];
            $conditions = [];

            // Apply filters
            if (!empty($filters['traffic'])) {
                $conditions[] = "traffic = :traffic";
                $params['traffic'] = $filters['traffic'];
            }

            if (!empty($filters['method'])) {
                $conditions[] = "method = :method";
                $params['method'] = $filters['method'];
            }

            if (!empty($filters['status'])) {
                $conditions[] = "status = :status";
                $params['status'] = $filters['status'];
            }

            if (!empty($filters['date_from'])) {
                $conditions[] = "created_at >= :date_from";
                $params['date_from'] = $filters['date_from'];
            }

            if (!empty($filters['date_to'])) {
                $conditions[] = "created_at <= :date_to";
                $params['date_to'] = $filters['date_to'];
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            $query .= " ORDER BY created_at DESC";

            if (!empty($filters['limit'])) {
                $query .= " LIMIT :limit";
                $params['limit'] = (int)$filters['limit'];
            }

            $stmt = $this->pdo->prepare($query);

            foreach ($params as $key => $value) {
                if ($key === 'limit') {
                    $stmt->bindValue(':' . $key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(':' . $key, $value);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            logger('api')->error('Failed to get API traffic', [
                'filters' => $filters,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getStats(string $period = '24h'): array
    {
        try {
            $dateCondition = $this->buildDateCondition($period);

            $query = "SELECT
                        traffic,
                        method,
                        status,
                        COUNT(*) as count,
                        AVG(CASE WHEN response_time IS NOT NULL THEN response_time END) as avg_response_time
                      FROM api_traffic
                      WHERE {$dateCondition}
                      GROUP BY traffic, method, status
                      ORDER BY count DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            logger('api')->error('Failed to get traffic stats', [
                'period' => $period,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getErrors(int $limit = 100): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM api_traffic
                WHERE status IN ('error', 'false', 'failed')
                ORDER BY created_at DESC
                LIMIT :limit
            ");

            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            logger('api')->error('Failed to get traffic errors', [
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getDashboard(): array
    {
        try {
            return [
                'total_requests' => $this->getTotalRequests(),
                'today_requests' => $this->getTodayRequests(),
                'error_rate' => $this->getErrorRate(),
                'top_endpoints' => $this->getTopEndpoints(10),
                'recent_errors' => $this->getErrors(5)
            ];

        } catch (Exception $e) {
            logger('api')->error('Failed to get dashboard data', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getByEndpoint(string $endpoint, int $limit = 50): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM api_traffic
                WHERE url LIKE :endpoint
                ORDER BY created_at DESC
                LIMIT :limit
            ");

            $stmt->bindValue(':endpoint', '%' . $endpoint . '%');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            logger('api')->error('Failed to get traffic by endpoint', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function export(array $filters = [], string $format = 'json'): string|false
    {
        try {
            $data = $this->getTraffic($filters);

            return match($format) {
                'csv' => $this->exportToCsv($data),
                'xml' => $this->exportToXml($data),
                'json', default => json_encode($data, JSON_PRETTY_PRINT)
            };

        } catch (Exception $e) {
            logger('api')->error('Failed to export traffic', [
                'format' => $format,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function cleanup(int $daysToKeep = 30): array
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM api_traffic
                WHERE created_at < datetime('now', '-' || :days || ' days')
            ");

            $stmt->bindValue(':days', $daysToKeep, PDO::PARAM_INT);
            $result = $stmt->execute();
            $deletedRows = $stmt->rowCount();

            return [
                'success' => $result,
                'deleted_rows' => $deletedRows
            ];

        } catch (Exception $e) {
            logger('api')->error('Failed to cleanup traffic', [
                'days_to_keep' => $daysToKeep,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    // Private helper methods
    private function buildDateCondition(string $period): string
    {
        return match($period) {
            '1h' => "created_at >= datetime('now', '-1 hour')",
            '24h', '1d' => "created_at >= datetime('now', '-1 day')",
            '7d', '1w' => "created_at >= datetime('now', '-7 days')",
            '30d', '1m' => "created_at >= datetime('now', '-30 days')",
            default => "created_at >= datetime('now', '-1 day')"
        };
    }

    private function getTotalRequests(): int
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM api_traffic");
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTodayRequests(): int
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM api_traffic WHERE DATE(created_at) = DATE('now')");
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getErrorRate(): float
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    ROUND(
                        (COUNT(CASE WHEN status IN ('error', 'false', 'failed') THEN 1 END) / COUNT(*)) * 100,
                        2
                    ) as error_rate
                FROM api_traffic
                WHERE created_at >= datetime('now', '-1 day')
            ");
            return (float)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0.0;
        }
    }

    private function getTopEndpoints(int $limit = 10): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    url,
                    COUNT(*) as request_count,
                    AVG(CASE WHEN status = 'success' THEN 1 ELSE 0 END) * 100 as success_rate
                FROM api_traffic
                WHERE created_at >= datetime('now', '-1 day')
                GROUP BY url
                ORDER BY request_count DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    private function exportToCsv(array $data): string
    {
        if (empty($data)) return '';

        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    private function exportToXml(array $data): string
    {
        $xml = new SimpleXMLElement('<traffic_logs/>');

        foreach ($data as $record) {
            $log = $xml->addChild('log');
            foreach ($record as $key => $value) {
                $log->addChild($key, htmlspecialchars((string)$value));
            }
        }

        return $xml->asXML();
    }
}
