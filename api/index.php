<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log function for debugging
function debug_log($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $log_message .= " - Data: " . json_encode($data);
    }
    
    // Log to stderr for Vercel
    error_log($log_message);
    
    // Also echo for immediate visibility during debugging
    if (isset($_GET['debug']) && $_GET['debug'] === '1') {
        echo "<pre>{$log_message}</pre>\n";
    }
}

try {
    debug_log("Starting Vercel PHP handler");
    debug_log("Current working directory", getcwd());
    debug_log("__DIR__", __DIR__);
    debug_log("REQUEST_URI", $_SERVER['REQUEST_URI'] ?? 'not set');
    debug_log("REQUEST_METHOD", $_SERVER['REQUEST_METHOD'] ?? 'not set');
    debug_log("HTTP_HOST", $_SERVER['HTTP_HOST'] ?? 'not set');
    
    // Check if Laravel's public/index.php exists
    $laravel_index = __DIR__ . '/../public/index.php';
    debug_log("Looking for Laravel index at", $laravel_index);
    
    if (!file_exists($laravel_index)) {
        debug_log("ERROR: Laravel index.php not found", $laravel_index);
        http_response_code(500);
        echo "Error: Laravel application not found at {$laravel_index}";
        exit(1);
    }
    
    debug_log("Laravel index.php found, loading application");
    
    // Set environment variables for Laravel
    $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'production';
    $_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'false';
    
    debug_log("Environment", [
        'APP_ENV' => $_ENV['APP_ENV'],
        'APP_DEBUG' => $_ENV['APP_DEBUG'],
        'PHP_VERSION' => PHP_VERSION
    ]);
    
    // Forward Vercel requests to Laravel's public/index.php
    require $laravel_index;
    
    debug_log("Laravel application loaded successfully");
    
} catch (Exception $e) {
    debug_log("EXCEPTION caught", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo "Application Error: " . $e->getMessage();
    
    if (isset($_GET['debug']) && $_GET['debug'] === '1') {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} catch (Error $e) {
    debug_log("FATAL ERROR caught", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo "Fatal Error: " . $e->getMessage();
    
    if (isset($_GET['debug']) && $_GET['debug'] === '1') {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}