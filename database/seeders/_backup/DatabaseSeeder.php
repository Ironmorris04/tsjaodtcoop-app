<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Unit;
use App\Models\Officer;
use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\GeneralInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User with generated User ID
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@transport.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $adminUserId = User::generateUserId('admin');
        $admin->user_id = $adminUserId;
        $admin->password = Hash::make($adminUserId); // Password is the User ID
        $admin->save();

        // Create Operator 1 User with generated User ID
        $operator1User = User::create([
            'name' => 'John Operator',
            'email' => 'operator1@transport.com',
            'password' => Hash::make('temp'),
            'role' => 'operator',
        ]);
        $operator1UserId = User::generateUserId('operator');
        $operator1User->user_id = $operator1UserId;
        $operator1User->password = Hash::make($operator1UserId); // Password is the User ID
        $operator1User->save();

        // Create Operator 2 User with generated User ID
        $operator2User = User::create([
            'name' => 'Jane Operator',
            'email' => 'operator2@transport.com',
            'password' => Hash::make('temp'),
            'role' => 'operator',
        ]);
        $operator2UserId = User::generateUserId('operator');
        $operator2User->user_id = $operator2UserId;
        $operator2User->password = Hash::make($operator2UserId); // Password is the User ID
        $operator2User->save();

        // Create President User with generated User ID
        $presidentUser = User::create([
            'name' => 'President Officer',
            'email' => 'president@transport.com',
            'password' => Hash::make('temp'),
            'role' => 'president',
        ]);
        $presidentUserId = User::generateUserId('president');
        $presidentUser->user_id = $presidentUserId;
        $presidentUser->password = Hash::make($presidentUserId); // Password is the User ID
        $presidentUser->save();

        // Create Treasurer User with generated User ID
        $treasurerUser = User::create([
            'name' => 'Treasurer Officer',
            'email' => 'treasurer@transport.com',
            'password' => Hash::make('temp'),
            'role' => 'treasurer',
        ]);
        $treasurerUserId = User::generateUserId('treasurer');
        $treasurerUser->user_id = $treasurerUserId;
        $treasurerUser->password = Hash::make($treasurerUserId); // Password is the User ID
        $treasurerUser->save();

        // Create Operator Profiles
        $operator1 = Operator::create([
            'user_id' => $operator1User->id,
            'business_name' => 'Metro Transport Services',
            'contact_person' => 'John Operator',
            'phone' => '+63 912 345 6789',
            'email' => 'metro@transport.com',
            'address' => '123 Main Street, Quezon City',
            'business_permit_no' => 'BP-2024-001',
            'status' => 'active',
        ]);

        $operator2 = Operator::create([
            'user_id' => $operator2User->id,
            'business_name' => 'City Line Transport Co.',
            'contact_person' => 'Jane Operator',
            'phone' => '+63 923 456 7890',
            'email' => 'cityline@transport.com',
            'address' => '456 Transit Ave, Manila',
            'business_permit_no' => 'BP-2024-002',
            'status' => 'active',
        ]);

        // Create Drivers for Operator 1
        Driver::create([
            'operator_id' => $operator1->id,
            'first_name' => 'Pedro',
            'last_name' => 'Santos',
            'license_number' => 'N01-12-345678',
            'license_expiry' => now()->addYears(2),
            'phone' => '+63 918 111 2222',
            'address' => '789 Driver St, Quezon City',
            'status' => 'active',
        ]);

        Driver::create([
            'operator_id' => $operator1->id,
            'first_name' => 'Maria',
            'last_name' => 'Cruz',
            'license_number' => 'N01-12-987654',
            'license_expiry' => now()->addYears(3),
            'phone' => '+63 919 222 3333',
            'address' => '321 Road Ave, Quezon City',
            'status' => 'active',
        ]);

        // Create Units for Operator 1
        Unit::create([
            'operator_id' => $operator1->id,
            'plate_no' => 'ABC 1234',
            'type' => 'Bus',
            'brand' => 'Hino',
            'model' => 'RK8',
            'year_model' => '2022',
            'capacity' => 54,
            'status' => 'active',
        ]);

        Unit::create([
            'operator_id' => $operator1->id,
            'plate_no' => 'XYZ 5678',
            'type' => 'Jeepney',
            'brand' => 'Isuzu',
            'model' => 'Elf',
            'year_model' => '2021',
            'capacity' => 16,
            'status' => 'active',
        ]);

        // Create Drivers for Operator 2
        Driver::create([
            'operator_id' => $operator2->id,
            'first_name' => 'Juan',
            'last_name' => 'Reyes',
            'license_number' => 'N01-13-111111',
            'license_expiry' => now()->addYears(2),
            'phone' => '+63 920 444 5555',
            'address' => '555 Transit Rd, Manila',
            'status' => 'active',
        ]);

        // Create Units for Operator 2
        Unit::create([
            'operator_id' => $operator2->id,
            'plate_no' => 'DEF 9012',
            'type' => 'Van',
            'brand' => 'Toyota',
            'model' => 'Hiace',
            'year_model' => '2023',
            'capacity' => 14,
            'status' => 'active',
        ]);

        // Create Officers
        Officer::create([
            'operator_id' => $operator1->id,
            'position' => 'president',
            'effective_from' => now()->startOfYear(),
            'effective_to' => now()->endOfYear(),
            'is_active' => true,
            'committee' => 'Executive',
        ]);

        Officer::create([
            'operator_id' => $operator1->id,
            'position' => 'treasurer',
            'effective_from' => now()->startOfYear(),
            'effective_to' => now()->endOfYear(),
            'is_active' => true,
            'committee' => 'Finance',
        ]);

        Officer::create([
            'operator_id' => $operator1->id,
            'position' => 'secretary',
            'effective_from' => now()->startOfYear(),
            'effective_to' => now()->endOfYear(),
            'is_active' => true,
            'committee' => 'Administrative',
        ]);

        // Create Meetings using DB to avoid model casts
        $meeting1Id = DB::table('meetings')->insertGetId([
            'title' => 'Annual General Assembly 2025',
            'type' => 'general_assembly',
            'meeting_date' => now()->addDays(30)->format('Y-m-d'),
            'meeting_time' => '14:00:00',
            'location' => 'Cooperative Main Office',
            'address' => 'Quezon City',
            'description' => 'Annual general assembly to discuss cooperative performance and future plans.',
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $meeting1 = Meeting::find($meeting1Id);

        $meeting2Id = DB::table('meetings')->insertGetId([
            'title' => 'Monthly Board Meeting - January',
            'type' => 'board_of_directors',
            'meeting_date' => now()->addDays(7)->format('Y-m-d'),
            'meeting_time' => '10:00:00',
            'location' => 'Conference Room',
            'address' => 'Main Office',
            'description' => 'Regular monthly board meeting to review operations.',
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $meeting2 = Meeting::find($meeting2Id);

        // Create Meeting Attendances
        MeetingAttendance::create([
            'meeting_id' => $meeting1->id,
            'operator_id' => $operator1->id,
            'status' => 'absent',
        ]);

        MeetingAttendance::create([
            'meeting_id' => $meeting1->id,
            'operator_id' => $operator2->id,
            'status' => 'absent',
        ]);

        // Create Transactions
        Transaction::create([
            'operator_id' => $operator1->id,
            'type' => 'receipt',
            'category' => 'membership_fee',
            'date' => now()->subDays(5)->format('Y-m-d'),
            'particular' => 'Annual Membership Fee Payment',
            'month' => now()->subDays(5)->format('F Y'),
            'or_number' => 'OR-2025-001',
            'amount' => 5000.00,
        ]);

        Transaction::create([
            'operator_id' => $operator2->id,
            'type' => 'receipt',
            'category' => 'monthly_due',
            'date' => now()->subDays(2)->format('Y-m-d'),
            'particular' => 'Monthly Dues Payment',
            'month' => now()->subDays(2)->format('F Y'),
            'or_number' => 'OR-2025-002',
            'amount' => 1500.00,
        ]);

        Transaction::create([
            'operator_id' => null,
            'type' => 'receipt',
            'category' => 'other',
            'date' => now()->subDays(10)->format('Y-m-d'),
            'particular' => 'Cooperative Income From Rental',
            'month' => now()->subDays(10)->format('F Y'),
            'or_number' => 'OR-2025-003',
            'amount' => 10000.00,
        ]);

        // Create Settings
        Setting::create([
            'key' => 'membership_fee',
            'value' => '5000',
            'type' => 'number',
            'description' => 'Annual Membership Fee Amount',
        ]);

        Setting::create([
            'key' => 'monthly_due',
            'value' => '1500',
            'type' => 'number',
            'description' => 'Monthly Dues Amount',
        ]);

        Setting::create([
            'key' => 'penalty_rate',
            'value' => '50',
            'type' => 'number',
            'description' => 'Daily Penalty Rate For Late Payments',
        ]);

        // Create General Info
        GeneralInfo::create([
            'registration_no' => 'COOP-2024-001',
            'cooperative_name' => 'Transport Service Cooperative',
            'reg_region' => 'NCR',
            'reg_province' => 'Metro Manila',
            'reg_municipality_city' => 'Quezon City',
            'reg_barangay' => 'Barangay 1',
            'reg_street' => 'Main Street',
            'reg_house_lot_blk_no' => '123',
            'present_region' => 'NCR',
            'present_province' => 'Metro Manila',
            'present_municipality_city' => 'Quezon City',
            'present_barangay' => 'Barangay 1',
            'present_street' => 'Main Street',
            'present_house_lot_blk_no' => '123',
            'date_registration_under_ra9520' => '2024-01-15',
            'category_of_cooperative' => 'Transport',
            'type_of_cooperative' => 'Primary',
            'asset_size' => 'Small',
        ]);

        // Display login credentials
        echo "\n========================================\n";
        echo "Sample Login Credentials:\n";
        echo "========================================\n";
        echo "Admin:\n";
        echo "  Email: admin@transport.com\n";
        echo "  User ID: {$adminUserId}\n";
        echo "  Password: {$adminUserId}\n";
        echo "----------------------------------------\n";
        echo "President:\n";
        echo "  Email: president@transport.com\n";
        echo "  User ID: {$presidentUserId}\n";
        echo "  Password: {$presidentUserId}\n";
        echo "----------------------------------------\n";
        echo "Treasurer:\n";
        echo "  Email: treasurer@transport.com\n";
        echo "  User ID: {$treasurerUserId}\n";
        echo "  Password: {$treasurerUserId}\n";
        echo "----------------------------------------\n";
        echo "Operator 1:\n";
        echo "  Email: operator1@transport.com\n";
        echo "  User ID: {$operator1UserId}\n";
        echo "  Password: {$operator1UserId}\n";
        echo "----------------------------------------\n";
        echo "Operator 2:\n";
        echo "  Email: operator2@transport.com\n";
        echo "  User ID: {$operator2UserId}\n";
        echo "  Password: {$operator2UserId}\n";
        echo "========================================\n\n";
    }
}