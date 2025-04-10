<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LanguageHelper;

class LanguageController extends Controller
{
    /**
     * Change the application language
     *
     * @param Request $request
     * @param string $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLanguage(Request $request, $language)
    {
        // Validate language
        $availableLanguages = config('app.available_locales', ['en', 'nl']);
        if (!in_array($language, $availableLanguages)) {
            $language = config('app.fallback_locale', 'en');
        }
        
        // Set session language
        session(['language' => $language]);
        
        // Update user language preference if logged in
        if (auth()->check()) {
            auth()->user()->update(['lang' => $language]);
        }
        
        // Set application locale
        app()->setLocale($language);
        
        // Redirect back to previous page
        return redirect()->back();
    }
} 