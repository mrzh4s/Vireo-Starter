<?php

/**
 * Storage Configuration
 * File: Config/Storage.php
 *
 * Configure multiple storage disks (Local, FTP, SFTP, S3, etc.)
 * Each disk can have different drivers and settings
 *
 * Available Drivers:
 * - local: Local filesystem storage
 * - ftp: FTP server storage
 * - sftp: SFTP/SSH storage (requires ssh2 extension)
 * - s3: Amazon S3 or S3-compatible storage
 */

return [
    /**
     * Default Storage Disk
     * This will be used when no disk is specified
     */
    'default' => env('STORAGE_DISK', 'local'),

    /**
     * Storage Disks
     *
     * Configure as many disks as you need
     * Each disk has its own driver and configuration
     */
    'disks' => [
        /**
         * Local Filesystem Storage
         * Store files on the local server
         */
        'local' => [
            'driver' => 'local',
            'root' => ROOT_PATH . '/storage',
            'url' => env('APP_URL', 'http://localhost') . '/storage',
        ],

        /**
         * Public Storage
         * Accessible via web browser
         */
        'public' => [
            'driver' => 'local',
            'root' => ROOT_PATH . '/public/uploads',
            'url' => env('APP_URL', 'http://localhost') . '/uploads',
        ],

        /**
         * Temporary Storage
         * For temporary files that can be deleted
         */
        'temp' => [
            'driver' => 'local',
            'root' => ROOT_PATH . '/storage/temp',
            'url' => null,
        ],

        /**
         * FTP Storage
         * Store files on FTP server
         *
         * Uses FTP connections from Config/Ftp.php
         */
        'ftp' => [
            'driver' => 'ftp',
            'connection' => env('STORAGE_FTP_CONNECTION', 'default'), // From Config/Ftp.php
            'root' => env('STORAGE_FTP_ROOT', '/uploads'),
            'url' => env('STORAGE_FTP_URL'),
        ],

        /**
         * Backup FTP Storage
         * Separate FTP for backups
         */
        'backup' => [
            'driver' => 'ftp',
            'connection' => env('STORAGE_BACKUP_CONNECTION', 'backup'),
            'root' => env('STORAGE_BACKUP_ROOT', '/backups'),
            'url' => null,
        ],

        /**
         * SFTP Storage
         * Store files on SFTP server
         * Requires: php-ssh2 extension
         */
        'sftp' => [
            'driver' => 'sftp',
            'host' => env('SFTP_HOST', 'sftp.example.com'),
            'port' => env('SFTP_PORT', 22),
            'username' => env('SFTP_USERNAME', 'username'),
            'password' => env('SFTP_PASSWORD', ''),
            // Or use key-based auth:
            // 'privateKey' => env('SFTP_PRIVATE_KEY', '/path/to/key'),
            // 'publicKey' => env('SFTP_PUBLIC_KEY', '/path/to/key.pub'),
            // 'passphrase' => env('SFTP_PASSPHRASE', ''),
            'root' => env('SFTP_ROOT', '/uploads'),
            'url' => env('SFTP_URL'),
        ],

        /**
         * Amazon S3 Storage
         * Store files on S3 or S3-compatible service
         *
         * Compatible with:
         * - Amazon S3
         * - DigitalOcean Spaces
         * - MinIO
         * - Wasabi
         * - Backblaze B2
         */
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'), // For S3-compatible services
        ],

        /**
         * DigitalOcean Spaces
         * S3-compatible storage from DigitalOcean
         */
        'spaces' => [
            'driver' => 's3',
            'key' => env('DO_SPACES_KEY'),
            'secret' => env('DO_SPACES_SECRET'),
            'region' => env('DO_SPACES_REGION', 'nyc3'),
            'bucket' => env('DO_SPACES_BUCKET'),
            'endpoint' => env('DO_SPACES_ENDPOINT', 'https://nyc3.digitaloceanspaces.com'),
            'url' => env('DO_SPACES_URL'),
        ],

        /**
         * MinIO Storage
         * Self-hosted S3-compatible object storage
         */
        'minio' => [
            'driver' => 's3',
            'key' => env('MINIO_ACCESS_KEY'),
            'secret' => env('MINIO_SECRET_KEY'),
            'region' => env('MINIO_REGION', 'us-east-1'),
            'bucket' => env('MINIO_BUCKET'),
            'endpoint' => env('MINIO_ENDPOINT', 'http://localhost:9000'),
            'url' => env('MINIO_URL'),
        ],

        /**
         * CDN Storage
         * Use FTP to upload to CDN
         */
        'cdn' => [
            'driver' => 'ftp',
            'connection' => env('CDN_FTP_CONNECTION', 'cdn'),
            'root' => env('CDN_ROOT', '/'),
            'url' => env('CDN_URL', 'https://cdn.example.com'),
        ],
    ],

    /**
     * Global Storage Settings
     */
    'global' => [
        // Default visibility for new files
        'visibility' => 'private', // 'public' or 'private'

        // Default file permissions (octal)
        'file_permissions' => 0644,

        // Default directory permissions (octal)
        'directory_permissions' => 0755,

        // Maximum file upload size (bytes)
        'max_file_size' => 10 * 1024 * 1024, // 10 MB

        // Allowed file extensions
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx',
            'xls', 'xlsx', 'txt', 'csv', 'zip',
        ],

        // Logging configuration for storage operations
        'logging' => [
            'enabled' => env('LOG_STORAGE_OPERATIONS', true),
            'log_failures_only' => env('LOG_STORAGE_FAILURES_ONLY', true),
            'include_file_sizes' => env('LOG_STORAGE_FILE_SIZES', false),
        ],
    ],
];
