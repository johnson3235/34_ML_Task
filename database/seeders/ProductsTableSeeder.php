<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\Product;
use app\Models\Variant;
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {


        \App\Models\Product::create([
            'title' => 'Product 1',
            'is_in_stock' => true,
            'average_rating' => 4.5,
           
        ]);

 

        \App\Models\Product::create([
            'title' => 'Product 2',
            'is_in_stock' => false,
            'average_rating' => 3.8,
           
        ]);


        \App\Models\Product::create([
            'title' => 'Product 3',
            'is_in_stock' => true,
            'average_rating' => 1.0,
           
        ]);
    }
}
