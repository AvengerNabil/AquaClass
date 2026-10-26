<?php

// Serve static assets directly if they exist in public
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Clean any potential Unicode BOM from environment variables
foreach (['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_SSLMODE', 'APP_KEY', 'APP_ENV', 'APP_DEBUG', 'SESSION_DRIVER', 'CACHE_STORE', 'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'GOOGLE_REDIRECT_URI'] as $envVar) {
    $v = getenv($envVar);
    if ($v !== false) {
        $clean = preg_replace('/^\xEF\xBB\xBF/', '', trim($v));
        putenv("$envVar=$clean");
        $_ENV[$envVar] = $clean;
        $_SERVER[$envVar] = $clean;
    }
}

putenv('APP_DEBUG=true');
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

if (isset($_GET['diag'])) {
    header('Content-Type: text/plain');
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
    echo "ENV APP_KEY: " . (getenv('APP_KEY') ? 'SET' : 'NOT SET') . "\n";
    echo "ENV DB_CONNECTION: " . getenv('DB_CONNECTION') . "\n";
    echo "ENV DB_HOST: " . getenv('DB_HOST') . "\n";
    exit;
}

// Vercel Serverless Entrypoint for AquaClass (Laravel 11)
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Redirect all Laravel writable locations to /tmp
$cacheDir = '/tmp/bootstrap/cache';
$serverlessVars = [
    'VERCEL' => '1',
    'APP_SERVICES_CACHE' => $cacheDir . '/services.php',
    'APP_PACKAGES_CACHE' => $cacheDir . '/packages.php',
    'APP_CONFIG_CACHE' => $cacheDir . '/config.php',
    'APP_ROUTES_CACHE' => $cacheDir . '/routes-v7.php',
    'APP_EVENTS_CACHE' => $cacheDir . '/events.php',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'LOG_CHANNEL' => 'stderr',
];

foreach ($serverlessVars as $k => $v) {
    putenv("$k=$v");
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
}

try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "Application Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    $curr = $e;
    while ($prev = $curr->getPrevious()) {
        echo "\nCaused by: " . $prev->getMessage() . "\n";
        echo "File: " . $prev->getFile() . " on line " . $prev->getLine() . "\n";
        $curr = $prev;
    }
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    if (file_exists('/tmp/storage/logs/laravel.log')) {
        echo "\n--- Laravel Log ---\n" . substr(file_get_contents('/tmp/storage/logs/laravel.log'), -2000);
    }
}
