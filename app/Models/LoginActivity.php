<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        // Skip location detection for local IPs
        if (self::isLocalIp($ip)) {
            return 'Lokale verbinding';
        }

        try {
            // Use ipapi.co for geolocation (free tier allows 1000 requests/month)
            $response = file_get_contents("http://ipapi.co/{$ip}/json/");
            $data = json_decode($response, true);

            if (isset($data['city']) && isset($data['country_name'])) {
                $city = $data['city'];
                $country = $data['country_name'];
                $region = $data['region'] ?? '';
                
                if ($region && $region !== $city) {
                    return "{$city}, {$region}, {$country}";
                }
                return "{$city}, {$country}";
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::warning('Failed to get location for IP: ' . $ip . ' - ' . $e->getMessage());
        }

        // Fallback
        return 'Onbekende locatie';
    }

    private static function isLocalIp($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1']) || 
               filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
