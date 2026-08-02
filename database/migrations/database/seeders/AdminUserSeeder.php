<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL', 'owner@aquila.net.au');
        $password = env('ADMIN_SEED_PASSWORD', 'ChangeMe123!');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Aquila Owner',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('Owner')) {
            $user->assignRole('Owner');
        }

        $this->command?->info("Admin user ready: {$email} / {$password} (change this password immediately after first login).");
    }
}
