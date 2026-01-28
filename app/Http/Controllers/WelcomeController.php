<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Services\IpInfoService;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected IpInfoService $ipInfoService;

    public function __construct(IpInfoService $ipInfoService)
    {
        $this->ipInfoService = $ipInfoService;
    }

    function __invoke(Request $request)
    {
        $popularCabinets = cache()->remember(
            'welcome_page_popular_cabinets',
            config('app.cache_ttl'),
            function () {
                return \App\Models\Cabinet::query()
                    ->with([
                        'doctor:id,name,email',
                        'doctor.media'
                    ])
                    ->withCount('appointments')
                    ->orderByDesc('appointments_count')
                    ->take(6)
                    ->get();
            }
        );

        // Get user's location based on IP address
        $userLocation = $this->getUserLocation($request);

        return view('welcome', compact('popularCabinets', 'userLocation'));
    }

    /**
     * Get the real IP address from various headers
     */
    private function getRealIpAddress(Request $request): string
    {
        // Check various headers that might contain the real IP
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                // X_FORWARDED_FOR can contain multiple IPs, take the first one
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Fallback to Laravel's ip() method
        return $request->ip();
    }

    /**
     * Get user location based on IP address
     */
    private function getUserLocation(Request $request): array
    {
        try {
            // Get the client's real IP address, checking various headers
            $ipAddress = $this->getRealIpAddress($request);
            
            // Handle local development (use a default IP for testing)
            if (in_array($ipAddress, ['127.0.0.1', '::1', 'localhost'])) {
                // For local development, let's use a Barcelona IP for testing
                $ipAddress = '2.139.0.0'; // Barcelona IP range
            }

            // Get location data from IpInfoService
            $country = $this->ipInfoService->getCountry($ipAddress);
            $city = $this->ipInfoService->getCity($ipAddress);
            $ipInfo = $this->ipInfoService->getIpInfo($ipAddress);

            // Extract coordinates if available
            $latitude = null;
            $longitude = null;
            
            // Handle ipgeolocation.io response structure
            if (isset($ipInfo['location']['latitude'])) {
                $latitude = (float) $ipInfo['location']['latitude'];
                $longitude = (float) $ipInfo['location']['longitude'];
            } elseif (isset($ipInfo['loc']) && strpos($ipInfo['loc'], ',') !== false) {
                // Fallback to old format if available
                [$lat, $lng] = explode(',', $ipInfo['loc']);
                $latitude = (float) trim($lat);
                $longitude = (float) trim($lng);
            }

            return [
                'country' => $country,
                'city' => $city,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'ip_address' => $ipAddress,
                'full_data' => $ipInfo
            ];

        } catch (\Exception $e) {
            // Fallback to Barcelona coordinates if service fails
            return [
                'country' => 'ES',
                'city' => 'Barcelona',
                'latitude' => 41.3851,
                'longitude' => 2.1734,
                'ip_address' => $request->ip(),
                'full_data' => []
            ];
        }
    }
}
