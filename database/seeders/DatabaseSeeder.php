<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta el seeder de Roles y Admin
        $this->call(\Database\Seeders\RolesAndAdminSeeder::class);
    }
}
