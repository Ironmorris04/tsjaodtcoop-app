<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncOperatorEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync email addresses between users and operators tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking email sync between users and operators tables...');
        $this->newLine();

        $mismatches = DB::table('users')
            ->join('operators', 'users.id', '=', 'operators.user_id')
            ->where('users.email', '!=', DB::raw('operators.email'))
            ->select(
                'users.id as user_id',
                'users.email as user_email',
                'operators.id as operator_id',
                'operators.email as operator_email',
                'users.name'
            )
            ->get();

        if ($mismatches->isEmpty()) {
            $this->info('✓ All emails are in sync!');
            return 0;
        }

        $this->warn("Found {$mismatches->count()} email mismatches:");
        $this->newLine();

        foreach ($mismatches as $mismatch) {
            $this->line("User ID: {$mismatch->user_id} | Name: {$mismatch->name}");
            $this->line("  User email:     {$mismatch->user_email}");
            $this->line("  Operator email: {$mismatch->operator_email}");
            $this->line('  ---');
        }

        $this->newLine();

        if ($this->confirm('Do you want to sync these emails (update operators table to match users table)?', true)) {
            foreach ($mismatches as $mismatch) {
                DB::table('operators')
                    ->where('id', $mismatch->operator_id)
                    ->update(['email' => $mismatch->user_email]);

                $this->info("✓ Updated Operator ID {$mismatch->operator_id} ({$mismatch->name})");
            }

            $this->newLine();
            $this->info('✓ All emails have been synced successfully!');
        } else {
            $this->warn('Email sync cancelled.');
        }

        return 0;
    }
}
