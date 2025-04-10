<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\LanguageHelper;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set application locale based on user preference
        $this->setApplicationLocale();
        
        // Add a blade directive for language strings
        Blade::directive('lang', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::get($expression); ?>";
        });
        
        // Add a helper function
        if (!function_exists('lang')) {
            function lang($key, $replace = [], $file = 'auth') {
                return LanguageHelper::get($key, $replace, $file);
            }
        }
    }
    
    /**
     * Set application locale based on user preference
     */
    protected function setApplicationLocale()
    {
        $locale = LanguageHelper::getCurrentLanguage();
        app()->setLocale($locale);
    }
} 