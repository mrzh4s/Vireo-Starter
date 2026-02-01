<?php
/**
 * LogApiTrafficCommand
 * Command for logging API traffic
 * File: Features/ApiMonitoring/LogApiTraffic/LogApiTrafficCommand.php
 */
namespace Features\ApiMonitoring\LogApiTraffic;

class LogApiTrafficCommand
{
    public function __construct(
        public string $traffic,      // inbound/outbound
        public string $url,
        public string $method,
        public string $headers,
        public string $body,
        public string $response,
        public string $status,       // success/error/failed
        public ?float $responseTime = null
    ) {}
}
