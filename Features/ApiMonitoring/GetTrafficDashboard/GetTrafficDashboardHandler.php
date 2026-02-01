<?php
/**
 * GetTrafficDashboardHandler
 * Handles retrieval of traffic dashboard data
 * File: Features/ApiMonitoring/GetTrafficDashboard/GetTrafficDashboardHandler.php
 */
namespace Features\ApiMonitoring\GetTrafficDashboard;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class GetTrafficDashboardHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetTrafficDashboardQuery $query): array
    {
        return $this->repository->getDashboard();
    }
}
