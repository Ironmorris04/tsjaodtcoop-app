<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check current operator count
        $currentCount = Operator::count();
        $targetCount = 50;
        $operatorsToCreate = $targetCount - $currentCount;

        if ($operatorsToCreate <= 0) {
            $this->command->info("Database already has {$currentCount} operators. No need to add more.");
            return;
        }

        $this->command->info("Creating {$operatorsToCreate} new operators...");

        // Filipino last names
        $lastNames = [
            'Aquino', 'Bautista', 'Castillo', 'Dela Cruz', 'Enriquez', 'Fernandez',
            'Gonzales', 'Hernandez', 'Ignacio', 'Jimenez', 'Kumar', 'Lim',
            'Mercado', 'Navarro', 'Ocampo', 'Perez', 'Quinto', 'Rivera',
            'Santiago', 'Tan', 'Uy', 'Villanueva', 'Wong', 'Yap', 'Zamora',
            'Aguilar', 'Bello', 'Cruz', 'Diaz', 'Espinosa', 'Fuentes',
            'Garcia', 'Herrera', 'Ibarra', 'Jacinto', 'Lacson'
        ];

        // Transport business types
        $businessTypes = [
            'Transport Services', 'Van Rental', 'Transport Co.', 'Shuttle Service',
            'Van Service', 'Transport', 'Shuttle Co.', 'Express Transport',
            'Transit Services', 'Commuter Van', 'People Mover', 'Passenger Transport'
        ];

        // Starting from current count
        $startIndex = $currentCount;

        for ($i = 0; $i < $operatorsToCreate; $i++) {
            $index = $startIndex + $i;
            $lastName = $lastNames[$i % count($lastNames)];
            $businessType = $businessTypes[$i % count($businessTypes)];

            // Generate registration year (mix of 2024 and 2025)
            $regYear = $i < $operatorsToCreate / 2 ? 2024 : 2025;
            $regMonth = rand(1, 12);
            $regDay = rand(1, 28);
            $registrationDate = Carbon::create($regYear, $regMonth, $regDay);

            // Create unique email based on operator number
            $operatorNumber = $index + 1;
            $email = strtolower($lastName) . $operatorNumber . '@transport.com';

            // Create user account
            $user = User::create([
                'user_id' => sprintf('O%03d-%d', $operatorNumber, $regYear),
                'name' => "{$lastName} Owner",
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]);

            // Create operator profile
            $operator = Operator::create([
                'user_id' => $user->id,
                'business_name' => "{$lastName} {$businessType}",
                'contact_person' => "{$lastName} Owner",
                'phone' => $this->generatePhoneNumber(),
                'email' => $email,
                'address' => $this->generateAddress(),
                'business_permit_no' => $this->generateBusinessPermitNo(),
                'status' => 'active',
            ]);

            // Approve the operator (using DB query to bypass fillable)
            $operator->update([
                'approval_status' => 'approved',
                'approved_at' => $registrationDate,
            ]);

            // Create random number of drivers (1-3 per operator)
            $driverCount = rand(1, 3);
            for ($d = 0; $d < $driverCount; $d++) {
                $driverLastName = $lastNames[rand(0, count($lastNames) - 1)];
                $driverFirstName = $this->getRandomFirstName();
                $driverNumber = ($index + 1) * 10 + $d;

                Driver::create([
                    'operator_id' => $operator->id,
                    'first_name' => $driverFirstName,
                    'last_name' => $driverLastName,
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 55))->subDays(rand(1, 365)),
                    'license_number' => $this->generateLicenseNumber(),
                    'license_type' => rand(0, 1) ? 'Professional' : 'Non-Professional',
                    'phone' => $this->generatePhoneNumber(),
                    'email' => strtolower($driverFirstName . $driverLastName . $driverNumber) . '@driver.com',
                    'address' => $this->generateAddress(),
                    'hire_date' => $registrationDate->copy()->addDays(rand(0, 365)),
                    'emergency_contact' => $this->generatePhoneNumber(),
                    'license_expiry' => Carbon::now()->addYears(rand(1, 3)),
                    'status' => rand(0, 10) > 1 ? 'active' : 'inactive', // 90% active
                ]);
            }

            // Create random number of units (1-2 per operator)
            $unitCount = rand(1, 2);
            for ($u = 0; $u < $unitCount; $u++) {
                $brands = ['Toyota', 'Nissan', 'Mitsubishi', 'Hyundai', 'Isuzu', 'Honda', 'Ford'];
                $models = ['Hiace', 'Urvan', 'L300', 'Starex', 'Commuter', 'NV350', 'Transit'];
                $types = ['van', 'bus', 'jeepney'];

                Unit::create([
                    'operator_id' => $operator->id,
                    'plate_no' => $this->generatePlateNumber(),
                    'type' => $types[array_rand($types)],
                    'brand' => $brands[array_rand($brands)],
                    'model' => $models[array_rand($models)],
                    'year' => rand(2015, 2024),
                    'capacity' => rand(12, 18),
                    'status' => rand(0, 10) > 1 ? 'active' : (rand(0, 1) ? 'inactive' : 'maintenance'), // 90% active
                ]);
            }

            $this->command->info("Created operator: {$operator->business_name} (User ID: {$user->user_id})");
        }

        $this->command->info("\n✓ Successfully created {$operatorsToCreate} operators!");
        $this->command->info("Total operators in database: " . Operator::count());
        $this->command->info("Total drivers: " . Driver::count());
        $this->command->info("Total units: " . Unit::count());
    }

    private function generateAddress(): string
    {
        $streets = ['Bonifacio St.', 'Rizal Ave.', 'Mabini St.', 'Luna St.', 'Del Pilar St.', 'Quezon Blvd.'];
        $barangays = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Poblacion', 'Centro'];
        $cities = ['Manila', 'Quezon City', 'Makati', 'Pasig', 'Taguig', 'Mandaluyong', 'Caloocan'];

        $street = $streets[array_rand($streets)];
        $barangay = $barangays[array_rand($barangays)];
        $city = $cities[array_rand($cities)];

        return rand(1, 999) . " {$street}, {$barangay}, {$city}";
    }

    private function generatePhoneNumber(): string
    {
        $prefixes = ['0917', '0918', '0919', '0920', '0921', '0922', '0923', '0924', '0925', '0926'];
        return $prefixes[array_rand($prefixes)] . rand(1000000, 9999999);
    }

    private function generateBusinessPermitNo(): string
    {
        return 'BP-' . date('Y') . '-' . sprintf('%05d', rand(10000, 99999));
    }

    private function generateLicenseNumber(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $letters[rand(0, 25)] . sprintf('%02d', rand(10, 99)) . '-' . sprintf('%02d', rand(10, 99)) . '-' . sprintf('%06d', rand(100000, 999999));
    }

    private function generatePlateNumber(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $letters[rand(0, 25)] . $letters[rand(0, 25)] . $letters[rand(0, 25)] . ' ' . rand(1000, 9999);
    }

    private function getRandomFirstName(): string
    {
        $names = [
            'Juan', 'Jose', 'Antonio', 'Pedro', 'Manuel', 'Luis', 'Carlos', 'Ramon',
            'Maria', 'Ana', 'Rosa', 'Teresa', 'Carmen', 'Elena', 'Sofia', 'Isabel',
            'Miguel', 'Rafael', 'Ricardo', 'Fernando', 'Roberto', 'Eduardo'
        ];
        return $names[array_rand($names)];
    }
}
