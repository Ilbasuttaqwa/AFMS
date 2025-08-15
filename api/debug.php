<?php

// Debug endpoint untuk monitoring aplikasi Laravel di Vercel
// Akses: https://your-app.vercel.app/api/debug.php

header('Content-Type: text/html; charset=utf-8');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Laravel Debug Information</h1>";
echo "<style>body{font-family:monospace;background:#f5f5f5;padding:20px;} .section{background:white;padding:15px;margin:10px 0;border-radius:5px;border-left:4px solid #007cba;} .error{border-left-color:#dc3545;} .success{border-left-color:#28a745;} .warning{border-left-color:#ffc107;}</style>";

function debug_section($title, $content, $type = 'section') {
    echo "<div class='{$type}'>";
    echo "<h3>{$title}</h3>";
    if (is_array($content) || is_object($content)) {
        echo "<pre>" . json_encode($content, JSON_PRETTY_PRINT) . "</pre>";
    } else {
        echo "<pre>{$content}</pre>";
    }
    echo "</div>";
}

try {
    // PHP Information
    debug_section("📋 PHP Information", [
        'PHP Version' => PHP_VERSION,
        'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'Current Time' => date('Y-m-d H:i:s T'),
        'Memory Limit' => ini_get('memory_limit'),
        'Max Execution Time' => ini_get('max_execution_time'),
        'Upload Max Filesize' => ini_get('upload_max_filesize')
    ]);

    // Directory Information
    debug_section("📁 Directory Information", [
        'Current Working Directory' => getcwd(),
        '__DIR__' => __DIR__,
        'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Not set',
        'Script Filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'Not set'
    ]);

    // File System Check
    $files_to_check = [
        'Laravel Index' => __DIR__ . '/../public/index.php',
        'Composer Autoload' => __DIR__ . '/../vendor/autoload.php',
        'Laravel Bootstrap' => __DIR__ . '/../bootstrap/app.php',
        '.env File' => __DIR__ . '/../.env',
        'Config Cache' => __DIR__ . '/../bootstrap/cache/config.php',
        'Routes Cache' => __DIR__ . '/../bootstrap/cache/routes-v7.php'
    ];

    $file_status = [];
    foreach ($files_to_check as $name => $path) {
        $file_status[$name] = [
            'path' => $path,
            'exists' => file_exists($path),
            'readable' => file_exists($path) ? is_readable($path) : false,
            'size' => file_exists($path) ? filesize($path) : 0
        ];
    }
    
    debug_section("📄 File System Status", $file_status);

    // Environment Variables
    $env_vars = [
        'APP_NAME' => $_ENV['APP_NAME'] ?? 'Not set',
        'APP_ENV' => $_ENV['APP_ENV'] ?? 'Not set',
        'APP_DEBUG' => $_ENV['APP_DEBUG'] ?? 'Not set',
        'APP_URL' => $_ENV['APP_URL'] ?? 'Not set',
        'DB_CONNECTION' => $_ENV['DB_CONNECTION'] ?? 'Not set',
        'CACHE_DRIVER' => $_ENV['CACHE_DRIVER'] ?? 'Not set',
        'SESSION_DRIVER' => $_ENV['SESSION_DRIVER'] ?? 'Not set'
    ];
    
    debug_section("🔧 Environment Variables", $env_vars);

    // Request Information
    debug_section("🌐 Request Information", [
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'Not set',
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'Not set',
        'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Not set',
        'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? 'Not set',
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? 'Not set',
        'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'Not set',
        'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? 'Not set'
    ]);

    // Test Laravel Bootstrap
    echo "<div class='section'>";
    echo "<h3>🚀 Laravel Bootstrap Test</h3>";
    
    $laravel_index = __DIR__ . '/../public/index.php';
    if (file_exists($laravel_index)) {
        echo "<p style='color:green;'>✅ Laravel index.php found</p>";
        
        // Try to load Laravel without executing
        ob_start();
        try {
            // Set minimal environment
            $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'production';
            $_ENV['APP_DEBUG'] = 'false';
            
            echo "<p>🔄 Attempting to load Laravel...</p>";
            
            // Check if we can at least load the autoloader
            $autoload_path = __DIR__ . '/../vendor/autoload.php';
            if (file_exists($autoload_path)) {
                require_once $autoload_path;
                echo "<p style='color:green;'>✅ Composer autoloader loaded successfully</p>";
            } else {
                echo "<p style='color:red;'>❌ Composer autoloader not found at: {$autoload_path}</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color:red;'>❌ Laravel bootstrap failed: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        } catch (Error $e) {
            echo "<p style='color:red;'>❌ Fatal error during Laravel bootstrap: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        ob_end_flush();
        
    } else {
        echo "<p style='color:red;'>❌ Laravel index.php not found at: {$laravel_index}</p>";
    }
    echo "</div>";

    // PHP Extensions
    debug_section("🔌 PHP Extensions", [
        'PDO' => extension_loaded('pdo') ? '✅ Loaded' : '❌ Not loaded',
        'PDO MySQL' => extension_loaded('pdo_mysql') ? '✅ Loaded' : '❌ Not loaded',
        'OpenSSL' => extension_loaded('openssl') ? '✅ Loaded' : '❌ Not loaded',
        'Mbstring' => extension_loaded('mbstring') ? '✅ Loaded' : '❌ Not loaded',
        'Tokenizer' => extension_loaded('tokenizer') ? '✅ Loaded' : '❌ Not loaded',
        'XML' => extension_loaded('xml') ? '✅ Loaded' : '❌ Not loaded',
        'Ctype' => extension_loaded('ctype') ? '✅ Loaded' : '❌ Not loaded',
        'JSON' => extension_loaded('json') ? '✅ Loaded' : '❌ Not loaded',
        'BCMath' => extension_loaded('bcmath') ? '✅ Loaded' : '❌ Not loaded',
        'Fileinfo' => extension_loaded('fileinfo') ? '✅ Loaded' : '❌ Not loaded'
    ]);

} catch (Exception $e) {
    debug_section("❌ Debug Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], 'error');
} catch (Error $e) {
    debug_section("❌ Fatal Debug Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], 'error');
}

echo "<div class='section'>";
echo "<h3>📝 Usage Instructions</h3>";
echo "<p><strong>Debug Mode:</strong> Add <code>?debug=1</code> to any URL to see debug output</p>";
echo "<p><strong>Main App:</strong> <a href='/'>Go to main application</a></p>";
echo "<p><strong>This Debug Page:</strong> <a href='/api/debug.php'>Refresh debug info</a></p>";
echo "</div>";

echo "<p style='text-align:center;color:#666;margin-top:30px;'>Generated at " . date('Y-m-d H:i:s T') . "</p>";