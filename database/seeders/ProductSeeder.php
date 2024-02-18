<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            do {
                $code = random_int(00000000, 99999999);
            } while (Product::where('product_code', $code)->exists());

            $product = new Product();
            $product->product_code = $code;
            $product->product_name = 'Product '.Str::random(5);
            $product->save();

            $categories = [];
            for ($c = 0; $c < 4; $c++) {
                do {
                    $catId = random_int(1, 20);
                } while (in_array($catId, $categories));
                $categories[] = $catId;
            }
            $product->categories()->sync($categories);

            $suppliers = [];
            for ($c = 0; $c < 2; $c++) {
                do {
                    $supId = random_int(1, 20);
                } while (in_array($supId, $suppliers));
                $suppliers[] = $supId;
            }
            $product->suppliers()->sync($suppliers);
        }
    }
}
