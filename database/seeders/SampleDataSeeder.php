<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Operator;
use App\Models\OperatorDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Sample photo and PDF paths
     */
    protected $samplePhotoPath = 'Document for about/1036.png';
    protected $samplePdfPath = 'Document for about/TSJAODTC_Membership_Application_Form_Pogi_Kim Patrick.pdf';

    protected $maleFirstNames = [
        'Juan','Pedro','Jose','Miguel','Antonio','Rafael','Carlos','Fernando',
        'Roberto','Eduardo','Ricardo','Mario','Ernesto','Ramon','Alfredo'
    ];

    protected $femaleFirstNames = [
        'Maria','Ana','Rosa','Carmen','Teresa','Gloria','Patricia','Elena',
        'Victoria','Lourdes','Cristina','Isabel','Grace','Joy','Angela'
    ];

    protected $middleNames = [
        'Santos','Reyes','Cruz','Garcia','Lopez','Martinez','Rodriguez',
        'Gonzales','Perez','Dela Cruz','Bautista','Ramos','Mendoza'
    ];

    protected $lastNames = [
        'Villanueva','Castro','Rivera','Fernandez','Aquino','Soriano',
        'Navarro','Morales','Estrada','Santiago','Pascual','Aguilar'
    ];

    protected $barangays = [
        'Barangay San Jose','Barangay Poblacion','Barangay Rizal',
        'Barangay Mabuhay','Barangay Malaya'
    ];

    protected $streets = [
        'Rizal Avenue','Quezon Boulevard','Bonifacio Street',
        'Mabini Street','Del Pilar Street'
    ];

    protected $religions = ['Roman Catholic','Born Again Christian','Iglesia ni Cristo'];
    protected $occupations = ['Business Owner','Transport Operator','Driver'];
    protected $idTypes = ['Driver\'s License','Philippine National ID','Passport'];

    public function run(): void
    {
        $this->copyFilesToStorage();

        echo "\n==============================\n";
        echo "Seeding 10 UNIQUE OPERATORS\n";
        echo "==============================\n";

        $operators = $this->createOperators(10);

        echo "✔ Created " . count($operators) . " operators\n";
        echo "✔ Seeder finished successfully\n\n";
    }

    protected function copyFilesToStorage(): void
    {
        $dirs = [
            'profile-photos','valid-ids','membership-forms'
        ];

        foreach ($dirs as $dir) {
            $path = storage_path("app/public/$dir");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
        }

        if (File::exists(base_path($this->samplePhotoPath))) {
            File::copy(
                base_path($this->samplePhotoPath),
                storage_path('app/public/profile-photos/sample.png')
            );
            File::copy(
                base_path($this->samplePhotoPath),
                storage_path('app/public/valid-ids/sample.png')
            );
        }

        if (File::exists(base_path($this->samplePdfPath))) {
            File::copy(
                base_path($this->samplePdfPath),
                storage_path('app/public/membership-forms/sample.pdf')
            );
        }
    }

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

            $email = strtolower($firstName . $lastName) . "_op$i@transport.test";

            // USER
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('temp'),
                'role' => 'operator',
            ]);

            $user->user_id = User::generateUserId('operator');
            $user->password = Hash::make($user->user_id);
            $user->save();

            // OPERATOR
            $operator = Operator::create([
                'user_id' => $user->id,
                'business_name' => "$lastName Transport Services $i",
                'contact_person' => $fullName,
                'phone' => '+63917' . rand(1000000, 9999999),
                'email' => $email,
                'address' => rand(1, 999) . ' ' .
                    $this->streets[array_rand($this->streets)] . ', ' .
                    $this->barangays[array_rand($this->barangays)] . ', Leyte',
                'business_permit_no' => 'BP-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => 'active',
                'approval_status' => 'approved',
                'membership_form_path' => 'membership-forms/sample.pdf',
            ]);

            // DETAILS
            $age = rand(28, 65);
            $birthdate = Carbon::now()->subYears($age);

            OperatorDetail::create([
                'operator_id' => $operator->id,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'birthdate' => $birthdate->format('Y-m-d'),
                'birthplace' => 'Leyte',
                'religion' => $this->religions[array_rand($this->religions)],
                'citizenship' => 'Filipino',
                'occupation' => $this->occupations[array_rand($this->occupations)],
                'sex' => $gender,
                'civil_status' => ['single','married'][rand(0,1)],
                'indigenous_people' => 'no',
                'pwd' => 'no',
                'senior_citizen' => $age >= 60 ? 'yes' : 'no',
                'fourps_beneficiary' => 'no',
                'id_type' => $this->idTypes[array_rand($this->idTypes)],
                'id_number' => strtoupper(substr(md5(rand()), 0, 12)),
                'valid_id_path' => 'valid-ids/sample.png',
                'profile_photo_path' => 'profile-photos/sample.png',
            ]);

            $operators[] = $operator;
        }

        return $operators;
    }
}
