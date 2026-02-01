<?php

use Vireo\Framework\Logging\LogLevel;

/**
 * Logging Configuration
 *
 * Configure logging handlers, channels, and minimum log levels
 *
 * Available log levels (in order of severity):
 * - LogLevel::EMERGENCY - System is unusable
 * - LogLevel::ALERT     - Action must be taken immediately
 * - LogLevel::CRITICAL  - Critical conditions
 * - LogLevel::ERROR     - Runtime errors
 * - LogLevel::WARNING   - Exceptional occurrences that are not errors
 * - LogLevel::NOTICE    - Normal but significant events
 * - LogLevel::INFO      - Interesting events
 * - LogLevel::DEBUG     - Detailed debug information
 *
 * Available handlers:
 * - 'file'     - Log to files with automatic rotation
 * - 'database' - Log to database table
 * - 'syslog'   - Log to system log
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Handler
    |--------------------------------------------------------------------------
    |
    | This option defines the default log handler that will be used to write
    | log messages. You may specify any of the handlers defined below.
    |
    */
    'default' => env('LOG_HANDLER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Minimum Log Level
    |--------------------------------------------------------------------------
    |
    | This option defines the minimum log level that will be logged.
    | Messages below this level will be ignored.
    |
    | Production: LogLevel::WARNING or LogLevel::ERROR
    | Development: LogLevel::DEBUG
    |
    */
    'min_level' => env('LOG_LEVEL', LogLevel::DEBUG),

    /*
    |--------------------------------------------------------------------------
    | Log Handlers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log handlers for your application.
    | Multiple handlers can be enabled simultaneously.
    |
    */
    'handlers' => [
        'file' => [
            'driver' => 'file',
            'path' => ROOT_PATH . '/storage/logs',
            'filename' => 'app.log',
            'max_size' => 10 * 1024 * 1024, // 10 MB
            'max_files' => 5,
            'permission' => 0664,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => 'app',  // Changed from 'main' to match available connection
            'table' => 'logs',
        ],

        'syslog' => [
            'driver' => 'syslog',
            'ident' => 'vireo-framework',
            'facility' => LOG_USER,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Channel-Specific Configuration
    |--------------------------------------------------------------------------
    |
    | You can configure different handlers and log levels for specific channels.
    | Channels allow you to separate logs by category (app, security, api, etc.)
    |
    | Example:
    | 'channels' => [
    |     'security' => [
    |         'handler' => 'database',
    |         'min_level' => LogLevel::WARNING,
    |     ],
    |     'api' => [
    |         'handler' => 'file',
    |         'min_level' => LogLevel::INFO,
    |     ],
    | ],
    |
    */
    'channels' => [
        'app' => [
            'handler' => 'file',
            'min_level' => LogLevel::DEBUG,
        ],
        'security' => [
            'handler' => 'database',
            'min_level' => LogLevel::WARNING,
        ],
        'database' => [
            'handler' => 'file',
            'min_level' => LogLevel::DEBUG,
        ],
        'performance' => [
            'handler' => 'file',
            'min_level' => LogLevel::INFO,
        ],
        'api' => [
            'handler' => 'file',
            'min_level' => LogLevel::INFO,
        ],
        'activity' => [
            'handler' => 'database',
            'min_level' => LogLevel::INFO,
        ],
        'storage' => [
            'handler' => 'file',
            'min_level' => LogLevel::WARNING,
        ],
        'cli' => [
            'handler' => 'file',
            'min_level' => LogLevel::INFO,
        ],
    ],
];
