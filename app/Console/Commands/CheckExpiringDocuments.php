<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\Document;
use App\Models\User;
use App\Notifications\DriverLicenseExpiringNotification;
use App\Notifications\UnitRegistrationExpiringNotification;
use App\Notifications\DocumentExpiringNotification;
use Carbon\Carbon;

class CheckExpiringDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:check-expiring {--days=30 : Number of days before expiration to send notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for documents, licenses, and registrations expiring soon and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $this->info("Checking for documents expiring in the next {$days} days...");

        $notificationCount = 0;

        // Check driver licenses
        $notificationCount += $this->checkDriverLicenses($days);

        // Check unit registrations
        $notificationCount += $this->checkUnitRegistrations($days);

        // Check general documents
        $notificationCount += $this->checkGeneralDocuments($days);

        $this->info("Expiration check completed. {$notificationCount} notification(s) sent.");
    }

    /**
     * Check for expiring driver licenses
     */
    protected function checkDriverLicenses($days)
    {
        $count = 0;
        $expiryThreshold = Carbon::now()->addDays($days);

        $drivers = Driver::with(['operator.user'])
            ->where('approval_status', 'approved')
            ->whereNotNull('license_expiry')
            ->whereDate('license_expiry', '<=', $expiryThreshold)
            ->whereDate('license_expiry', '>=', Carbon::now())
            ->get();

        foreach ($drivers as $driver) {
            // Get the user through the operator relationship
            $operatorUser = $driver->operator && $driver->operator->user ? $driver->operator->user : null;

            if ($operatorUser && $operatorUser->email) {
                try {
                    $operatorUser->notify(new DriverLicenseExpiringNotification($driver));
                    $this->line("Sent driver license expiry notification for: {$driver->first_name} {$driver->last_name} (License: {$driver->license_number})");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send notification for driver {$driver->id}: " . $e->getMessage());
                }
            }

            // Also notify admin users
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->email) {
                    try {
                        $admin->notify(new DriverLicenseExpiringNotification($driver));
                        $count++;
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification to admin: " . $e->getMessage());
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Check for expiring unit registrations
     */
    protected function checkUnitRegistrations($days)
    {
        $count = 0;
        $expiryThreshold = Carbon::now()->addDays($days);

        $units = Unit::with(['operator.user'])
            ->where('approval_status', 'approved')
            ->whereNotNull('registration_expiry')
            ->whereDate('registration_expiry', '<=', $expiryThreshold)
            ->whereDate('registration_expiry', '>=', Carbon::now())
            ->get();

        foreach ($units as $unit) {
            // Get the user through the operator relationship
            $operatorUser = $unit->operator && $unit->operator->user ? $unit->operator->user : null;

            if ($operatorUser && $operatorUser->email) {
                try {
                    $operatorUser->notify(new UnitRegistrationExpiringNotification($unit));
                    $this->line("Sent unit registration expiry notification for: {$unit->plate_number}");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send notification for unit {$unit->id}: " . $e->getMessage());
                }
            }

            // Also notify admin users
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->email) {
                    try {
                        $admin->notify(new UnitRegistrationExpiringNotification($unit));
                        $count++;
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification to admin: " . $e->getMessage());
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Check for expiring general documents
     */
    protected function checkGeneralDocuments($days)
    {
        $count = 0;
        $expiryThreshold = Carbon::now()->addDays($days);

        $documents = Document::where('status', '!=', 'expired')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', $expiryThreshold)
            ->whereDate('expiry_date', '>=', Carbon::now())
            ->get();

        foreach ($documents as $document) {
            // Get the owner of this document (polymorphic relationship)
            $owner = $document->documentable;

            if ($owner) {
                // Try to get the user associated with this document
                $user = null;

                if (method_exists($owner, 'user')) {
                    $user = $owner->user;
                } elseif ($owner instanceof User) {
                    $user = $owner;
                }

                if ($user && $user->email) {
                    try {
                        $user->notify(new DocumentExpiringNotification($document));
                        $this->line("Sent document expiry notification for: {$document->document_name}");
                        $count++;
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification for document {$document->id}: " . $e->getMessage());
                    }
                }
            }

            // Also notify admin users
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->email) {
                    try {
                        $admin->notify(new DocumentExpiringNotification($document));
                        $count++;
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification to admin: " . $e->getMessage());
                    }
                }
            }
        }

        return $count;
    }
}
