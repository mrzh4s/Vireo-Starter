<?php
/**
 * CleanupOldTrafficCommand
 * Command for cleaning up old traffic logs
 * File: Features/ApiMonitoring/CleanupOldTraffic/CleanupOldTrafficCommand.php
 */
namespace Features\ApiMonitoring\CleanupOldTraffic;

class CleanupOldTrafficCommand
{
    public function __construct(
        public int $daysToKeep = 30
    ) {}
}
