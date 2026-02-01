<?php
/**
 * LogProjectActivityCommand
 * Command for logging project activity
 * File: Features/UserActivity/LogProjectActivity/LogProjectActivityCommand.php
 */
namespace Features\UserActivity\LogProjectActivity;

class LogProjectActivityCommand
{
    public function __construct(
        public string $systemId,
        public string $currentFlow,
        public string $username,
        public string $details,
        public ?string $authorityId = null
    ) {}
}
