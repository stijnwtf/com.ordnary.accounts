<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
                Log::debug("Loading language file: {$path}");
                $content = File::get($path);
                $data = json_decode($content, true);
                
                // Check if json_decode failed
                if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                    Log::error("Error parsing language file: {$path}", ['error' => json_last_error_msg()]);
                    return [];
                }
                
                return $data;
            }
            
            // Fallback to default language
            $defaultLang = config('app.fallback_locale', 'en');
            $fallbackPath = resource_path("lang/{$defaultLang}/{$file}.json");
            
            if (File::exists($fallbackPath)) {
                Log::debug("Loading fallback language file: {$fallbackPath}");
                return json_decode(File::get($fallbackPath), true);
            }
            
            Log::warning("Language file not found: {$file}.json");
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
        // Debug information
        Log::debug("LanguageHelper::get() called", ['key' => $key, 'file' => $file]);
        
        // Check for dot notation in the key
        $isNestedKey = strpos($key, '.') !== false;
        
        // Load the language file data
        $langData = self::loadLanguageFile($file);
        
        // Debug: log the loaded language data
        Log::debug("Language data for {$file}", ['data' => json_encode($langData)]);
        
        // Handle direct key access (no dot notation)
        if (!$isNestedKey && isset($langData[$key])) {
            $value = $langData[$key];
            Log::debug("Direct key match for {$key}", ['value' => $value]);
        } 
        // Handle dot notation for direct access pattern (like profile.personal_info)
        else if ($isNestedKey) {
            // Try direct access first (some keys might contain dots but still be top-level)
            if (isset($langData[$key])) {
                $value = $langData[$key];
                Log::debug("Direct match for dot-notation key {$key}", ['value' => $value]);
            } else {
                // Split the key at the first dot
                $parts = explode('.', $key, 2);
                $firstPart = $parts[0];
                $secondPart = $parts[1] ?? null;
                
                // Check if the first part exists and the second part is a valid key
                if ($secondPart && isset($langData[$firstPart]) && is_array($langData[$firstPart]) && isset($langData[$firstPart][$secondPart])) {
                    $value = $langData[$firstPart][$secondPart];
                    Log::debug("Nested key match for {$key}", ['value' => $value]);
                } else {
                    // Use Laravel's Arr::get for deeper nesting
                    $value = Arr::get($langData, $key, $key);
                    Log::debug("Arr::get result for {$key}", ['value' => $value]);
                }
            }
        } else {
            // Default fallback to Arr::get for all other cases
            $value = Arr::get($langData, $key, $key);
            Log::debug("Fallback to Arr::get for {$key}", ['value' => $value]);
        }
        
        // If value not found, use the key as fallback
        if ($value === $key) {
            Log::warning("Translation key not found: {$key} in {$file}");
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