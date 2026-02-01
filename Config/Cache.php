<?php
/**
 * Cache Configuration
 *
 * Configure cache stores and default cache driver for the Vireo Framework.
 * Supports multiple drivers: file, redis, memcached, memcache, array, database
 */

return [
    /**
     * Default Cache Store
     *
     * This cache store will be used as the default for all caching operations.
     * You can switch between stores based on your environment.
     *
     * Supported: "file", "redis", "memcached", "memcache", "array", "database"
     */
    'default' => env('CACHE_DRIVER', 'file'),

    /**
     * Default TTL (Time To Live)
     *
     * Default time to live for cached items in seconds.
     * Set to null for no expiration.
     */
    'ttl' => (int) env('CACHE_TTL', 3600), // 1 hour

    /**
     * Cache Key Prefix
     *
     * Prefix for all cache keys to avoid collisions with other applications.
     */
    'prefix' => env('CACHE_PREFIX', 'pop_cache'),

    /**
     * Cache Stores
     *
     * Here you may define all of the cache "stores" for your application.
     * Each store can use a different driver and configuration.
     */
    'stores' => [
        /**
         * File Cache Store
         *
         * File-based cache using the local filesystem.
         * No external dependencies required.
         */
        'file' => [
            'driver' => 'file',
            'path' => ROOT_PATH . '/storage/cache',
            'prefix' => env('CACHE_PREFIX', 'pop_cache'),
            'ttl' => 3600, // 1 hour
        ],

        /**
         * Redis Cache Store
         *
         * High-performance in-memory cache using Redis.
         * Requires: php-redis extension
         */
        'redis' => [
            'driver' => 'redis',
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'password' => env('REDIS_PASSWORD', null),
            'database' => env('REDIS_DB', 0),
            'prefix' => env('REDIS_PREFIX', 'pop_cache:'),
            'persistent' => true,
            'persistent_id' => 'pop_redis',
            'timeout' => 0.0,
            'ttl' => 3600,
        ],

        /**
         * Memcached Cache Store
         *
         * Distributed memory cache using Memcached.
         * Requires: php-memcached extension (modern, libmemcached-based)
         */
        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID', 'pop_memcached'),
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
                // Add more servers for distributed caching
                // [
                //     'host' => '127.0.0.2',
                //     'port' => 11211,
                //     'weight' => 100,
                // ],
            ],
            'prefix' => env('MEMCACHED_PREFIX', 'pop_cache:'),
            'ttl' => 3600,
        ],

        /**
         * Memcache Cache Store (Legacy)
         *
         * Legacy Memcache driver.
         * Requires: php-memcache extension (old, less features)
         */
        'memcache' => [
            'driver' => 'memcache',
            'servers' => [
                [
                    'host' => env('MEMCACHE_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHE_PORT', 11211),
                ],
            ],
            'prefix' => env('MEMCACHE_PREFIX', 'pop_cache:'),
            'ttl' => 3600,
        ],

        /**
         * Array Cache Store
         *
         * In-memory cache for current request only.
         * Data is lost after request ends. Useful for testing.
         */
        'array' => [
            'driver' => 'array',
            'prefix' => env('CACHE_PREFIX', 'pop_cache'),
            'ttl' => null, // No expiration (in-memory only)
        ],

        /**
         * Database Cache Store
         *
         * Store cache in database table.
         * Requires: cache table migration
         */
        'database' => [
            'driver' => 'database',
            'connection' => env('CACHE_DB_CONNECTION', 'app'),
            'table' => env('CACHE_DB_TABLE', 'cache'),
            'prefix' => env('CACHE_PREFIX', 'pop_cache'),
            'ttl' => 3600,
        ],
    ],
];
