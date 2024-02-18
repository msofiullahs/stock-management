<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ProductPrice;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 300; $i++) {
            $productId = random_int(1, 30);
            $dateTime = Carbon::today()->subDays(rand(1, 270))->format('Y-m-d H:i:s');
            if ($i % 2 == 0) {
                $price = ProductPrice::where('product_id', $productId)->where('price_type', 'in')->first();
                DB::table('stocks')->insert([
                    'product_id'    => $productId,
                    'price_id'      => $price->id,
                    'numb_of_stock' => random_int(100, 999),
                    'stock_type'    => 'in',
                    'created_at'    => $dateTime,
                    'updated_at'    => $dateTime
                ]);
            } else {
                $price = ProductPrice::where('product_id', $productId)->where('price_type', 'out')->first();
                DB::table('stocks')->insert([
                    'product_id'    => $productId,
                    'price_id'      => $price->id,
                    'numb_of_stock' => random_int(100, 999),
                    'stock_type'    => 'in',
                    'created_at'    => $dateTime,
                    'updated_at'    => $dateTime
                ]);
            }
        }
    }
}
