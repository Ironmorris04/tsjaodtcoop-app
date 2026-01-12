<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OfficerUsersSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // PRESIDENT
        // =========================
        $presidentUser = User::create([
            'name'     => 'President Officer',
            'email'    => 'president@transport.com',
            'password' => Hash::make('temp'),
            'role'     => 'president',
        ]);

        $presidentUserId = User::generateUserId('president');
        $presidentUser->user_id = $presidentUserId;
        $presidentUser->password = Hash::make($presidentUserId);
        $presidentUser->save();

        // =========================
        // TREASURER
        // =========================
        $treasurerUser = User::create([
            'name'     => 'Treasurer Officer',
            'email'    => 'treasurer@transport.com',
            'password' => Hash::make('temp'),
            'role'     => 'treasurer',
        ]);

        $treasurerUserId = User::generateUserId('treasurer');
        $treasurerUser->user_id = $treasurerUserId;
        $treasurerUser->password = Hash::make($treasurerUserId);
        $treasurerUser->save();
    }
}
