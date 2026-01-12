<?php

/**
 * Remove Unnecessary Comments from Source Code
 *
 * This script removes:
 * - Single line comments that are obvious (e.g., // Get user)
 * - Commented out code blocks
 * - Debugging comments (e.g., // dd(), // var_dump())
 * - TODO comments
 * - Empty comment blocks
 *
 * This script preserves:
 * - PHPDoc blocks (/** ... */)
 * - Copyright/license headers
 * - Complex logic explanations
 * - @param, @return, @var annotations
 */

class CommentCleaner
{
    private $stats = [
        'files_processed' => 0,
        'comments_removed' => 0,
        'lines_removed' => 0,
        'files_modified' => 0,
    ];

    private $dryRun = true;

    public function __construct($dryRun = true)
    {
        $this->dryRun = $dryRun;
    }

    public function clean()
    {
        echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
        echo "║           REMOVE UNNECESSARY COMMENTS FROM SOURCE CODE               ║\n";
        echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

        if ($this->dryRun) {
            echo "🔍 DRY RUN MODE - No files will be modified\n";
            echo "   Review the output, then run with --execute to apply changes\n\n";
        } else {
            echo "⚠️  EXECUTE MODE - Files will be modified!\n";
            echo "   Make sure you have a backup before proceeding.\n\n";
            echo "Press ENTER to continue or Ctrl+C to cancel...";
            fgets(STDIN);
            echo "\n";
        }

        $directories = [
            'app/Http/Controllers',
            'app/Models',
            'app/Http/Middleware',
            'app/Notifications',
            'routes',
            'config',
        ];

        foreach ($directories as $dir) {
            $this->processDirectory($dir);
        }

        $this->printStats();
    }

    private function processDirectory($directory)
    {
        $path = __DIR__ . '/' . $directory;

        if (!is_dir($path)) {
            return;
        }

        echo "\n┌─────────────────────────────────────────────────────────────────────┐\n";
        echo "│ Processing: {$directory}\n";
        echo "└─────────────────────────────────────────────────────────────────────┘\n";

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }
    }

    private function processFile($filePath)
    {
        $this->stats['files_processed']++;

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $lines = explode("\n", $content);
        $newLines = [];
        $commentsRemoved = 0;

        $inMultilineComment = false;
        $multilineCommentStart = 0;
        $multilineBuffer = [];

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            $trimmedLine = trim($line);

            // Handle multiline comments (/* ... */)
            if (!$inMultilineComment && preg_match('/\/\*(?!\*)/', $line)) {
                $inMultilineComment = true;
                $multilineCommentStart = $i;
                $multilineBuffer = [$line];

                // Check if comment closes on same line
                if (preg_match('/\*\//', $line)) {
                    $inMultilineComment = false;
                    // Check if it's a simple comment to remove
                    if ($this->shouldRemoveMultilineComment($multilineBuffer)) {
                        $commentsRemoved++;
                        continue; // Skip this line
                    } else {
                        $newLines[] = $line; // Keep it
                        $multilineBuffer = [];
                    }
                }
                continue;
            }

            if ($inMultilineComment) {
                $multilineBuffer[] = $line;
                if (preg_match('/\*\//', $line)) {
                    $inMultilineComment = false;
                    // Check if entire comment block should be removed
                    if ($this->shouldRemoveMultilineComment($multilineBuffer)) {
                        $commentsRemoved += count($multilineBuffer);
                    } else {
                        // Keep the comment
                        foreach ($multilineBuffer as $commentLine) {
                            $newLines[] = $commentLine;
                        }
                    }
                    $multilineBuffer = [];
                }
                continue;
            }

            // Skip PHPDoc blocks (/** ... */) - always keep these
            if (preg_match('/^\s*\/\*\*/', $line)) {
                $newLines[] = $line;
                continue;
            }

            // Handle single-line comments (//)
            if (preg_match('/^\s*\/\//', $trimmedLine)) {
                if ($this->shouldRemoveSingleLineComment($trimmedLine)) {
                    $commentsRemoved++;
                    continue; // Skip this line
                }
            }

            // Handle inline comments and remove if unnecessary
            if (preg_match('/^(.+?)\s*\/\/(.+)$/', $line, $matches)) {
                $code = trim($matches[1]);
                $comment = trim($matches[2]);

                if ($this->shouldRemoveInlineComment($comment, $code)) {
                    $newLines[] = rtrim($code); // Keep code, remove comment
                    $commentsRemoved++;
                    continue;
                }
            }

            // Keep the line as-is
            $newLines[] = $line;
        }

        $newContent = implode("\n", $newLines);

        if ($newContent !== $originalContent) {
            $this->stats['files_modified']++;
            $this->stats['comments_removed'] += $commentsRemoved;
            $this->stats['lines_removed'] += (count($lines) - count($newLines));

            $relativePath = str_replace(__DIR__ . '/', '', $filePath);
            echo "  ✓ {$relativePath}\n";
            echo "    Removed: {$commentsRemoved} comments, " . (count($lines) - count($newLines)) . " lines\n";

            if (!$this->dryRun) {
                file_put_contents($filePath, $newContent);
            }
        }
    }

    private function shouldRemoveSingleLineComment($comment)
    {
        // Remove comment markers
        $comment = trim(str_replace('//', '', $comment));

        // Keep empty comments (might be section dividers)
        if (empty($comment)) {
            return false;
        }

        // Patterns to REMOVE
        $removePatterns = [
            '/^(Get|Set|Create|Update|Delete|Add|Remove|Check|Verify|Validate)\s+/i', // Obvious actions
            '/^TODO/i',
            '/^FIXME/i',
            '/^DEBUG/i',
            '/^TEMP/i',
            '/^dd\(|var_dump\(|dump\(|print_r\(/i', // Debug functions
            '/^Test/i',
            '/^\$/', // Variable references
            '/^This (is|will|should|must)/i', // Obvious explanations
            '/^Returns?$/i',
            '/^Loop through/i',
            '/^If .+ then/i',
            '/^Check if/i',
            '/^Commented out/', // Self-referential
        ];

        foreach ($removePatterns as $pattern) {
            if (preg_match($pattern, $comment)) {
                return true;
            }
        }

        // Keep comments that are:
        // - Long explanations (>80 chars)
        if (strlen($comment) > 80) {
            return false;
        }

        // - Section headers with === or ---
        if (preg_match('/={3,}|={3,}/', $comment)) {
            return false;
        }

        // - Contain "IMPORTANT", "NOTE", "WARNING"
        if (preg_match('/\b(IMPORTANT|NOTE|WARNING|CAUTION|CRITICAL)\b/i', $comment)) {
            return false;
        }

        return false; // Default: keep if unsure
    }

    private function shouldRemoveInlineComment($comment, $code)
    {
        // Remove inline comments that just repeat the code
        $comment = trim($comment);

        // Patterns to REMOVE
        $removePatterns = [
            '/^(Get|Set|Create|Update|Delete|Add|Remove)\s+/i',
            '/^Returns?/i',
            '/^TODO/i',
            '/^FIXME/i',
        ];

        foreach ($removePatterns as $pattern) {
            if (preg_match($pattern, $comment)) {
                return true;
            }
        }

        return false;
    }

    private function shouldRemoveMultilineComment($lines)
    {
        $comment = implode("\n", $lines);

        // Never remove PHPDoc blocks
        if (preg_match('/\/\*\*/', $comment)) {
            return false;
        }

        // Check if it's just commented out code
        if (preg_match('/\/\*\s*\n\s*[\$a-zA-Z]/', $comment)) {
            return true; // Likely commented code
        }

        // Remove if it contains typical debug patterns
        if (preg_match('/(var_dump|print_r|dd\(|dump\()/i', $comment)) {
            return true;
        }

        // Keep if it's a meaningful comment block
        $cleanComment = preg_replace('/\/\*|\*\/|\*/', '', $comment);
        $cleanComment = trim($cleanComment);

        if (strlen($cleanComment) > 50) {
            return false; // Keep longer explanations
        }

        return false; // Default: keep if unsure
    }

    private function printStats()
    {
        echo "\n╔═══════════════════════════════════════════════════════════════════════╗\n";
        echo "║                           CLEANUP SUMMARY                             ║\n";
        echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

        echo "Files processed:    {$this->stats['files_processed']}\n";
        echo "Files modified:     {$this->stats['files_modified']}\n";
        echo "Comments removed:   {$this->stats['comments_removed']}\n";
        echo "Lines removed:      {$this->stats['lines_removed']}\n\n";

        if ($this->dryRun) {
            echo "🔍 This was a DRY RUN - no files were actually modified.\n";
            echo "   To apply these changes, run:\n";
            echo "   php remove_unnecessary_comments.php --execute\n\n";
        } else {
            echo "✅ Files have been modified!\n";
            echo "   Please test your application to ensure everything works.\n\n";
        }
    }
}

// Run the script
$dryRun = !in_array('--execute', $argv);
$cleaner = new CommentCleaner($dryRun);
$cleaner->clean();
