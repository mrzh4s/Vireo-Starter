<?php
/**
 * GetTrafficErrorsHandler
 * Handles retrieval of traffic errors
 * File: Features/ApiMonitoring/GetTrafficErrors/GetTrafficErrorsHandler.php
 */
namespace Features\ApiMonitoring\GetTrafficErrors;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class GetTrafficErrorsHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetTrafficErrorsQuery $query): array
    {
        return $this->repository->getErrors($query->limit);
    }
}
