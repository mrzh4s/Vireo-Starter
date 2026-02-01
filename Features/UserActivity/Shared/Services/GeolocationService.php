<?php
/**
 * GeolocationService
 * Service for fetching geolocation data from IP address
 * File: Features/UserActivity/Shared/Services/GeolocationService.php
 */
namespace Features\UserActivity\Shared\Services;

use Features\UserActivity\Shared\Exceptions\LocationFetchFailedException;

class GeolocationService
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('location.api.url') ?? env('LOCATION_API_URL', '');
        $this->apiKey = config('location.api.key') ?? env('LOCATION_API_KEY', '');
    }

    /**
     * Fetch location data for an IP address
     *
     * @param string $ipAddress
     * @return array Location data
     * @throws LocationFetchFailedException
     */
    public function fetchLocation(string $ipAddress): array
    {
        // Skip geolocation for localhost
        if (in_array($ipAddress, ['127.0.0.1', '::1', 'localhost'])) {
            return [];
        }

        // Skip if API not configured
        if (empty($this->apiUrl) || empty($this->apiKey)) {
            return [];
        }

        try {
            $url = "{$this->apiUrl}?apiKey={$this->apiKey}&ip={$ipAddress}";
            $response = @file_get_contents($url);

            if ($response === false) {
                throw new LocationFetchFailedException(
                    "Failed to fetch location for IP: {$ipAddress}"
                );
            }

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new LocationFetchFailedException(
                    "Invalid JSON response from geolocation API"
                );
            }

            return $data;

        } catch (\Exception $e) {
            logger('activity')->warning('Geolocation fetch failed', [
                'ip' => $ipAddress,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
