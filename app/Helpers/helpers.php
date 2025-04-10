<?php

if (!function_exists('lang')) {
    /**
     * Get a language string
     *
     * @param string $key Key using dot notation
     * @param array $replace Values to replace in string
     * @param string $file Language file to use
     * @return string
     */
    function lang($key, $replace = [], $file = 'auth') {
        return \App\Helpers\LanguageHelper::get($key, $replace, $file);
    }
} 