<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class LanguageHelper
{
    /**
     * Get the current language code for the user
     *
     * @return string
     */
    public static function getCurrentLanguage()
    {
        // Check if user is logged in and has a language preference
        if (Auth::check() && Auth::user()->lang) {
            return strtolower(Auth::user()->lang);
        }
        
        // Otherwise fall back to session or default
        return session('language', config('app.locale', 'en'));
    }

    /**
     * Load a specific language file
     *
     * @param string $file
     * @param string $language
     * @return array
     */
    public static function loadLanguageFile($file, $language = null)
    {
        $language = $language ?: self::getCurrentLanguage();
        $cacheKey = "lang_{$language}_{$file}";
        
        return Cache::remember($cacheKey, 86400, function () use ($file, $language) {
            $path = resource_path("lang/{$language}/{$file}.json");
            
            if (File::exists($path)) {
                return json_decode(File::get($path), true);
            }
            
            // Fallback to default language
            $defaultLang = config('app.fallback_locale', 'en');
            $fallbackPath = resource_path("lang/{$defaultLang}/{$file}.json");
            
            if (File::exists($fallbackPath)) {
                return json_decode(File::get($fallbackPath), true);
            }
            
            return [];
        });
    }

    /**
     * Get a language string
     *
     * @param string $key Key using dot notation or direct key
     * @param array $replace Values to replace in string
     * @param string $file Language file to use
     * @return string
     */
    public static function get($key, $replace = [], $file = 'auth')
    {
        $langData = self::loadLanguageFile($file);
        
        // Handle direct keys (without dot notation)
        if (strpos($key, '.') === false && isset($langData[$key])) {
            $value = $langData[$key];
        } else {
            // Handle dot notation
            $value = Arr::get($langData, $key, $key);
        }
        
        // If value not found, use the key as fallback
        if ($value === $key) {
            return $key;
        }
        
        // Replace placeholders
        if (!empty($replace) && is_string($value)) {
            foreach ($replace as $k => $v) {
                $value = str_replace('{'.$k.'}', $v, $value);
            }
        }
        
        return $value;
    }

    /**
     * Set the application language
     *
     * @param string $language
     * @return void
     */
    public static function setLanguage($language)
    {
        $language = strtolower($language);
        $availableLanguages = config('app.available_locales', ['en']);
        
        if (!in_array($language, $availableLanguages)) {
            $language = config('app.fallback_locale', 'en');
        }
        
        session(['language' => $language]);
        
        if (Auth::check()) {
            Auth::user()->update(['lang' => $language]);
        }
        
        app()->setLocale($language);
    }
} 