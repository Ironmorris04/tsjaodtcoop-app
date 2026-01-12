<?php
// Storage diagnostics
echo "<h1>Storage Diagnostics</h1>";

echo "<h2>1. Storage Path Check</h2>";
$storagePath = __DIR__ . '/storage';
echo "Storage symlink path: $storagePath<br>";
echo "Symlink exists: " . (is_link($storagePath) ? 'YES' : 'NO') . "<br>";
echo "Symlink readable: " . (is_readable($storagePath) ? 'YES' : 'NO') . "<br>";

if (is_link($storagePath)) {
    $target = readlink($storagePath);
    echo "Symlink target: $target<br>";
    echo "Target exists: " . (file_exists($target) ? 'YES' : 'NO') . "<br>";
    echo "Target readable: " . (is_readable($target) ? 'YES' : 'NO') . "<br>";
}

echo "<h2>2. Sample Files</h2>";
$publicStoragePath = __DIR__ . '/../storage/app/public';
echo "Public storage path: $publicStoragePath<br>";

// Find sample image files
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicStoragePath));
$images = [];
foreach ($iterator as $file) {
    if ($file->isFile() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
        $images[] = $file->getPathname();
        if (count($images) >= 5) break;
    }
}

echo "<h3>Sample image files found:</h3>";
echo "<ul>";
foreach ($images as $img) {
    $relativePath = str_replace($publicStoragePath, '', $img);
    $relativePath = str_replace('\\', '/', $relativePath);
    $webPath = '/transport-coop-system/public/storage' . $relativePath;
    echo "<li>";
    echo "File: $relativePath<br>";
    echo "Readable: " . (is_readable($img) ? 'YES' : 'NO') . "<br>";
    echo "Size: " . filesize($img) . " bytes<br>";
    echo "<a href='$webPath' target='_blank'>Test Access</a><br>";
    echo "<img src='$webPath' style='max-width:200px; border:1px solid #ccc; margin:10px 0;'><br>";
    echo "</li>";
}
echo "</ul>";

echo "<h2>3. Permissions</h2>";
echo "Current script: " . __FILE__ . "<br>";
echo "Current user: " . get_current_user() . "<br>";
echo "PHP process user: " . (function_exists('posix_geteuid') ? posix_getpwuid(posix_geteuid())['name'] : 'N/A (Windows)') . "<br>";

echo "<h2>4. Laravel Config</h2>";
echo "APP_URL from env: " . getenv('APP_URL') . "<br>";
