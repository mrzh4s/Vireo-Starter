<?php
/**
 * LogUserActivityHandler
 * Handles logging of user activity
 * File: Features/UserActivity/LogUserActivity/LogUserActivityHandler.php
 */
namespace Features\UserActivity\LogUserActivity;

use Features\UserActivity\Shared\Ports\ActivityRepositoryInterface;
use Features\UserActivity\Shared\Services\GeolocationService;
use Exception;

class LogUserActivityHandler
{
    private ActivityRepositoryInterface $repository;
    private GeolocationService $geolocationService;

    public function __construct(
        ActivityRepositoryInterface $repository,
        GeolocationService $geolocationService
    ) {
        $this->repository = $repository;
        $this->geolocationService = $geolocationService;
    }

    public function handle(LogUserActivityCommand $command): bool
    {
        try {
            // Fetch location data
            $location = $this->geolocationService->fetchLocation($command->ipAddress);

            // Save activity
            return $this->repository->saveUserActivity(
                userId: $command->userId,
                message: $command->message,
                ipAddress: $command->ipAddress,
                url: $command->url,
                device: $command->device,
                location: $location
            );

        } catch (Exception $e) {
            logger('activity')->error('Failed to log user activity', [
                'user_id' => $command->userId,
                'message' => $command->message,
                'error' => $e->getMessage()
            ]);

            // Save without location on failure
            return $this->repository->saveUserActivity(
                userId: $command->userId,
                message: $command->message,
                ipAddress: $command->ipAddress,
                url: $command->url,
                device: $command->device,
                location: []
            );
        }
    }
}
