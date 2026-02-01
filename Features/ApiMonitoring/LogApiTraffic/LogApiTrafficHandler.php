<?php
/**
 * LogApiTrafficHandler
 * Handles logging of API traffic
 * File: Features/ApiMonitoring/LogApiTraffic/LogApiTrafficHandler.php
 */
namespace Features\ApiMonitoring\LogApiTraffic;

use Features\ApiMonitoring\Shared\Ports\TrafficRepositoryInterface;
use Exception;

class LogApiTrafficHandler
{
    private TrafficRepositoryInterface $repository;

    public function __construct(TrafficRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(LogApiTrafficCommand $command): bool
    {
        try {
            return $this->repository->saveTraffic(
                traffic: $command->traffic,
                url: $command->url,
                method: $command->method,
                headers: $command->headers,
                body: $command->body,
                response: $command->response,
                status: $command->status,
                responseTime: $command->responseTime
            );

        } catch (Exception $e) {
            logger('api')->error('Failed to log API traffic', [
                'traffic' => $command->traffic,
                'url' => $command->url,
                'method' => $command->method,
                'status' => $command->status,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
