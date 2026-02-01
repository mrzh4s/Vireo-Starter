<?php

namespace Infrastructure\Http\Controllers\Email;

use Vireo\Framework\Email\Tracking\Tracker;

/**
 * Track Email Click Controller
 *
 * Handles email click tracking and redirects to original URL.
 */
class TrackClickController
{
    public function track(string $token): void
    {
        $tracker = new Tracker();
        $originalUrl = $tracker->trackClick($token);

        if ($originalUrl) {
            header("Location: {$originalUrl}", true, 302);
            exit;
        }

        // Invalid token
        http_response_code(404);
        echo "Invalid tracking link";
        exit;
    }
}
