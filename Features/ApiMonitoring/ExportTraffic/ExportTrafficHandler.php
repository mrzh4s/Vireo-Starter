<?php
/**
 * ExportTrafficHandler
 * Handles exporting of traffic data
 * File: Features/ApiMonitoring/ExportTraffic/ExportTrafficHandler.php
 */
namespace Features\ApiMonitoring\ExportTraffic;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;

class ExportTrafficHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ExportTrafficCommand $command): string|false
    {
        return $this->repository->export($command->filters, $command->format);
    }
}
