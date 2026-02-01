<?php
/**
 * UserActivity Helper Functions
 * Provides convenient global functions for activity logging (VSA pattern)
 * File: Features/UserActivity/Helpers/activity.php
 */

use Features\UserActivity\LogUserActivity\LogUserActivityCommand;
use Features\UserActivity\LogUserActivity\LogUserActivityHandler;
use Features\UserActivity\GetUserActivity\GetUserActivityQuery;
use Features\UserActivity\GetUserActivity\GetUserActivityHandler;
use Features\UserActivity\LogProjectActivity\LogProjectActivityCommand;
use Features\UserActivity\LogProjectActivity\LogProjectActivityHandler;
use Features\UserActivity\GetProjectActivity\GetProjectActivityQuery;
use Features\UserActivity\GetProjectActivity\GetProjectActivityHandler;
use Features\UserActivity\Shared\Adapters\PgActivityRepository;
use Features\UserActivity\Shared\Services\GeolocationService;

/**
 * Log user activity
 */
if (!function_exists('log_user_activity')) {
    function log_user_activity(string $message, ?string $userId = null): bool {
        $userId = $userId ?? session('user.id') ?? 'guest';

        $command = new LogUserActivityCommand(
            userId: $userId,
            message: $message,
            ipAddress: $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            url: $_SERVER['REQUEST_URI'] ?? '/',
            device: $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        );

        $repository = new PgActivityRepository(db());
        $geolocationService = new GeolocationService();
        $handler = new LogUserActivityHandler($repository, $geolocationService);

        return $handler->handle($command);
    }
}

/**
 * Get user activity history
 */
if (!function_exists('get_user_activity')) {
    function get_user_activity(int $limit = 1, ?string $userId = null): array {
        $userId = $userId ?? session('user.id');

        if (!$userId) {
            return [];
        }

        $query = new GetUserActivityQuery($userId, $limit);

        $repository = new PgActivityRepository(db());
        $handler = new GetUserActivityHandler($repository);

        $results = $handler->handle($query);

        // Return single item if limit is 1, otherwise return array
        return $limit === 1 ? ($results[0] ?? []) : $results;
    }
}

/**
 * Log project activity
 */
if (!function_exists('log_project_activity')) {
    function log_project_activity(
        string $systemId,
        string $currentFlow,
        string $details,
        ?string $authorityId = null
    ): bool {
        $username = session('user.name') ?? session('user.email') ?? 'System';

        $command = new LogProjectActivityCommand(
            systemId: $systemId,
            currentFlow: $currentFlow,
            username: $username,
            details: $details,
            authorityId: $authorityId
        );

        $repository = new PgActivityRepository(db());
        $handler = new LogProjectActivityHandler($repository);

        return $handler->handle($command);
    }
}

/**
 * Get project activity history
 */
if (!function_exists('get_project_activity')) {
    function get_project_activity(?string $systemId = null): array {
        $query = new GetProjectActivityQuery($systemId);

        $repository = new PgActivityRepository(db());
        $handler = new GetProjectActivityHandler($repository);

        return $handler->handle($query);
    }
}

/**
 * Main activity helper function - handles multiple operations (backward compatibility)
 */
if (!function_exists('activity')) {
    function activity(string $action, array $data = []) {
        return match($action) {
            'user', 'log.user' => log_user_activity(
                message: $data['message'] ?? 'User activity',
                userId: $data['user_id'] ?? null
            ),
            'project', 'log.project' => log_project_activity(
                systemId: $data['system_id'] ?? '',
                currentFlow: $data['flow'] ?? $data['current_flow'] ?? '',
                details: $data['details'] ?? 'Project activity',
                authorityId: $data['authority_id'] ?? null
            ),
            'get.user', 'user.history' => get_user_activity(
                limit: $data['limit'] ?? 1,
                userId: $data['user_id'] ?? null
            ),
            'get.project', 'project.history' => get_project_activity(
                systemId: $data['system_id'] ?? null
            ),
            default => null
        };
    }
}

/**
 * Auto-log activity based on current context
 */
if (!function_exists('auto_activity')) {
    function auto_activity(string $message, array $context = []): bool {
        if (session('user.id')) {
            return log_user_activity($message);
        }
        return false;
    }
}

/**
 * Batch activity logging
 */
if (!function_exists('batch_activity')) {
    function batch_activity(array $activities): array {
        $results = [];

        foreach ($activities as $activity) {
            $results[] = log_user_activity(
                message: $activity['message'] ?? 'Activity',
                userId: $activity['user_id'] ?? null
            );
        }

        return $results;
    }
}
