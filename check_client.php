<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Client;

$client = Client::where('client_id', 'client_qkgFE65LI2oo5VZ4m66d9rPm6OlgMODb')->first();

if ($client) {
    echo "Client gevonden: " . $client->name . PHP_EOL;
    echo "Redirect URIs (raw): " . $client->redirect_uris . PHP_EOL;
    echo "Parsed URIs:" . PHP_EOL;
    
    // Test both the old method and new method
    $urisOld = json_decode($client->redirect_uris, true);
    echo "  - Old method (json_decode): " . var_export($urisOld, true) . PHP_EOL;
    
    $urisNew = $client->redirect_uris_array;
    echo "  - New method (accessor): " . var_export($urisNew, true) . PHP_EOL;
    
    if ($urisNew) {
        foreach ($urisNew as $uri) {
            echo "  - " . $uri . PHP_EOL;
        }
    } else {
        echo "  Geen geldige URIs gevonden" . PHP_EOL;
    }
} else {
    echo "Client niet gevonden voor client_id: client_qkgFE65LI2oo5VZ4m66d9rPm6OlgMODb" . PHP_EOL;
}
