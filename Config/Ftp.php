<?php
/**
 * FTP Configuration
 * File: Config/Ftp.php
 *
 * Manages FTP connection configurations for the Storage system.
 * Part of Framework\Storage\Connections\FTPConnection
 *
 * Features:
 * - Dynamic FTP connection discovery from environment variables
 * - Support for multiple FTP servers
 * - Pattern: {NAME}_FTP_HOST, {NAME}_FTP_PORT, etc.
 *
 * Environment Variable Patterns:
 * - FTP_HOST, FTP_PORT, etc. → 'default' connection
 * - BACKUP_FTP_HOST, BACKUP_FTP_PORT, etc. → 'backup' connection
 * - CDN_FTP_HOST, etc. → 'cdn' connection
 *
 * Usage via Storage System (Recommended):
 * Storage::disk('ftp')->put('file.txt', 'content')
 * Storage::disk('ftp')->get('file.txt')
 *
 * Usage via FTP Helpers:
 * ftp_connection('default')          // Get FTP connection resource
 * ftp_connection('backup')           // Get backup FTP connection
 * ftp_upload('local.txt', 'remote.txt', 'cdn')
 * ftp_download('remote.txt', 'local.txt')
 *
 * Direct FTPConnection Usage:
 * FTPConnection::getInstance()->getConnection('default')
 * FTPConnection::getInstance()->upload('local.txt', 'remote.txt', 'backup')
 *
 * Note: FTP connections are configured here but can be accessed through:
 * - Storage system (for file operations)
 * - Helper functions (for convenience)
 * - FTPConnection class (for direct control)
 */

/**
 * Auto-discover FTP connections from environment variables
 * Scans for patterns like {NAME}_FTP_HOST, {NAME}_FTP_PORT, etc.
 */
function discoverFtpConnections() {
    $connections = [];
    $envVars = $_ENV + $_SERVER; // Merge both sources

    // Find all unique connection names (e.g., BACKUP, CDN, MEDIA)
    $connectionNames = [];
    foreach ($envVars as $key => $value) {
        if (preg_match('/^([A-Z_]+)?FTP_(HOST|USERNAME)$/', $key, $matches)) {
            // Handle both "FTP_HOST" (default) and "BACKUP_FTP_HOST" (named)
            $prefix = $matches[1] ? rtrim($matches[1], '_') : 'DEFAULT';
            $connectionNames[$prefix] = true;
        }
    }

    // Build configuration for each discovered connection
    foreach (array_keys($connectionNames) as $name) {
        // For DEFAULT, use "FTP_" prefix, otherwise use "{NAME}_FTP_" prefix
        $prefix = ($name === 'DEFAULT') ? 'FTP_' : $name . '_FTP_';
        $connName = strtolower($name === 'DEFAULT' ? 'default' : $name);

        // Skip if no host is defined
        if (!env($prefix . 'HOST')) {
            continue;
        }

        $config = [
            'host' => env($prefix . 'HOST'),
            'port' => env($prefix . 'PORT', 21),
            'username' => env($prefix . 'USERNAME', 'anonymous'),
            'password' => env($prefix . 'PASSWORD', ''),
            'path' => env($prefix . 'PATH', '/'),
            'passive' => env($prefix . 'PASSIVE', true),
            'ssl' => env($prefix . 'SSL', false),
            'timeout' => env($prefix . 'TIMEOUT', 90),
        ];

        $connections[$connName] = $config;
    }

    return $connections;
}

return [
    /**
     * Default FTP connection name
     * Used when calling ftp_connection() or FTPConnection::getInstance()->getConnection() without arguments
     * Also used by Storage::disk('ftp') if no specific connection is configured in Config/Storage.php
     */
    'default' => env('FTP_DEFAULT', 'default'),

    /**
     * FTP Connections
     * Auto-discovered from environment variables
     */
    'connections' => discoverFtpConnections(),

    /**
     * Global FTP Settings
     */
    'global' => [
        'max_retries' => 3,
        'retry_delay' => 1, // seconds
        'verify_ssl' => false,
    ],
];
