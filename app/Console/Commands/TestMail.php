<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('=== Email Configuration ===');
        $this->line('Mailer: ' . config('mail.default'));
        $this->line('Host: ' . config('mail.mailers.smtp.host'));
        $this->line('Port: ' . config('mail.mailers.smtp.port'));
        $this->line('Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->line('Username: ' . config('mail.mailers.smtp.username'));
        $this->line('From: ' . config('mail.from.address'));
        $this->line('');

        $this->info('Sending test email to: ' . $email);

        try {
            Mail::send('emails.registration-approved', [
                'operatorName' => 'Test User',
                'userId' => 'TEST-001',
                'setupUrl' => 'http://localhost/test-setup',
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - TSJAODTCooperative System');
            });

            $this->info('✓ Email sent successfully!');
            $this->info('Check your inbox at: ' . $email);
            $this->line('');
            $this->warn('Note: Check spam/junk folder if not in inbox');
            $this->warn('Gmail may take a few seconds to receive the email');

        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->error('');
            $this->error('Common issues:');
            $this->line('1. Gmail App Password may be incorrect');
            $this->line('2. 2-Step Verification not enabled on Gmail account');
            $this->line('3. Gmail may be blocking less secure apps');
            $this->line('4. Network/firewall blocking port 587');
            $this->line('');
            $this->error('Full error details:');
            $this->line($e->getTraceAsString());
        }
    }
}
