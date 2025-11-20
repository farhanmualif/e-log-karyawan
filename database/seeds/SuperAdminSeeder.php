<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cek apakah superadmin sudah ada
        $superadmin = User::where('username', 'superadmin')->first();

        if (!$superadmin) {
            User::create([
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('superduperadmin789'),
                'password_changed' => false,
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]);

            $this->command->info('Super Admin berhasil dibuat!');
            $this->command->info('Username: superadmin');
            $this->command->info('Password: superduperadmin789');
        } else {
            $this->command->warn('Super Admin sudah ada di database.');
        }
    }
}
