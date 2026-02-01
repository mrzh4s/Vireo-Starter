<?php
/**
 * GetApiTrafficQuery
 * Query for retrieving API traffic logs
 * File: Features/ApiMonitoring/GetApiTraffic/GetApiTrafficQuery.php
 */
namespace Features\ApiMonitoring\GetApiTraffic;

class GetApiTrafficQuery
{
    public function __construct(
        public array $filters = []
    ) {}
}
