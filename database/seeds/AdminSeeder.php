<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'adminelog54321',
                'role' => 'admin',
            ],
            [
                'name' => 'SDM',
                'username' => 'sdm',
                'email' => 'sdm@example.com',
                'password' => 'sdm54321',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            // Cek apakah user sudah ada
            $user = User::where('username', $userData['username'])->first();

            if (!$user) {
                User::create([
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'password_changed' => false,
                    'role' => $userData['role'],
                    'email_verified_at' => now(),
                ]);

                if ($this->command) {
                    $this->command->info("User {$userData['name']} berhasil dibuat!");
                    $this->command->info("Username: {$userData['username']}");
                    $this->command->info("Password: {$userData['password']}");
                }
            } else {
                if ($this->command) {
                    $this->command->warn("User {$userData['name']} (username: {$userData['username']}) sudah ada di database.");
                }
            }
        }
    }
}
