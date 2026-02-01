<?php
/**
 * LogProjectActivityHandler
 * Handles logging of project activity
 * File: Features/UserActivity/LogProjectActivity/LogProjectActivityHandler.php
 */
namespace Features\UserActivity\LogProjectActivity;

use Features\UserActivity\Shared\Ports\ActivityRepositoryInterface;
use Exception;

class LogProjectActivityHandler
{
    private ActivityRepositoryInterface $repository;

    public function __construct(ActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(LogProjectActivityCommand $command): bool
    {
        try {
            return $this->repository->saveProjectActivity(
                systemId: $command->systemId,
                currentFlow: $command->currentFlow,
                username: $command->username,
                details: $command->details,
                authorityId: $command->authorityId
            );

        } catch (Exception $e) {
            logger('activity')->error('Failed to log project activity', [
                'system_id' => $command->systemId,
                'current_flow' => $command->currentFlow,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
