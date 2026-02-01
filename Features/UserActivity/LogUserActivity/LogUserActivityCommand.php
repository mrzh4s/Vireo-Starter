<?php
/**
 * LogUserActivityCommand
 * Command for logging user activity
 * File: Features/UserActivity/LogUserActivity/LogUserActivityCommand.php
 */
namespace Features\UserActivity\LogUserActivity;

class LogUserActivityCommand
{
    public function __construct(
        public string $userId,
        public string $message,
        public string $ipAddress,
        public string $url,
        public string $device
    ) {}
}
