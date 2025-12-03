<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        require_once __DIR__ . '/SuperAdminSeeder.php';
        $seeder = new SuperAdminSeeder();
        $seeder->setCommand($this->command);
        $seeder->run();

        require_once __DIR__ . '/DepartemenSeeder.php';
        $departemenSeeder = new DepartemenSeeder();
        $departemenSeeder->setCommand($this->command);
        $departemenSeeder->run();

        require_once __DIR__ . '/AdminSeeder.php';
        $adminSeeder = new AdminSeeder();
        $adminSeeder->setCommand($this->command);
        $adminSeeder->run();

        // require_once __DIR__ . '/LogAktivitasSeeder.php';
        // $logSeeder = new LogAktivitasSeeder();
        // $logSeeder->setCommand($this->command);
        // $logSeeder->run();
    }
}
