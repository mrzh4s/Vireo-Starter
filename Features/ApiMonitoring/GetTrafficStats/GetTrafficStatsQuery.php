<?php
/**
 * GetTrafficStatsQuery
 * Query for retrieving traffic statistics
 * File: Features/ApiMonitoring/GetTrafficStats/GetTrafficStatsQuery.php
 */
namespace Features\ApiMonitoring\GetTrafficStats;

class GetTrafficStatsQuery
{
    public function __construct(
        public string $period = '24h'
    ) {}
}
