<?php
/**
 * CleanupOldTrafficHandler
 * Handles cleanup of old traffic logs
 * File: Features/ApiMonitoring/CleanupOldTraffic/CleanupOldTrafficHandler.php
 */
namespace Features\ApiMonitoring\CleanupOldTraffic;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class CleanupOldTrafficHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CleanupOldTrafficCommand $command): array
    {
        return $this->repository->cleanup($command->daysToKeep);
    }
}
