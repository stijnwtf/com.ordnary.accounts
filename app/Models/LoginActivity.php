<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class LoginActivity extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logLogin($user, $request)
    {
        $userAgent = $request->userAgent();
        $browser = self::getBrowser($userAgent);
        $platform = self::getPlatform($userAgent);
        $deviceType = self::getDeviceType($userAgent);

        return self::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
            'location' => self::getLocation($request->ip()),
            'status' => 'success'
        ]);
    }

    private static function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome')) return 'Chrome';
        if (strpos($userAgent, 'Firefox')) return 'Firefox';
        if (strpos($userAgent, 'Safari')) return 'Safari';
        if (strpos($userAgent, 'Edge')) return 'Edge';
        if (strpos($userAgent, 'MSIE') || strpos($userAgent, 'Trident/7')) return 'Internet Explorer';
        return 'Unknown';
    }

    private static function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows')) return 'Windows';
        if (strpos($userAgent, 'Mac')) return 'macOS';
        if (strpos($userAgent, 'Linux')) return 'Linux';
        if (strpos($userAgent, 'Android')) return 'Android';
        if (strpos($userAgent, 'iOS')) return 'iOS';
        return 'Unknown';
    }

    private static function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile')) return 'Mobile';
        if (strpos($userAgent, 'Tablet')) return 'Tablet';
        return 'Desktop';
    }

    private static function getLocation($ip)
    {
        // Skip location lookup for local/private IPs
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return 'Local';
        }

        try {
            // Use cURL for better reliability
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,city");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; LocationBot/1.0)');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($response === false || !empty($error)) {
                \Log::warning("cURL error for IP {$ip}: " . $error);
                return 'Unknown';
            }
            
            if ($httpCode !== 200) {
                \Log::warning("HTTP error for IP {$ip}: HTTP {$httpCode}");
                return 'Unknown';
            }
            
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::warning("JSON decode error for IP {$ip}: " . json_last_error_msg());
                return 'Unknown';
            }
            
            // Log the response for debugging
            \Log::info("Geolocation response for IP {$ip}: " . json_encode($data));
            
            if (!isset($data['status']) || $data['status'] !== 'success') {
                \Log::warning("API error for IP {$ip}: " . ($data['message'] ?? 'Unknown error'));
                return 'Unknown';
            }
            
            $city = $data['city'] ?? '';
            $countryCode = $data['countryCode'] ?? '';
            
            if (empty($city) && empty($countryCode)) {
                return 'Unknown';
            }
            
            if (empty($city)) {
                return $countryCode;
            }
            
            if (empty($countryCode)) {
                return $city;
            }
            
            return $city . ', ' . $countryCode;
            
        } catch (Exception $e) {
            \Log::error('Exception in getLocation for IP: ' . $ip . '. Error: ' . $e->getMessage());
            return 'Unknown';
        }
    }
}
