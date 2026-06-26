<?php

define('LARAVEL_START', microtime(true));

// Use /tmp for writable storage on Vercel serverless
$storagePath = env('LARAVEL_STORAGE_PATH', $_ENV['LARAVEL_STORAGE_PATH'] ?? '/tmp/storage');
if (!is_dir($storagePath)) {
    @mkdir($storagePath.'/app/public', 0755, true);
    @mkdir($storagePath.'/framework/cache/data', 0755, true);
    @mkdir($storagePath.'/framework/sessions', 0755, true);
    @mkdir($storagePath.'/framework/views', 0755, true);
    @mkdir($storagePath.'/logs', 0755, true);
}

// Determine if the application is in maintenance mode...
$maintenance = __DIR__.'/../storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
