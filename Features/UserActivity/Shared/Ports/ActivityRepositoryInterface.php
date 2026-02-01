<?php
/**
 * ActivityRepositoryInterface
 * Port for activity data access
 * File: Features/UserActivity/Shared/Ports/ActivityRepositoryInterface.php
 */
namespace Features\UserActivity\Shared\Ports;

interface ActivityRepositoryInterface
{
    /**
     * Save user activity
     */
    public function saveUserActivity(
        string $userId,
        string $message,
        string $ipAddress,
        string $url,
        string $device,
        array $location
    ): bool;

    /**
     * Get user activity history
     */
    public function getUserActivity(string $userId, int $limit = 1): array;

    /**
     * Save project activity
     */
    public function saveProjectActivity(
        string $systemId,
        string $currentFlow,
        string $username,
        string $details,
        ?string $authorityId = null
    ): bool;

    /**
     * Get project activity history
     */
    public function getProjectActivity(?string $systemId = null): array;
}
