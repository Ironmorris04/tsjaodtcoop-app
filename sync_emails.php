<?php
// Script to sync emails from users table to operators table

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Syncing emails from users table to operators table...\n\n";

$mismatches = DB::table('users')
    ->join('operators', 'users.id', '=', 'operators.user_id')
    ->where('users.email', '!=', DB::raw('operators.email'))
    ->select('users.id as user_id', 'users.email as user_email', 'operators.id as operator_id', 'operators.email as operator_email', 'users.name')
    ->get();

if ($mismatches->isEmpty()) {
    echo "✓ All emails are already in sync!\n";
} else {
    echo "Found " . $mismatches->count() . " email mismatches. Syncing...\n\n";
    
    foreach ($mismatches as $mismatch) {
        echo "Updating Operator ID {$mismatch->operator_id} ({$mismatch->name})\n";
        echo "  FROM: {$mismatch->operator_email}\n";
        echo "  TO:   {$mismatch->user_email}\n";
        
        DB::table('operators')
            ->where('id', $mismatch->operator_id)
            ->update(['email' => $mismatch->user_email]);
        
        echo "  ✓ Updated\n\n";
    }
    
    echo "✓ All emails have been synced successfully!\n";
}
