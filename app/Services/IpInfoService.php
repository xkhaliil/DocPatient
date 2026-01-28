<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class IpInfoService
{
    public string $endpoint;
    public string $api_key;

    public function __construct()
    {
        $this->endpoint = config('services.ipgeolocation.endpoint', 'https://api.ipgeolocation.io/v2/ipgeo');
        $this->api_key = config('services.ipgeolocation.api_key', 'API_KEY');
    }

    public function getCountry(string $ip_address): string
    {
        try {
            $data = $this->getIpInfo($ip_address);
            // Handle ipgeolocation.io response structure
            if (isset($data['location']['country_code2'])) {
                return $data['location']['country_code2'];
            }
            return $data['country_code2'] ?? $data['country'] ?? 'unknown';
        } catch (\Exception $e) {
            Log::warning('Failed to get country from IpGeolocation: ' . $e->getMessage());
            return 'unknown';
        }
    }

    public function getCity(string $ip_address): string
    {
        try {
            $data = $this->getIpInfo($ip_address);
            // Handle ipgeolocation.io response structure
            if (isset($data['location']['city'])) {
                return $data['location']['city'];
            }
            return $data['city'] ?? 'unknown';
        } catch (\Exception $e) {
            Log::warning('Failed to get city from IpGeolocation: ' . $e->getMessage());
            return 'unknown';
        }
    }

    public function getIpInfo(string $ip_address)
    {
        try {
            if (in_array($ip_address, ['127.0.0.1', '::1', 'localhost'])) {
                return $this->getLocalhostData();
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->endpoint . '?apiKey=' . $this->api_key . '&ip=' . $ip_address,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array()
            ));

            $response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($http_code !== 200) {
                Log::warning('IpGeolocation API returned non-successful response: ' . $http_code);
                return $this->getFallbackData();
            }

            $data = json_decode($response, true);
            if (!$data || isset($data['message'])) {
                Log::warning('IpGeolocation API error: ' . ($data['message'] ?? 'Invalid response'));
                return $this->getFallbackData();
            }

            return $data;

        } catch (\Exception $e) {
            Log::warning('Failed to get IP info from IpGeolocation: ' . $e->getMessage());
            return $this->getFallbackData();
        }
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

    private function getFallbackData(): array
    {
        return [
            'ip' => request()->ip(),
            'country_code2' => 'US',
            'country' => 'United States',
            'city' => 'Unknown',
            'latitude' => '37.7749',
            'longitude' => '-122.4194'
        ];
    }
}