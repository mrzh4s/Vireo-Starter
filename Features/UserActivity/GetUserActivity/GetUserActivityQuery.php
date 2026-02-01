<?php
/**
 * GetUserActivityQuery
 * Query for retrieving user activity
 * File: Features/UserActivity/GetUserActivity/GetUserActivityQuery.php
 */
namespace Features\UserActivity\GetUserActivity;

class GetUserActivityQuery
{
    public function __construct(
        public string $userId,
        public int $limit = 1
    ) {}
}
