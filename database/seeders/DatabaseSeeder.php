<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Lvl 1: Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_level' => 1,
        ]);

        // Lvl 2: Production (Modeling)
        User::factory()->create([
            'name' => 'Modeling Specialist',
            'username' => 'modeling1',
            'email' => 'modeling1@example.com',
            'password' => Hash::make('password'),
            'role_level' => 2,
            'role_specialty' => 'Modeling',
        ]);

        // Lvl 3: Reviewer
        User::factory()->create([
            'name' => 'QC Reviewer',
            'username' => 'reviewer1',
            'email' => 'reviewer1@example.com',
            'password' => Hash::make('password'),
            'role_level' => 3,
        ]);

        $this->call([
            ProductionSeeder::class,
        ]);
    }
}
