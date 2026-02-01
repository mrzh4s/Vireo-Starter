<?php
/**
 * GetProjectActivityQuery
 * Query for retrieving project activity
 * File: Features/UserActivity/GetProjectActivity/GetProjectActivityQuery.php
 */
namespace Features\UserActivity\GetProjectActivity;

class GetProjectActivityQuery
{
    public function __construct(
        public ?string $systemId = null
    ) {}
}
