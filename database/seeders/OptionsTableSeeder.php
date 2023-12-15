<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\Option;
class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Option::create([
            'name' => 'Color',
            'values' => json_encode(['Red', 'Blue', 'Green']),
        ]);

        \App\Models\Option::create([
            'name' => 'Size',
            'values' => json_encode(['Small', 'Medium', 'Large']),
        ]);

        \App\Models\Option::create([
            'name' => 'Material',
            'values' => json_encode(['Cotton', 'Polyester', 'Silk']),
        ]);

        \App\Models\Option::create([
            'name' => 'Weight',
            'values' => json_encode(['Light', 'Medium', 'Heavy']),
        ]);

        \App\Models\Option::create([
            'name' => 'Brand',
            'values' => json_encode(['Nike', 'Adidas', 'Puma']),
        ]);
    }
}
