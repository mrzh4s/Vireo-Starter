<?php
/**
 * GetTrafficStatsHandler
 * Handles retrieval of traffic statistics
 * File: Features/ApiMonitoring/GetTrafficStats/GetTrafficStatsHandler.php
 */
namespace Features\ApiMonitoring\GetTrafficStats;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class GetTrafficStatsHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetTrafficStatsQuery $query): array
    {
        return $this->repository->getStats($query->period);
    }
}
