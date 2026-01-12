<?php

/**
 * Conservative Comment Cleaner
 *
 * Removes ONLY truly unnecessary comments:
 * - Commented out code
 * - Debug statements
 * - Empty comment lines
 * - Redundant comments that just repeat the code
 *
 * PRESERVES:
 * - All PHPDoc blocks
 * - Relationship comments
 * - Section dividers
 * - Important notes, warnings, etc.
 * - All complex logic explanations
 */

$dryRun = !in_array('--execute', $argv ?? []);

echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║            CONSERVATIVE COMMENT CLEANUP                               ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

if ($dryRun) {
    echo "🔍 DRY RUN MODE - Showing what would be removed\n";
    echo "   Run with --execute to apply changes\n\n";
} else {
    echo "⚠️  EXECUTE MODE - Files will be modified!\n\n";
}

$stats = [
    'files_scanned' => 0,
    'files_modified' => 0,
    'comments_removed' => 0,
];

$directories = [
    'app/Http/Controllers',
    'app/Models',
    'app/Http/Middleware',
    'app/Notifications',
];

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;

    if (!is_dir($path)) continue;

    echo "\n📁 Processing: {$dir}\n";
    echo str_repeat('─', 70) . "\n";

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') continue;

        $stats['files_scanned']++;
        $filePath = $file->getPathname();
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        $newLines = [];
        $removed = 0;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            $shouldRemove = false;

            // Remove commented out code (but not explanatory comments)
            if (preg_match('/^\s*\/\/\s*[\$a-zA-Z_].*[;=\(\)]/', $line)) {
                $shouldRemove = true;
                $removed++;
            }
            // Remove debug comments
            elseif (preg_match('/^\s*\/\/\s*(dd|var_dump|dump|print_r)\s*\(/', $line)) {
                $shouldRemove = true;
                $removed++;
            }
            // Remove empty // comments
            elseif (preg_match('/^\s*\/\/\s*$/', $line)) {
                $shouldRemove = true;
                $removed++;
            }
            // Remove obvious redundant comments like "// Get user" before $user = ...
            elseif (preg_match('/^\s*\/\/\s*(Get|Set|Create|Update|Delete|Fetch|Retrieve)\s+\w+\s*$/', $line)) {
                // Check next line to see if it's obvious
                $nextLineIndex = array_search($line, $lines) + 1;
                if (isset($lines[$nextLineIndex])) {
                    $nextLine = $lines[$nextLineIndex];
                    if (preg_match('/^\s*\$\w+\s*=/', $nextLine)) {
                        $shouldRemove = true;
                        $removed++;
                    }
                }
            }

            if (!$shouldRemove) {
                $newLines[] = $line;
            }
        }

        if ($removed > 0) {
            $stats['files_modified']++;
            $stats['comments_removed'] += $removed;

            $relativePath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $filePath);
            echo "  ✓ {$relativePath}\n";
            echo "    Removed: {$removed} unnecessary comment(s)\n";

            if (!$dryRun) {
                $newContent = implode("\n", $newLines);
                file_put_contents($filePath, $newContent);
            }
        }
    }
}

echo "\n╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║                           SUMMARY                                     ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

echo "Files scanned:      {$stats['files_scanned']}\n";
echo "Files modified:     {$stats['files_modified']}\n";
echo "Comments removed:   {$stats['comments_removed']}\n\n";

if ($dryRun) {
    echo "🔍 DRY RUN complete. Run with --execute to apply changes.\n";
    echo "   Command: php clean_comments_conservative.php --execute\n\n";
} else {
    echo "✅ Cleanup complete! Test your application.\n\n";
}
