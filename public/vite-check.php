<?php
/**
 * Vite Asset Diagnostic Tool
 * Access this file via: https://yourdomain.com/vite-check.php
 * Delete this file after debugging!
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vite Asset Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: white; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Vite Asset Diagnostic</h1>
    
    <h2>1. Build Folder Structure</h2>
    <?php
    $buildPath = __DIR__ . '/build';
    $manifestPath = $buildPath . '/manifest.json';
    $assetsPath = $buildPath . '/assets';
    
    echo '<p><strong>Build folder exists:</strong> ' . (is_dir($buildPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . '</p>';
    echo '<p><strong>Manifest file exists:</strong> ' . (file_exists($manifestPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . '</p>';
    echo '<p><strong>Assets folder exists:</strong> ' . (is_dir($assetsPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . '</p>';
    
    if (file_exists($manifestPath)) {
        echo '<h3>Manifest Contents:</h3>';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        echo '<pre>' . json_encode($manifest, JSON_PRETTY_PRINT) . '</pre>';
        
        if (isset($manifest['resources/css/app.css']['file'])) {
            $cssFile = $assetsPath . '/' . $manifest['resources/css/app.css']['file'];
            echo '<p><strong>CSS file exists:</strong> ' . (file_exists($cssFile) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . '</p>';
            if (file_exists($cssFile)) {
                echo '<p><strong>CSS file size:</strong> ' . filesize($cssFile) . ' bytes</p>';
            }
        }
        
        if (isset($manifest['resources/js/app.js']['file'])) {
            $jsFile = $assetsPath . '/' . $manifest['resources/js/app.js']['file'];
            echo '<p><strong>JS file exists:</strong> ' . (file_exists($jsFile) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . '</p>';
        }
    }
    ?>
    
    <h2>2. File Permissions</h2>
    <?php
    if (is_dir($buildPath)) {
        echo '<p><strong>Build folder permissions:</strong> ' . substr(sprintf('%o', fileperms($buildPath)), -4) . '</p>';
    }
    if (file_exists($manifestPath)) {
        echo '<p><strong>Manifest file permissions:</strong> ' . substr(sprintf('%o', fileperms($manifestPath)), -4) . '</p>';
    }
    ?>
    
    <h2>3. Direct File Access URLs</h2>
    <?php
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        
        if (isset($manifest['resources/css/app.css']['file'])) {
            $cssUrl = $baseUrl . '/build/assets/' . $manifest['resources/css/app.css']['file'];
            echo '<p><strong>CSS URL:</strong> <a href="' . $cssUrl . '" target="_blank">' . $cssUrl . '</a></p>';
        }
        
        if (isset($manifest['resources/js/app.js']['file'])) {
            $jsUrl = $baseUrl . '/build/assets/' . $manifest['resources/js/app.js']['file'];
            echo '<p><strong>JS URL:</strong> <a href="' . $jsUrl . '" target="_blank">' . $jsUrl . '</a></p>';
        }
    }
    ?>
    
    <h2>4. Laravel Environment</h2>
    <?php
    if (file_exists(__DIR__ . '/../.env')) {
        echo '<p><strong>.env file exists:</strong> <span class="success">YES</span></p>';
    } else {
        echo '<p><strong>.env file exists:</strong> <span class="error">NO</span></p>';
    }
    
    if (file_exists(__DIR__ . '/../vendor')) {
        echo '<p><strong>Vendor folder exists:</strong> <span class="success">YES</span></p>';
    }
    ?>
    
    <h2>5. Directory Listing</h2>
    <?php
    if (is_dir($buildPath)) {
        echo '<h3>Build folder contents:</h3>';
        echo '<pre>';
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($buildPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            echo str_replace($buildPath, '', $file->getPathname()) . "\n";
        }
        echo '</pre>';
    }
    ?>
    
    <hr>
    <p class="info"><strong>Note:</strong> Delete this file (vite-check.php) after debugging for security!</p>
</body>
</html>

