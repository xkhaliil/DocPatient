<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class IpInfoService
{
    public string $endpoint;
    public string $api_key;
    private array $fallbackLocations;
    private int $maxRetries = 3;
    private int $retryDelay = 1; // seconds

    public function __construct()
    {
        $this->endpoint = config('services.ipgeolocation.endpoint', 'https://api.ipgeolocation.io/v2/ipgeo');
        $this->api_key = config('services.ipgeolocation.api_key', 'API_KEY');
        
        // Predefined fallback locations for different scenarios
        $this->fallbackLocations = [
            'barcelona' => ['country_code2' => 'ES', 'country' => 'Spain', 'city' => 'Barcelona', 'latitude' => '41.3851', 'longitude' => '2.1734'],
            'new_york' => ['country_code2' => 'US', 'country' => 'United States', 'city' => 'New York', 'latitude' => '40.7128', 'longitude' => '-74.0060'],
            'london' => ['country_code2' => 'GB', 'country' => 'United Kingdom', 'city' => 'London', 'latitude' => '51.5074', 'longitude' => '-0.1278'],
            'tokyo' => ['country_code2' => 'JP', 'country' => 'Japan', 'city' => 'Tokyo', 'latitude' => '35.6762', 'longitude' => '139.6503'],
        ];
    }

    public function getCountry(string $ip_address): string
    {
        try {
            // Validate IP address
            if (!$this->isValidIpAddress($ip_address)) {
                Log::warning('Invalid IP address format in getCountry: ' . $ip_address);
                return 'unknown';
            }

            $data = $this->getIpInfo($ip_address);
            
            // Handle different response structures from various providers
            $countryCode = $this->extractCountryCode($data);
            
            if ($countryCode && $countryCode !== 'unknown') {
                return strtoupper($countryCode);
            }

            Log::warning('Could not extract country code from IP data', ['ip' => $ip_address, 'data' => $data]);
            return 'unknown';

        } catch (Exception $e) {
            Log::error('Failed to get country from IP service', [
                'ip' => $ip_address,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'unknown';
        }
    }

    /**
     * Extract country code from various API response formats
     */
    private function extractCountryCode(array $data): string
    {
        // Try different possible response structures
        $possibleKeys = [
            'location.country_code2',
            'country_code2',
            'country',
            'country_code',
            'countryCode',
            'country_code_iso',
            'iso_code'
        ];

        foreach ($possibleKeys as $key) {
            $value = $this->getNestedValue($data, $key);
            if ($value && strlen($value) === 2) {
                return $value;
            }
        }

        return 'unknown';
    }

    public function getCity(string $ip_address): string
    {
        try {
            // Validate IP address
            if (!$this->isValidIpAddress($ip_address)) {
                Log::warning('Invalid IP address format in getCity: ' . $ip_address);
                return 'Unknown';
            }

            $data = $this->getIpInfo($ip_address);
            
            // Handle different response structures from various providers
            $city = $this->extractCity($data);
            
            if ($city && $city !== 'Unknown') {
                return $city;
            }

            Log::warning('Could not extract city from IP data', ['ip' => $ip_address, 'data' => $data]);
            return 'Unknown';

        } catch (Exception $e) {
            Log::error('Failed to get city from IP service', [
                'ip' => $ip_address,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'Unknown';
        }
    }

    /**
     * Extract city name from various API response formats
     */
    private function extractCity(array $data): string
    {
        // Try different possible response structures
        $possibleKeys = [
            'location.city',
            'city',
            'location.name',
            'location.region',
            'region'
        ];

        foreach ($possibleKeys as $key) {
            $value = $this->getNestedValue($data, $key);
            if ($value && is_string($value) && strlen($value) > 1) {
                return $value;
            }
        }

        return 'Unknown';
    }

    /**
     * Get nested value from array using dot notation
     */
    private function getNestedValue(array $data, string $key)
    {
        $keys = explode('.', $key);
        $value = $data;

        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function getIpInfo(string $ip_address)
    {
        try {
            // Validate IP address format
            if (!$this->isValidIpAddress($ip_address)) {
                Log::warning('Invalid IP address format: ' . $ip_address);
                return $this->getFallbackData();
            }

            // Handle localhost/development scenarios
            if (in_array($ip_address, ['127.0.0.1', '::1', 'localhost'])) {
                return $this->getLocalhostData();
            }

            // Check API key configuration
            if (empty($this->api_key) || $this->api_key === 'API_KEY') {
                Log::warning('IP Geolocation API key not configured properly');
                return $this->getFallbackData();
            }

            // Retry mechanism for transient failures
            for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
                try {
                    $result = $this->makeApiRequest($ip_address);
                    if ($result !== null) {
                        return $result;
                    }
                } catch (Exception $e) {
                    Log::warning("IP Geolocation API attempt {$attempt} failed: " . $e->getMessage());
                    
                    // Don't retry on certain error types
                    if ($this->isNonRetryableError($e)) {
                        break;
                    }
                    
                    // Wait before retry (exponential backoff)
                    if ($attempt < $this->maxRetries) {
                        sleep($this->retryDelay * $attempt);
                    }
                }
            }

            // All attempts failed, return fallback data
            Log::error('All IP Geolocation API attempts failed for IP: ' . $ip_address);
            return $this->getFallbackData();

        } catch (Exception $e) {
            Log::error('Critical error in IP location service: ' . $e->getMessage(), [
                'ip' => $ip_address,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->getFallbackData();
        }
    }

    /**
     * Validate IP address format
     */
    private function isValidIpAddress(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Determine if error is non-retryable
     */
    private function isNonRetryableError(Exception $e): bool
    {
        $message = strtolower($e->getMessage());
        return strpos($message, 'invalid api key') !== false ||
               strpos($message, 'quota exceeded') !== false ||
               strpos($message, 'invalid request') !== false;
    }

    /**
     * Make the actual API request
     */
    private function makeApiRequest(string $ip_address): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->endpoint . '?apiKey=' . $this->api_key . '&ip=' . $ip_address,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10, // Increased timeout for reliability
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        // Handle cURL errors
        if ($response === false) {
            throw new Exception('cURL error: ' . $curl_error);
        }

        // Handle HTTP errors
        if ($http_code !== 200) {
            $this->handleHttpError($http_code, $response);
            return null;
        }

        // Parse JSON response
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }

        // Handle API-specific errors
        if (!$data || isset($data['message']) || isset($data['error'])) {
            $errorMessage = $data['message'] ?? $data['error'] ?? 'Unknown API error';
            throw new Exception('API error: ' . $errorMessage);
        }

        return $data;
    }

    /**
     * Handle HTTP error responses
     */
    private function handleHttpError(int $http_code, string $response): void
    {
        $error_messages = [
            400 => 'Bad request - Invalid IP address format',
            401 => 'Unauthorized - Invalid API key',
            403 => 'Forbidden - API key quota exceeded',
            404 => 'Not found - IP address not found',
            429 => 'Too many requests - Rate limit exceeded',
            500 => 'Server error - Service temporarily unavailable',
            502 => 'Bad gateway - Service temporarily unavailable',
            503 => 'Service unavailable - Service temporarily unavailable',
            504 => 'Gateway timeout - Service temporarily unavailable'
        ];

        $message = $error_messages[$http_code] ?? "HTTP error {$http_code}";
        Log::warning("IP Geolocation API HTTP error: {$message}", [
            'http_code' => $http_code,
            'response' => substr($response, 0, 500)
        ]);

        // Throw exception for retry logic
        throw new Exception($message);
    }

    private function getLocalhostData(): array
    {
        return [
            'ip' => '127.0.0.1',
            'country_code2' => 'US',
            'country' => 'United States',
            'city' => 'Localhost',
            'latitude' => '37.7749',
            'longitude' => '-122.4194'
        ];
    }

    /**
     * Get fallback data with intelligent location selection
     */
    private function getFallbackData(): array
    {
        try {
            $request = request();
            $currentIp = $request ? $request->ip() : '127.0.0.1';
            
            // Try to determine appropriate fallback based on context
            $fallbackLocation = $this->determineSmartFallback();
            
            return [
                'ip' => $currentIp,
                'country_code2' => $fallbackLocation['country_code2'],
                'country' => $fallbackLocation['country'],
                'city' => $fallbackLocation['city'],
                'latitude' => $fallbackLocation['latitude'],
                'longitude' => $fallbackLocation['longitude'],
                'fallback' => true,
                'source' => 'smart_fallback'
            ];
        } catch (Exception $e) {
            // Ultimate fallback - should never reach here
            Log::error('Critical error in fallback data generation: ' . $e->getMessage());
            return [
                'ip' => '127.0.0.1',
                'country_code2' => 'US',
                'country' => 'United States',
                'city' => 'Unknown',
                'latitude' => '37.7749',
                'longitude' => '-122.4194',
                'fallback' => true,
                'source' => 'ultimate_fallback'
            ];
        }
    }

    /**
     * Determine smart fallback location based on context
     */
    private function determineSmartFallback(): array
    {
        try {
            $request = request();
            if (!$request) {
                return $this->fallbackLocations['barcelona'];
            }

            // Check for timezone hints
            $timezone = $request->header('X-Timezone') ?? date_default_timezone_get();
            if ($timezone) {
                $location = $this->getFallbackByTimezone($timezone);
                if ($location) {
                    return $location;
                }
            }

            // Check for language hints
            $language = $request->header('Accept-Language');
            if ($language) {
                $location = $this->getFallbackByLanguage($language);
                if ($location) {
                    return $location;
                }
            }

            // Default to Barcelona for this healthcare application
            return $this->fallbackLocations['barcelona'];

        } catch (Exception $e) {
            Log::warning('Smart fallback determination failed: ' . $e->getMessage());
            return $this->fallbackLocations['barcelona'];
        }
    }

    /**
     * Get fallback location by timezone
     */
    private function getFallbackByTimezone(string $timezone): ?array
    {
        $timezoneMappings = [
            'Europe/Madrid' => 'barcelona',
            'Europe/London' => 'london',
            'America/New_York' => 'new_york',
            'America/Los_Angeles' => 'new_york',
            'Asia/Tokyo' => 'tokyo',
            'Asia/Shanghai' => 'tokyo',
        ];

        $locationKey = $timezoneMappings[$timezone] ?? null;
        return $locationKey ? $this->fallbackLocations[$locationKey] : null;
    }

    /**
     * Get fallback location by language
     */
    private function getFallbackByLanguage(string $language): ?array
    {
        $languageMappings = [
            'es' => 'barcelona',
            'en-US' => 'new_york',
            'en-GB' => 'london',
            'ja' => 'tokyo',
            'zh' => 'tokyo',
        ];

        // Extract primary language code
        $primaryLang = explode(',', $language)[0];
        $langCode = explode('-', $primaryLang)[0];
        
        $locationKey = $languageMappings[$langCode] ?? null;
        return $locationKey ? $this->fallbackLocations[$locationKey] : null;
    }
}