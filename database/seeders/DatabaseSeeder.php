<?php

namespace Database\Seeders;

use App\Models\AirplaneSeat;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $array = [
            ['A1', 'A2', 'A3', 'A4', 'A5', 'A6'],
            ['B1', 'B2', 'B3', 'B4', 'B5', 'B6'],
            ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'],
            ['D1', 'D2', 'D3', 'D4', 'D5', 'D6'],
            ['E1', 'E2', 'E3', 'E4', 'E5', 'E6'],
            ['F1', 'F2', 'F3', 'F4', 'F5', 'F6'],
            ['G1', 'G2', 'G3', 'G4', 'G5', 'G6'],
            ['H1', 'H2', 'H3', 'H4', 'H5', 'H6']
        ];

        foreach ($array as $names) {
            foreach ($names as $name) {
                AirplaneSeat::create([
                    'name' => $name
                ]);
            }
        }
    }
}
