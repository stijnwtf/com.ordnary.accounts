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
        // Set the language
        LanguageHelper::setLanguage($language);
        
        // Redirect back to previous page
        return redirect()->back();
    }
} 