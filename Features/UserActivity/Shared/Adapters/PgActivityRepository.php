<?php
/**
 * PgActivityRepository
 * PostgreSQL implementation of activity repository
 * File: Features/UserActivity/Shared/Adapters/PgActivityRepository.php
 */
namespace Features\UserActivity\Shared\Adapters;

use Features\UserActivity\Shared\Ports\ActivityRepositoryInterface;
use PDO;
use Exception;

class PgActivityRepository implements ActivityRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveUserActivity(
        string $userId,
        string $message,
        string $ipAddress,
        string $url,
        string $device,
        array $location
    ): bool {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO user_activities
                (user_id, ip_address, url, location, device, message, action_at)
                VALUES
                (:user_id, :ip_address, :url, :location, :device, :message, CURRENT_TIMESTAMP)
            ");

            return $stmt->execute([
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'url' => $url,
                'location' => json_encode($location),
                'device' => $device,
                'message' => $message
            ]);

        } catch (Exception $e) {
            logger('activity')->error('Failed to save user activity', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getUserActivity(string $userId, int $limit = 1): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM user_activities
                WHERE user_id = :user_id
                ORDER BY action_at DESC
                LIMIT :limit
            ");

            $stmt->bindValue(':user_id', $userId);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode JSON location field
            foreach ($results as &$result) {
                $result['location'] = json_decode($result['location'] ?? '{}', true);
            }

            return $results;

        } catch (Exception $e) {
            logger('activity')->error('Failed to get user activity', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function saveProjectActivity(
        string $systemId,
        string $currentFlow,
        string $username,
        string $details,
        ?string $authorityId = null
    ): bool {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO project_activities
                (system_id, current_flow, username, details, authority_id, flow_timestamp)
                VALUES
                (:system_id, :current_flow, :username, :details, :authority_id, CURRENT_TIMESTAMP)
            ");

            return $stmt->execute([
                'system_id' => $systemId,
                'current_flow' => $currentFlow,
                'username' => $username,
                'details' => $details,
                'authority_id' => $authorityId
            ]);

        } catch (Exception $e) {
            logger('activity')->error('Failed to save project activity', [
                'system_id' => $systemId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getProjectActivity(?string $systemId = null): array
    {
        try {
            if ($systemId) {
                $stmt = $this->pdo->prepare("
                    SELECT * FROM project_activities
                    WHERE system_id = :system_id
                    ORDER BY flow_timestamp DESC
                ");
                $stmt->bindValue(':system_id', $systemId);
            } else {
                $stmt = $this->pdo->prepare("
                    SELECT * FROM project_activities
                    ORDER BY flow_timestamp DESC
                    LIMIT 100
                ");
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            logger('activity')->error('Failed to get project activity', [
                'system_id' => $systemId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
