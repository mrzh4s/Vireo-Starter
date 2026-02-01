<?php
/**
 * GetApiTrafficHandler
 * Handles retrieval of API traffic logs
 * File: Features/ApiMonitoring/GetApiTraffic/GetApiTrafficHandler.php
 */
namespace Features\ApiMonitoring\GetApiTraffic;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class GetApiTrafficHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetApiTrafficQuery $query): array
    {
        return $this->repository->getTraffic($query->filters);
    }
}
