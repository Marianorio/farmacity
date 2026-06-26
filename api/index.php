<?php

define('LARAVEL_START', microtime(true));

$_ENV['APP_DEBUG'] = 'true';
$_ENV['APP_ENV'] = 'local';

// Force HTTPS so asset() generates https:// URLs on Vercel
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;

error_reporting(E_ALL & ~E_DEPRECATED);

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Use /tmp for writable storage on Vercel serverless
$storagePath = getenv('LARAVEL_STORAGE_PATH') ?: '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath.'/app/public', 0755, true);
    @mkdir($storagePath.'/framework/cache/data', 0755, true);
    @mkdir($storagePath.'/framework/sessions', 0755, true);
    @mkdir($storagePath.'/framework/views', 0755, true);
    @mkdir($storagePath.'/logs', 0755, true);
}

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    header('Content-Type: text/plain');
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Type: " . get_class($e) . "\n";
}
