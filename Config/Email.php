<?php

/**
 * Email Configuration
 *
 * Configuration for email sending, SMTP connections, queue management,
 * rate limiting, tracking, and attachment handling.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified.
    |
    */
    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Configure all available mailers and their SMTP settings. You can define
    | multiple mailers for different purposes (transactional, marketing, etc.)
    |
    */
    'mailers' => [
        'smtp' => [
            'driver' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'), // tls, ssl, or null
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => 30,
            'local_domain' => env('MAIL_EHLO_DOMAIN', 'localhost'),
            'verify_peer' => true,
        ],

        'gmail' => [
            'driver' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => env('GMAIL_USERNAME'),
            'password' => env('GMAIL_APP_PASSWORD'), // Use App Password, not regular password
            'timeout' => 30,
            'local_domain' => env('MAIL_EHLO_DOMAIN', 'localhost'),
        ],

        'sendgrid' => [
            'driver' => 'smtp',
            'host' => 'smtp.sendgrid.net',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'apikey',
            'password' => env('SENDGRID_API_KEY'),
            'timeout' => 30,
        ],

        'mailgun' => [
            'driver' => 'smtp',
            'host' => 'smtp.mailgun.org',
            'port' => 587,
            'encryption' => 'tls',
            'username' => env('MAILGUN_USERNAME'),
            'password' => env('MAILGUN_PASSWORD'),
            'timeout' => 30,
        ],

        'ses' => [
            'driver' => 'smtp',
            'host' => env('SES_HOST', 'email-smtp.us-east-1.amazonaws.com'),
            'port' => 587,
            'encryption' => 'tls',
            'username' => env('SES_USERNAME'),
            'password' => env('SES_PASSWORD'),
            'timeout' => 30,
        ],

        'postmark' => [
            'driver' => 'smtp',
            'host' => 'smtp.postmarkapp.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => env('POSTMARK_TOKEN'),
            'password' => env('POSTMARK_TOKEN'),
            'timeout' => 30,
        ],

        'mailtrap' => [
            'driver' => 'smtp',
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'encryption' => 'tls',
            'username' => env('MAILTRAP_USERNAME'),
            'password' => env('MAILTRAP_PASSWORD'),
            'timeout' => 30,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | Default sender address and name for all outgoing emails. Can be
    | overridden per email.
    |
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Vireo Framework'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how emails are queued and processed. Enable queueing for
    | better performance and reliability.
    |
    */
    'queue' => [
        'enabled' => env('MAIL_QUEUE', true),
        'connection' => env('MAIL_QUEUE_CONNECTION', 'database'),
        'batch_size' => 100, // Number of emails to process in one batch
        'retry_after' => 90, // Seconds before retry
        'max_attempts' => 3, // Maximum retry attempts
        'retry_delay' => 60, // Base delay between retries (exponential backoff)
        'failed_job_retention' => 30, // Days to keep failed emails
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Control email sending rate to comply with SMTP provider limits and
    | prevent being flagged as spam.
    |
    */
    'rate_limit' => [
        'enabled' => true,
        'per_hour' => env('MAIL_RATE_LIMIT_HOUR', 500), // Max emails per hour
        'per_minute' => env('MAIL_RATE_LIMIT_MINUTE', 10), // Max emails per minute
        'per_second' => env('MAIL_RATE_LIMIT_SECOND', 2), // Max emails per second
        'per_connection' => 100, // Reopen SMTP connection after X emails
        'connection_lifetime' => 300, // Close connection after X seconds

        // Provider-specific limits (will override global if using that provider)
        'providers' => [
            'gmail' => ['per_day' => 500, 'per_hour' => 100],
            'sendgrid' => ['per_second' => 100], // Based on your plan
            'ses' => ['per_second' => 14], // AWS SES limit
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Tracking
    |--------------------------------------------------------------------------
    |
    | Enable tracking for email opens, clicks, and other events. Useful for
    | analytics and campaign management.
    |
    */
    'tracking' => [
        'enabled' => env('MAIL_TRACKING', true),
        'track_opens' => true,
        'track_clicks' => true,
        'pixel_url' => env('APP_URL', 'http://localhost') . '/email/track/open',
        'click_url' => env('APP_URL', 'http://localhost') . '/email/track/click',
        'store_location' => true, // Store geo location data (requires IP lookup)
        'store_user_agent' => true,
        'retention_days' => 90, // Days to keep tracking data
    ],

    /*
    |--------------------------------------------------------------------------
    | Attachment Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how email attachments are handled and stored.
    |
    */
    'attachments' => [
        'default_disk' => env('MAIL_ATTACHMENT_DISK', 'local'),
        'max_size' => env('MAIL_ATTACHMENT_MAX_SIZE', 10 * 1024 * 1024), // 10MB default
        'max_attachments' => 10, // Maximum number of attachments per email
        'allowed_types' => [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'txt', 'csv', 'rtf', 'odt', 'ods', 'odp',
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp',
            'zip', 'tar', 'gz', '7z', 'rar',
            'mp3', 'mp4', 'avi', 'mov', 'wmv',
            'eml', 'msg', 'ics',
        ],
        'cleanup_after_send' => false, // Delete attachments after successful send
        'temp_path' => storage_path('email/attachments/temp'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Configuration
    |--------------------------------------------------------------------------
    |
    | Configure email template rendering and caching.
    |
    */
    'templates' => [
        'engine' => 'blade', // blade or php
        'path' => resource_path('views/email'),
        'cache' => env('MAIL_TEMPLATE_CACHE', true),
        'cache_lifetime' => 3600, // Seconds
        'default_layout' => 'email.layouts.default',

        // Auto-convert HTML to plain text if text version not provided
        'auto_plain_text' => true,

        // Global template variables available in all templates
        'global_vars' => [
            'app_name' => env('APP_NAME', 'Vireo Framework'),
            'app_url' => env('APP_URL', 'http://localhost'),
            'support_email' => env('MAIL_SUPPORT_EMAIL', 'support@example.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Campaign Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for bulk email campaigns.
    |
    */
    'campaigns' => [
        'default_rate_limit' => 50, // Emails per hour for campaigns
        'pause_between_batches' => 60, // Seconds to wait between batches
        'enable_unsubscribe' => true,
        'unsubscribe_url' => env('APP_URL', 'http://localhost') . '/email/unsubscribe',
        'require_double_optin' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Headers
    |--------------------------------------------------------------------------
    |
    | Additional headers to include in all outgoing emails.
    |
    */
    'headers' => [
        'X-Mailer' => 'Vireo Framework Mailer',
        'X-Priority' => '3', // 1 (highest) to 5 (lowest), 3 is normal
        // 'List-Unsubscribe' => '<mailto:unsubscribe@example.com>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Spam Prevention
    |--------------------------------------------------------------------------
    |
    | Settings to help prevent emails from being marked as spam.
    |
    */
    'spam_prevention' => [
        'add_list_unsubscribe_header' => true,
        'add_precedence_bulk_header' => true, // For bulk emails
        'validate_dns' => true, // Validate recipient domain has MX records
        'check_spf' => false, // Check SPF records (requires DNS lookup)
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configure email logging for debugging and auditing.
    |
    */
    'logging' => [
        'enabled' => env('MAIL_LOGGING', true),
        'channel' => 'email', // Log channel to use
        'log_level' => env('MAIL_LOG_LEVEL', 'info'),
        'log_body' => false, // Log email body content (can be large)
        'log_recipients' => true,
        'log_attachments' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Development & Testing
    |--------------------------------------------------------------------------
    |
    | Settings for development and testing environments.
    |
    */
    'testing' => [
        'catch_all' => env('MAIL_CATCH_ALL', null), // Redirect all emails to this address
        'log_only' => env('MAIL_LOG_ONLY', false), // Don't send, just log
        'fake_send' => env('MAIL_FAKE_SEND', false), // Simulate sending without SMTP
    ],
];