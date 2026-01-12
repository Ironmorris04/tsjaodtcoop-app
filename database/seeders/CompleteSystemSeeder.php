<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompleteSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing operator-related data
        echo "Clearing existing data...\n";

        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Transaction::whereNotNull('operator_id')->delete();
        Driver::query()->delete();
        Unit::query()->delete();
        Operator::query()->delete();
        User::where('role', 'operator')->delete();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Creating sample operators with complete data...\n";

        $operatorBusinesses = [
            'Rodriguez Transport Services',
            'Santos Van Rental',
            'Cruz Transport Co.',
            'Reyes Shuttle Service',
            'Garcia Transport',
            'Lopez Van Service',
            'Mendoza Transport',
            'Torres Shuttle Co.',
            'Flores Transport Services',
            'Ramos Van Rental'
        ];

        $bodyNumbers = ['123', '456', '789', '234', '567', '890', '345', '678', '901', '012'];
        $operators = [];
        $currentYear = date('Y');

        foreach ($operatorBusinesses as $index => $businessName) {
            $lastName = explode(' ', $businessName)[0];
            $email = strtolower(str_replace(' ', '', $lastName)) . '@transport.com';

            // Create user account
            $user = User::create([
                'name' => $lastName . ' Owner',
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]);

            // Create operator
            $operator = Operator::create([
                'user_id' => $user->id,
                'business_name' => $businessName,
                'contact_person' => $lastName . ' Owner',
                'phone' => '09' . rand(100000000, 999999999),
                'email' => $email,
                'address' => rand(1, 999) . ' ' . $lastName . ' Street, Quezon City',
                'business_permit_no' => 'BP-' . rand(1000, 9999) . '-' . $currentYear,
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]);

            $operators[] = $operator;

            // Create 2-4 drivers per operator
            $driverCount = rand(2, 4);
            $driverFirstNames = ['Juan', 'Pedro', 'Jose', 'Antonio', 'Miguel', 'Carlos', 'Roberto', 'Luis'];

            for ($d = 0; $d < $driverCount; $d++) {
                $firstName = $driverFirstNames[array_rand($driverFirstNames)];
                $licenseExpiry = Carbon::now()->addYears(rand(1, 5))->format('Y-m-d');

                Driver::create([
                    'operator_id' => $operator->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'license_number' => 'N' . rand(10, 99) . '-' . rand(10, 99) . '-' . rand(100000, 999999),
                    'license_expiry' => $licenseExpiry,
                    'phone' => '09' . rand(100000000, 999999999),
                    'address' => rand(1, 999) . ' Driver St, Manila',
                    'status' => 'active',
                ]);
            }

            // Create 1-3 units per operator
            $unitCount = rand(1, 3);
            $brands = ['Toyota', 'Nissan', 'Mitsubishi', 'Hyundai', 'Isuzu'];
            $models = ['Hi-Ace', 'Urvan', 'L300', 'H-100', 'Commuter'];
            $types = ['van', 'bus'];

            for ($u = 0; $u < $unitCount; $u++) {
                Unit::create([
                    'operator_id' => $operator->id,
                    'plate_no' => 'ABC' . rand(100, 999) . chr(rand(65, 90)),
                    'type' => $types[array_rand($types)],
                    'brand' => $brands[array_rand($brands)],
                    'model' => $models[array_rand($models)],
                    'year' => rand(2015, 2023),
                    'capacity' => rand(10, 50),
                    'status' => 'active',
                ]);
            }

            echo "Created operator: {$businessName} (Email: {$email}, Password: password123)\n";
        }

        echo "\nGenerating transactions for all operators...\n";

        // Get a treasurer/admin user for created_by field
        $admin = User::where('role', 'admin')->first() ??
                 User::where('role', 'treasurer')->first() ??
                 User::first();

        $receiptParticulars = [
            'subscription_capital',
            'management_fee',
            'membership_fee',
            'monthly_dues',
            'business_permit'
        ];

        $transactionCount = 0;

        // Generate transactions for 2023-2025
        for ($year = 2023; $year <= 2025; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);

                // Skip future months
                if ($date->isFuture()) {
                    continue;
                }

                // Generate 10-20 receipts per month, distributed across operators
                $receiptsCount = rand(10, 20);

                for ($i = 0; $i < $receiptsCount; $i++) {
                    // Random operator
                    $operator = $operators[array_rand($operators)];

                    // Random date within the month
                    $randomDay = rand(1, $date->daysInMonth);
                    $transactionDate = Carbon::create($year, $month, $randomDay);

                    // Skip if future date
                    if ($transactionDate->isFuture()) {
                        continue;
                    }

                    $particular = $receiptParticulars[array_rand($receiptParticulars)];

                    // Amount based on particular type
                    $amount = match($particular) {
                        'subscription_capital' => rand(500, 2000),
                        'management_fee' => rand(200, 800),
                        'membership_fee' => rand(100, 500),
                        'monthly_dues' => rand(150, 600),
                        'business_permit' => rand(300, 1000),
                        default => rand(100, 1000)
                    };

                    Transaction::create([
                        'operator_id' => $operator->id,
                        'type' => 'receipt',
                        'category' => null,
                        'date' => $transactionDate->format('Y-m-d'),
                        'particular' => $particular,
                        'month' => $transactionDate->format('F Y'),
                        'or_number' => 'OR-' . $year . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                        'amount' => $amount,
                        'created_by' => $admin->id,
                    ]);

                    $transactionCount++;
                }
            }
        }

        echo "\nSeeding completed successfully!\n";
        echo "Created {$transactionCount} operator receipt transactions\n";
        echo "Total operators: " . count($operators) . "\n";
        echo "Total drivers: " . Driver::count() . "\n";
        echo "Total units: " . Unit::count() . "\n";
        echo "\nOperator Login Credentials:\n";
        echo "Email format: [lastname]@transport.com\n";
        echo "Password: password123\n";
        echo "\nExample logins:\n";
        echo "- rodriguez@transport.com / password123\n";
        echo "- santos@transport.com / password123\n";
        echo "- cruz@transport.com / password123\n";
    }
}
