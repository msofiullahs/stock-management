<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductPrice;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // in price
        for ($i = 0; $i < 30; $i++) {
            do {
                $productId = random_int(1, 30);
            } while (ProductPrice::where('product_id', $productId)->where('price_type', 'in')->exists());

            $price = new ProductPrice();
            $price->product_id = $productId;
            $price->price = 10000 * ($i + 1);
            $price->price_type = 'in';
            $price->save();
        }

        // in price
        for ($i = 0; $i < 30; $i++) {
            do {
                $productId = random_int(1, 30);
            } while (ProductPrice::where('product_id', $productId)->where('price_type', 'out')->exists());

            $price = new ProductPrice();
            $price->product_id = $productId;
            $price->price = 10000 + (1000 * ($i + 1));
            $price->price_type = 'out';
            $price->save();
        }
    }
}
