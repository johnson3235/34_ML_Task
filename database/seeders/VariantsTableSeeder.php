<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\Variant;
class VariantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
        {
            $this->createVariant('Variant 1', 19.99, 10, true,1,1,2);
            $this->createVariant('Variant 2', 29.99, 5, true,1,2,3);
            $this->createVariant('Variant 3',  14.99, 0, false,2,1,3);
            $this->createVariant('Variant 4',  39.99, 8, true,2,3,4);
            $this->createVariant('Variant 5',  24.99, 15, true,3,1,5);
        }
    
        private function createVariant($title, $price, $stock, $isInStock,$product_id,$op1,$op2)
        {


            $variant =  \App\Models\Variant::create([
                'title' => $title,
                'price' => $price,
                'stock' => $stock,
                'is_in_stock' => $isInStock,
                'product_id' => $product_id,
            ]);
            $variant->options()->attach([$op1, $op2]);
        }
}
