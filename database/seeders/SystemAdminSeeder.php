<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemAdminSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // SYSTEM ADMIN (SUPPORT)
        // =========================
        $systemAdmin = User::create([
            'name'     => 'System Administrator',
            'email'    => 'distrajoironmorris@gmail.com',
            'password' => Hash::make('temp'),
            'role'     => 'system_admin',
        ]);

        $systemAdminId = User::generateUserId('system_admin');
        $systemAdmin->user_id = $systemAdminId;
        $systemAdmin->password = Hash::make($systemAdminId);
        $systemAdmin->save();
    }
}
