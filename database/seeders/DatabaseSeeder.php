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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        
        $this->call([
            OfficerUsersSeeder::class,
            SampleDataSeeder::class,
        ]);
        
    }
}