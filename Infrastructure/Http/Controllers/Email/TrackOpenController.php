<?php

namespace Infrastructure\Http\Controllers\Email;

use Vireo\Framework\Email\Tracking\Tracker;

/**
 * Track Email Open Controller
 *
 * Handles email open tracking via 1x1 pixel.
 */
class TrackOpenController
{
    public function track(string $token): void
    {
        $tracker = new Tracker();
        $tracker->trackOpen($token);

        // Return 1x1 transparent GIF
        header('Content-Type: image/gif');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // 1x1 transparent GIF (base64 decoded)
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        exit;
    }
}
