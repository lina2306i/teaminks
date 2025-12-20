<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('pwd1234'),
        ]);

        User::factory()->create([
            'name' => 'Leader Test',
            'email' => 'leader@test.com',
            'password' => Hash::make('pwd123'),
            'role' => 'leader',
            ]);

        User::factory()->create([
            'name' => 'Lina Admin',
            'email' => 'lina.hkl2306@gmail.com',
            'password' => Hash::make('pwd123'),
            'role' => 'admin',
            ]);
    }
}
