<?php
/**
 * GetProjectActivityHandler
 * Handles retrieval of project activity
 * File: Features/UserActivity/GetProjectActivity/GetProjectActivityHandler.php
 */
namespace Features\UserActivity\GetProjectActivity;

use Features\UserActivity\Shared\Ports\ActivityRepositoryInterface;

class GetProjectActivityHandler
{
    private ActivityRepositoryInterface $repository;

    public function __construct(ActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetProjectActivityQuery $query): array
    {
        return $this->repository->getProjectActivity($query->systemId);
    }
}
