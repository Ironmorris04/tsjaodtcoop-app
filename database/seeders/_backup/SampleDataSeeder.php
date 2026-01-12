<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Operator;
use App\Models\OperatorDetail;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Sample photo and PDF paths from the Document for about directory
     */
    protected $samplePhotoPath = 'Document for about/1036.png';
    protected $samplePdfPath = 'Document for about/TSJAODTC_Membership_Application_Form_Pogi_Kim Patrick.pdf';

    /**
     * Filipino first names by gender
     */
    protected $maleFirstNames = [
        'Juan', 'Pedro', 'Jose', 'Miguel', 'Antonio', 'Rafael', 'Carlos', 'Fernando',
        'Roberto', 'Eduardo', 'Ricardo', 'Mario', 'Ernesto', 'Ramon', 'Alfredo',
        'Gabriel', 'Daniel', 'Manuel', 'Francisco', 'Alejandro', 'Mark', 'John Paul',
        'Michael', 'Christopher', 'Kenneth', 'Ronald', 'Dennis', 'Vincent', 'Jerome', 'Albert'
    ];

    protected $femaleFirstNames = [
        'Maria', 'Ana', 'Rosa', 'Carmen', 'Teresa', 'Gloria', 'Patricia', 'Elena',
        'Victoria', 'Lourdes', 'Mercedes', 'Cristina', 'Isabel', 'Josefina', 'Rosario',
        'Grace', 'Faith', 'Joy', 'Hope', 'Angelica', 'Jennifer', 'Jessica', 'Michelle',
        'Katherine', 'Samantha', 'Nicole', 'Stephanie', 'Vanessa', 'Bianca', 'Angela'
    ];

    protected $middleNames = [
        'Santos', 'Reyes', 'Cruz', 'Garcia', 'Lopez', 'Martinez', 'Rodriguez', 'Hernandez',
        'Gonzales', 'Perez', 'Dela Cruz', 'Bautista', 'Ramos', 'Mendoza', 'Torres'
    ];

    protected $lastNames = [
        'Santos', 'Reyes', 'Cruz', 'Garcia', 'Lopez', 'Martinez', 'Rodriguez', 'Hernandez',
        'Gonzales', 'Perez', 'Dela Cruz', 'Bautista', 'Ramos', 'Mendoza', 'Torres',
        'Villanueva', 'Castro', 'Rivera', 'Fernandez', 'Aquino', 'Soriano', 'Navarro',
        'Morales', 'Estrada', 'Santiago', 'Pascual', 'Aguilar', 'Francisco', 'Tolentino', 'Manalo'
    ];

    protected $barangays = [
        'Barangay San Jose', 'Barangay San Antonio', 'Barangay San Roque', 'Barangay Poblacion',
        'Barangay Bagong Silang', 'Barangay Malaya', 'Barangay Mabuhay', 'Barangay Progreso',
        'Barangay Sampaguita', 'Barangay Rizal'
    ];

    protected $streets = [
        'Main Street', 'Rizal Avenue', 'Quezon Boulevard', 'Bonifacio Street', 'Mabini Street',
        'Luna Street', 'Del Pilar Street', 'Aguinaldo Street', 'Lapu-Lapu Street', 'Burgos Street'
    ];

    protected $religions = ['Roman Catholic', 'Iglesia ni Cristo', 'Islam', 'Born Again Christian', 'Protestant', 'Buddhist'];

    protected $occupations = ['Driver', 'Business Owner', 'Farmer', 'Vendor', 'Self-Employed', 'Employee'];

    protected $idTypes = ['Philippine National ID', 'Driver\'s License', 'Passport', 'Voter\'s ID', 'SSS ID', 'GSIS ID', 'PRC ID'];

    protected $vehicleBrands = ['Toyota', 'Isuzu', 'Mitsubishi', 'Nissan', 'Hino', 'Hyundai'];

    protected $vehicleColors = ['White', 'Silver', 'Black', 'Red', 'Blue', 'Yellow', 'Green', 'Orange'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Copy sample files to storage
        $this->copyFilesToStorage();

        echo "\n========================================\n";
        echo "Creating Sample Data...\n";
        echo "========================================\n";

        // Create 20 operators with full details
        $operators = $this->createOperators(20);
        echo "Created " . count($operators) . " operators with details\n";

        // Create drivers for each operator
        $drivers = $this->createDrivers($operators, 2); // 2 drivers per operator
        echo "Created " . count($drivers) . " drivers\n";

        // Create units for each operator
        $units = $this->createUnits($operators, $drivers, 2); // 2 units per operator
        echo "Created " . count($units) . " units\n";

        // Create financial transactions
        $this->createTransactions($operators);
        echo "Created financial transactions\n";

        echo "\n========================================\n";
        echo "Sample Data Created Successfully!\n";
        echo "========================================\n\n";
    }

    /**
     * Copy sample files to storage directories
     */
    protected function copyFilesToStorage(): void
    {
        $sourcePhoto = base_path($this->samplePhotoPath);
        $sourcePdf = base_path($this->samplePdfPath);

        // Create storage directories if they don't exist
        $directories = [
            storage_path('app/public/profile-photos'),
            storage_path('app/public/valid-ids'),
            storage_path('app/public/driver-photos'),
            storage_path('app/public/license-photos'),
            storage_path('app/public/biodata-photos'),
            storage_path('app/public/unit-photos'),
            storage_path('app/public/cr-photos'),
            storage_path('app/public/or-photos'),
            storage_path('app/public/business-permit-photos'),
            storage_path('app/public/membership-forms'),
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }

        // Copy sample photo to various directories
        if (File::exists($sourcePhoto)) {
            File::copy($sourcePhoto, storage_path('app/public/profile-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/valid-ids/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/driver-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/license-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/biodata-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/unit-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/cr-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/or-photos/sample.png'));
            File::copy($sourcePhoto, storage_path('app/public/business-permit-photos/sample.png'));
        }

        // Copy sample PDF
        if (File::exists($sourcePdf)) {
            File::copy($sourcePdf, storage_path('app/public/membership-forms/sample.pdf'));
        }
    }

    /**
     * Create operators with full details
     */
    protected function createOperators(int $count): array
    {
        $operators = [];

        for ($i = 1; $i <= $count; $i++) {
            $gender = rand(0, 1) ? 'male' : 'female';
            $firstName = $gender === 'male'
                ? $this->maleFirstNames[array_rand($this->maleFirstNames)]
                : $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
            $middleName = $this->middleNames[array_rand($this->middleNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $fullName = "$firstName $middleName $lastName";

            // Create user
            $user = User::create([
                'name' => $fullName,
                'email' => strtolower(str_replace(' ', '', $firstName)) . $i . '@transport.com',
                'password' => Hash::make('temp'),
                'role' => 'operator',
            ]);

            // Generate and set user_id
            $userId = User::generateUserId('operator');
            $user->user_id = $userId;
            $user->password = Hash::make($userId);
            $user->save();

            // Create operator
            $operator = Operator::create([
                'user_id' => $user->id,
                'business_name' => "$lastName Transport Services",
                'contact_person' => $fullName,
                'phone' => '+63 9' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'email' => strtolower(str_replace(' ', '', $firstName)) . $i . '@transport.com',
                'address' => rand(1, 999) . ' ' . $this->streets[array_rand($this->streets)] . ', ' . $this->barangays[array_rand($this->barangays)] . ', San Jose del Monte, Bulacan',
                'business_permit_no' => 'BP-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'approval_status' => 'approved',
                'membership_form_path' => 'membership-forms/sample.pdf',
            ]);

            // Generate birthdate (25-65 years old)
            $age = rand(25, 65);
            $birthdate = Carbon::now()->subYears($age)->subDays(rand(1, 365));

            // Create operator details
            OperatorDetail::create([
                'operator_id' => $operator->id,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'birthdate' => $birthdate->format('Y-m-d'),
                'birthplace' => 'San Jose del Monte, Bulacan',
                'religion' => $this->religions[array_rand($this->religions)],
                'citizenship' => 'Filipino',
                'occupation' => $this->occupations[array_rand($this->occupations)],
                'sex' => $gender,
                'civil_status' => ['single', 'married', 'widowed', 'separated'][rand(0, 3)],
                'indigenous_people' => rand(0, 10) > 8 ? 'yes' : 'no',
                'pwd' => rand(0, 10) > 9 ? 'yes' : 'no',
                'senior_citizen' => $age >= 60 ? 'yes' : 'no',
                'fourps_beneficiary' => rand(0, 10) > 7 ? 'yes' : 'no',
                'id_type' => $this->idTypes[array_rand($this->idTypes)],
                'id_number' => strtoupper(substr(md5(rand()), 0, 12)),
                'valid_id_path' => 'valid-ids/sample.png',
                'profile_photo_path' => 'profile-photos/sample.png',
            ]);

            $operators[] = $operator;
        }

        return $operators;
    }

    /**
     * Create drivers for operators
     */
    protected function createDrivers(array $operators, int $driversPerOperator): array
    {
        $drivers = [];
        $driverCounter = 1;

        foreach ($operators as $operator) {
            for ($i = 0; $i < $driversPerOperator; $i++) {
                $gender = rand(0, 1) ? 'Male' : 'Female';
                $firstName = $gender === 'Male'
                    ? $this->maleFirstNames[array_rand($this->maleFirstNames)]
                    : $this->femaleFirstNames[array_rand($this->femaleFirstNames)];
                $lastName = $this->lastNames[array_rand($this->lastNames)];

                // Generate birthdate (21-55 years old)
                $age = rand(21, 55);
                $birthdate = Carbon::now()->subYears($age)->subDays(rand(1, 365));

                // Generate license expiry (1-5 years from now)
                $licenseExpiry = Carbon::now()->addYears(rand(1, 5))->addDays(rand(1, 365));

                $driver = Driver::create([
                    'operator_id' => $operator->id,
                    'driver_id' => 'DRV-' . str_pad($driverCounter, 4, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'license_number' => 'N' . rand(10, 99) . '-' . rand(10, 99) . '-' . rand(100000, 999999),
                    'license_expiry' => $licenseExpiry->format('Y-m-d'),
                    'phone' => '+63 9' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                    'address' => rand(1, 999) . ' ' . $this->streets[array_rand($this->streets)] . ', ' . $this->barangays[array_rand($this->barangays)] . ', San Jose del Monte, Bulacan',
                    'status' => 'active',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'photo' => 'driver-photos/sample.png',
                    'biodata_photo' => 'biodata-photos/sample.png',
                    'birthdate' => $birthdate->format('Y-m-d'),
                    'sex' => $gender,
                    'license_photo' => 'license-photos/sample.png',
                    'license_restrictions' => rand(0, 1) ? 'None' : 'Restriction 1',
                    'dl_codes' => 'A, B, ' . ['C', 'D', 'E'][rand(0, 2)],
                ]);

                $drivers[] = $driver;
                $driverCounter++;
            }
        }

        return $drivers;
    }

    /**
     * Create units for operators and assign drivers
     */
    protected function createUnits(array $operators, array $drivers, int $unitsPerOperator): array
    {
        $units = [];
        $unitCounter = 1;
        $driverIndex = 0;

        foreach ($operators as $index => $operator) {
            for ($i = 0; $i < $unitsPerOperator; $i++) {
                $brand = $this->vehicleBrands[array_rand($this->vehicleBrands)];
                $yearModel = rand(2015, 2024);

                // Assign driver to this unit (if available)
                $driverId = null;
                if (isset($drivers[$driverIndex])) {
                    $driverId = $drivers[$driverIndex]->id;
                    $driverIndex++;
                }

                // Generate plate number (Format: ABC 1234)
                $plateNo = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . ' ' . rand(1000, 9999);

                // Registration expiry (1-3 years from now)
                $registrationExpiry = Carbon::now()->addYears(rand(1, 3))->addDays(rand(1, 365));

                $unit = Unit::create([
                    'operator_id' => $operator->id,
                    'unit_id' => 'UNT-' . str_pad($unitCounter, 4, '0', STR_PAD_LEFT),
                    'driver_id' => $driverId,
                    'plate_no' => $plateNo,
                    'body_number' => 'BN-' . rand(1000, 9999),
                    'engine_number' => strtoupper(substr(md5(rand()), 0, 10)),
                    'chassis_number' => strtoupper(substr(md5(rand()), 0, 17)),
                    'color' => $this->vehicleColors[array_rand($this->vehicleColors)],
                    'type' => ['Bus', 'Jeepney', 'Van', 'Taxi'][rand(0, 3)],
                    'brand' => $brand,
                    'model' => $this->getModelForBrand($brand),
                    'year_model' => (string)$yearModel,
                    'capacity' => rand(10, 50),
                    'status' => 'active',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'lto_cr_number' => 'CR-' . rand(10000000, 99999999),
                    'lto_cr_date_issued' => Carbon::now()->subYears(rand(1, 3))->format('Y-m-d'),
                    'lto_or_number' => 'OR-' . rand(10000000, 99999999),
                    'lto_or_date_issued' => Carbon::now()->subMonths(rand(1, 11))->format('Y-m-d'),
                    'franchise_case' => 'FC-' . date('Y') . '-' . rand(1000, 9999),
                    'mv_file' => 'MV-' . rand(100000, 999999),
                    'mbp_no_prev_year' => 'MBP-' . (date('Y') - 1) . '-' . rand(100, 999),
                    'mch_no_prev_year' => 'MCH-' . (date('Y') - 1) . '-' . rand(100, 999),
                    'registration_expiry' => $registrationExpiry->format('Y-m-d'),
                    'unit_photo' => 'unit-photos/sample.png',
                    'business_permit_photo' => 'business-permit-photos/sample.png',
                    'or_photo' => 'or-photos/sample.png',
                    'cr_photo' => 'cr-photos/sample.png',
                ]);

                $units[] = $unit;
                $unitCounter++;
            }
        }

        return $units;
    }

    /**
     * Get vehicle model based on brand
     */
    protected function getModelForBrand(string $brand): string
    {
        $models = [
            'Toyota' => ['Hiace', 'Coaster', 'Land Cruiser', 'Fortuner'],
            'Isuzu' => ['Elf', 'Forward', 'Giga', 'D-Max'],
            'Mitsubishi' => ['L300', 'Canter', 'Rosa', 'Fuso'],
            'Nissan' => ['NV350', 'Urvan', 'Terra', 'Navara'],
            'Hino' => ['Dutro', 'Ranger', 'Profia', 'RK8'],
            'Hyundai' => ['H100', 'County', 'Universe', 'Starex'],
        ];

        $brandModels = $models[$brand] ?? ['Standard'];
        return $brandModels[array_rand($brandModels)];
    }

    /**
     * Create financial transactions for operators
     */
    protected function createTransactions(array $operators): void
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June',
                   'July', 'August', 'September', 'October', 'November'];

        $orCounter = 1;
        $voucherCounter = 1;

        foreach ($operators as $operator) {
            // Create Cash Receipt Journal entries (receipts from operators)
            foreach ($months as $monthIndex => $month) {
                // Subscription Capital (CBU)
                Transaction::create([
                    'operator_id' => $operator->id,
                    'type' => 'receipt',
                    'category' => 'membership_fee',
                    'date' => Carbon::create(2024, $monthIndex + 1, rand(1, 28))->format('Y-m-d'),
                    'particular' => 'subscription_capital',
                    'month' => $month . ' 2024',
                    'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => 500.00,
                ]);

                // Management Fee
                Transaction::create([
                    'operator_id' => $operator->id,
                    'type' => 'receipt',
                    'category' => 'management_fee',
                    'date' => Carbon::create(2024, $monthIndex + 1, rand(1, 28))->format('Y-m-d'),
                    'particular' => 'management_fee',
                    'month' => $month . ' 2024',
                    'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => 500.00,
                ]);

                // Monthly Dues
                Transaction::create([
                    'operator_id' => $operator->id,
                    'type' => 'receipt',
                    'category' => 'monthly_due',
                    'date' => Carbon::create(2024, $monthIndex + 1, rand(1, 28))->format('Y-m-d'),
                    'particular' => 'monthly_dues',
                    'month' => $month . ' 2024',
                    'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => 150.00,
                ]);
            }

            // Membership Fee (once)
            Transaction::create([
                'operator_id' => $operator->id,
                'type' => 'receipt',
                'category' => 'membership_fee',
                'date' => Carbon::create(2024, 1, rand(1, 15))->format('Y-m-d'),
                'particular' => 'membership_fee',
                'month' => 'January 2024',
                'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                'amount' => 500.00,
            ]);

            // Business Permit (once per year)
            Transaction::create([
                'operator_id' => $operator->id,
                'type' => 'receipt',
                'category' => 'other',
                'date' => Carbon::create(2024, 1, rand(1, 31))->format('Y-m-d'),
                'particular' => 'business_permit',
                'month' => 'January 2024',
                'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                'amount' => rand(500, 2000) * 1.00,
            ]);
        }

        // Create Cash Disbursement Book entries (expenses)
        $disbursementParticulars = [
            'Office Supplies' => [500, 2000],
            'Utilities (Electricity)' => [3000, 8000],
            'Utilities (Water)' => [500, 1500],
            'Internet and Communications' => [1500, 3000],
            'Office Rental' => [5000, 15000],
            'Staff Salaries' => [15000, 50000],
            'Fuel Allowance' => [2000, 5000],
            'Vehicle Maintenance' => [3000, 10000],
            'Miscellaneous Expenses' => [500, 3000],
            'Professional Fees' => [2000, 10000],
            'Insurance Premium' => [5000, 20000],
            'Training and Seminars' => [1000, 5000],
        ];

        foreach ($months as $monthIndex => $month) {
            // Select 5-8 random disbursements per month
            $shuffledParticulars = array_keys($disbursementParticulars);
            shuffle($shuffledParticulars);
            $selectedParticulars = array_slice($shuffledParticulars, 0, rand(5, 8));

            foreach ($selectedParticulars as $particular) {
                $range = $disbursementParticulars[$particular];

                Transaction::create([
                    'operator_id' => null, // Cooperative expenses, not operator-specific
                    'type' => 'disbursement',
                    'category' => 'expense',
                    'date' => Carbon::create(2024, $monthIndex + 1, rand(1, 28))->format('Y-m-d'),
                    'particular' => $particular,
                    'month' => $month . ' 2024',
                    'or_number' => 'CV-2024-' . str_pad($voucherCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => rand($range[0], $range[1]) * 1.00,
                ]);
            }
        }

        // Create some Miscellaneous Income entries
        $miscIncomes = [
            'Rental Income from Office Space' => [5000, 15000],
            'Interest from Bank Deposits' => [500, 2000],
            'Fines and Penalties Collected' => [100, 500],
            'Service Fees' => [1000, 3000],
            'Other Income' => [500, 5000],
        ];

        foreach ($months as $monthIndex => $month) {
            // Select 1-2 random misc incomes per month
            $shuffledMisc = array_keys($miscIncomes);
            shuffle($shuffledMisc);
            $selectedMisc = array_slice($shuffledMisc, 0, rand(1, 2));

            foreach ($selectedMisc as $particular) {
                $range = $miscIncomes[$particular];

                Transaction::create([
                    'operator_id' => null,
                    'type' => 'receipt',
                    'category' => 'other',
                    'date' => Carbon::create(2024, $monthIndex + 1, rand(1, 28))->format('Y-m-d'),
                    'particular' => $particular,
                    'month' => $month . ' 2024',
                    'or_number' => 'OR-2024-' . str_pad($orCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => rand($range[0], $range[1]) * 1.00,
                ]);
            }
        }

        echo "  - Cash Receipt Journal entries: " . Transaction::where('type', 'receipt')->count() . "\n";
        echo "  - Cash Disbursement Book entries: " . Transaction::where('type', 'disbursement')->count() . "\n";
    }
}
