<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requirement;
use App\Models\Operator;
use App\Notifications\ExpiringDocumentsSummaryNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendExpiringDocumentEmails extends Command
{
    protected $signature = 'notify:expiring-documents';
    protected $description = 'Send expiring document notifications to cooperative and operators';

    public function handle()
    {
        $this->info('Starting to send expiring document notifications...');

        // Days before expiry to send reminders
        $reminderDays = [30, 25, 20, 15];

        // -----------------------------
        // 1. Cooperative Requirements
        // -----------------------------
        $coopRequirementsRaw = Requirement::all();

        $coopRequirements = $coopRequirementsRaw->filter(function ($doc) use ($reminderDays) {
            if (!$doc->expiry_date) return false;
            $daysLeft = Carbon::now()->startOfDay()->diffInDays($doc->expiry_date->startOfDay(), false);
            return in_array($daysLeft, $reminderDays);
        })->map(function ($doc) {
            return (object)[
                'owner_name' => 'Cooperative',
                'type' => $doc->type,
                'formatted_type' => $doc->formatted_type,
                'document_number' => $doc->document_number,
                'expiry_date' => $doc->expiry_date,
            ];
        });

        if ($coopRequirements->count() > 0) {
            Notification::route('mail', 'tsjaodtcooperative@gmail.com')
                ->notify(new ExpiringDocumentsSummaryNotification(
                    $coopRequirements, 
                    'tsjaodtcooperative@gmail.com', 
                    true, 
                    'Cooperative'
                ));
            $this->info('Cooperative email sent.');
        } else {
            $this->info('No cooperative requirements expiring today.');
        }

        // -----------------------------
        // 2. Operator Documents
        // -----------------------------
        $operators = Operator::with(['drivers', 'units', 'operatorIds'])->get();

        foreach ($operators as $operator) {

            $expiringDocs = collect();
            $recipientName = $operator->contact_person ?? $operator->name ?? 'Operator';

            // Operator IDs
            foreach ($operator->operatorIds as $id) {
                if ($id->expiry_date) {
                    $daysLeft = Carbon::now()->startOfDay()->diffInDays($id->expiry_date->startOfDay(), false);
                    if (in_array($daysLeft, $reminderDays)) {
                        $expiringDocs->push((object)[
                            'owner_name' => $recipientName,
                            'type' => $id->id_type,
                            'formatted_type' => $id->id_type,
                            'document_number' => $id->id_number,
                            'expiry_date' => $id->expiry_date,
                        ]);
                    }
                }
            }

            // Drivers’ licenses
            foreach ($operator->drivers as $driver) {
                if ($driver->license_expiry) {
                    $daysLeft = Carbon::now()->startOfDay()->diffInDays($driver->license_expiry->startOfDay(), false);
                    if (in_array($daysLeft, $reminderDays)) {
                        $expiringDocs->push((object)[
                            'owner_name' => $driver->full_name,
                            'type' => 'Driver License',
                            'formatted_type' => 'Driver License',
                            'document_number' => $driver->license_number,
                            'expiry_date' => $driver->license_expiry,
                        ]);
                    }
                }
            }

            // Units registration
            foreach ($operator->units as $unit) {
                if ($unit->registration_expiry) {
                    $daysLeft = Carbon::now()->startOfDay()->diffInDays($unit->registration_expiry->startOfDay(), false);
                    if (in_array($daysLeft, $reminderDays)) {
                        $expiringDocs->push((object)[
                            'owner_name' => $recipientName,
                            'type' => 'Vehicle Registration',
                            'formatted_type' => 'Vehicle Registration - ' . ($unit->plate_no ?? 'N/A'),
                            'document_number' => $unit->unit_id,
                            'expiry_date' => $unit->registration_expiry,
                        ]);
                    }
                }
            }

            if ($expiringDocs->count() === 0) continue;

            $recipientEmail = $operator->email ?? ($operator->user->email ?? null);

            if ($recipientEmail) {
                Notification::route('mail', $recipientEmail)
                    ->notify(new ExpiringDocumentsSummaryNotification(
                        $expiringDocs, 
                        $recipientEmail, 
                        false, 
                        $recipientName
                    ));
                $this->info("Email sent to operator: $recipientEmail");
            }
        }

        $this->info('All notifications sent successfully!');
    }
}
