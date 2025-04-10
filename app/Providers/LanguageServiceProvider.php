<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\LanguageHelper;
use Illuminate\Translation\Translator;

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
            return "<?php echo lang($expression); ?>";
        });
        
        // Make sure @lang and trans() functions work consistently with our JSON files
        $this->app->extend('translator', function ($translator, $app) {
            // Create a proxy class to handle translation consistently
            return new class($translator) extends Translator {
                protected $baseTranslator;
                
                public function __construct($baseTranslator) {
                    $this->baseTranslator = $baseTranslator;
                    // Pass original parameters to parent
                    parent::__construct(
                        $baseTranslator->getLoader(),
                        $baseTranslator->getLocale()
                    );
                }
                
                // Override the get method to handle direct JSON keys properly
                public function get($key, array $replace = [], $locale = null, $fallback = true) {
                    // Check if this is a flat key (no dots) and not a file-based translation
                    if (strpos($key, '.') === false && strpos($key, '::') === false) {
                        $args = func_get_args();
                        // Use the file parameter if provided (4th parameter in @lang)
                        $file = isset($args[4]) ? $args[4] : 'auth';
                        return lang($key, $replace, $file);
                    }
                    
                    // Otherwise use original translator
                    return $this->baseTranslator->get($key, $replace, $locale, $fallback);
                }
                
                // Make sure we proxy all other methods to the base translator
                public function __call($method, $args) {
                    return call_user_func_array([$this->baseTranslator, $method], $args);
                }
            };
        });
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