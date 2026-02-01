<?php
/**
 * GetTrafficErrorsQuery
 * Query for retrieving traffic errors
 * File: Features/ApiMonitoring/GetTrafficErrors/GetTrafficErrorsQuery.php
 */
namespace Features\ApiMonitoring\GetTrafficErrors;

class GetTrafficErrorsQuery
{
    public function __construct(
        public int $limit = 100
    ) {}
}
