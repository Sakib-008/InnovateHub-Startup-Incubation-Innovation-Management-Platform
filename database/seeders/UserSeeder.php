<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@innovatehub.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $founders = User::factory(5)->create(['role' => 'founder']);

        $mentors = User::factory(3)->create([
            'role' => 'mentor',
            'expertise' => 'Product & Growth',
        ]);

        $investors = User::factory(3)->create(['role' => 'investor']);

        foreach ($investors as $investor) {
            $investor->investorProfile()->create([
                'company_name' => fake()->company(),
                'investment_focus' => fake()->randomElement(['Fintech', 'HealthTech', 'EdTech', 'SaaS']),
                'investment_range' => '$10k - $100k',
            ]);
        }
    }
}