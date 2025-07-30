<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Toon de instellingen pagina
     */
    public function index()
    {
        // Haal applicatie-instellingen op
        $settings = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'session_lifetime' => config('session.lifetime'),
            'cache_driver' => config('cache.default'),
            'queue_connection' => config('queue.default'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];

        // Haal systeeminformatie op
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Onbekend',
            'database' => config('database.connections.' . config('database.default') . '.driver'),
            'database_name' => config('database.connections.' . config('database.default') . '.database'),
        ];

        return view('admin.settings.index', compact('settings', 'systemInfo'));
    }

    /**
     * Update de instellingen
     */
    public function update(Request $request)
    {
        // Valideer de instellingen
        $validated = $request->validate([
            'session_lifetime' => 'required|integer|min:1',
            'timezone' => 'required|string',
            'locale' => 'required|string',
        ]);

        // Update de instellingen in de .env file
        $this->updateEnvFile([
            'SESSION_LIFETIME' => $validated['session_lifetime'],
            'APP_TIMEZONE' => $validated['timezone'],
            'APP_LOCALE' => $validated['locale'],
        ]);

        // Clear cache
        Artisan::call('config:clear');
        Cache::flush();

        return redirect()->route('admin.settings')->with('success', 'Instellingen zijn succesvol bijgewerkt.');
    }

    /**
     * Wis de applicatie cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()->route('admin.settings')->with('success', 'Applicatie cache is succesvol gewist.');
    }

    /**
     * Update de .env file
     */
    private function updateEnvFile(array $values)
    {
        $envFile = app()->environmentFilePath();
        $envContents = file_get_contents($envFile);

        foreach ($values as $key => $value) {
            // Als de instelling al bestaat, update deze
            if (strpos($envContents, "{$key}=") !== false) {
                $envContents = preg_replace("/{$key}=(.*)/", "{$key}={$value}", $envContents);
            } else {
                // Anders voeg de instelling toe
                $envContents .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envFile, $envContents);
    }
}