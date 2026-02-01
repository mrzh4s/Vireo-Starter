<?php
/**
 * ExportTrafficCommand
 * Command for exporting traffic data
 * File: Features/ApiMonitoring/ExportTraffic/ExportTrafficCommand.php
 */
namespace Features\ApiMonitoring\ExportTraffic;

class ExportTrafficCommand
{
    public function __construct(
        public array $filters = [],
        public string $format = 'json'  // json, csv, xml
    ) {}
}
