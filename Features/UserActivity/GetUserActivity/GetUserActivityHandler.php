<?php
/**
 * GetUserActivityHandler
 * Handles retrieval of user activity
 * File: Features/UserActivity/GetUserActivity/GetUserActivityHandler.php
 */
namespace Features\UserActivity\GetUserActivity;

use Features\UserActivity\Shared\Ports\ActivityRepositoryInterface;

class GetUserActivityHandler
{
    private ActivityRepositoryInterface $repository;

    public function __construct(ActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetUserActivityQuery $query): array
    {
        return $this->repository->getUserActivity(
            userId: $query->userId,
            limit: $query->limit
        );
    }
}
