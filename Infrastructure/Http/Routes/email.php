<?php

use Vireo\Framework\Http\Router;
use Infrastructure\Http\Controllers\Email\TrackOpenController;
use Infrastructure\Http\Controllers\Email\TrackClickController;

/**
 * Email Tracking Routes
 *
 * Routes for email open and click tracking.
 */

// Track email open (1x1 pixel)
Router::get('/email/track/open/{token}', [TrackOpenController::class, 'track']);

// Track email click (redirect)
Router::get('/email/track/click/{token}', [TrackClickController::class, 'track']);
