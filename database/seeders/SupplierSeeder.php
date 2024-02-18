<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            do {
                $code = random_int(00000000, 99999999);
            } while (Supplier::where('supplier_code', $code)->exists());

            $supplier = new Supplier();
            $supplier->supplier_code = $code;
            $supplier->supplier_name = 'PT '.Str::random(8).' '.Str::random(5);
            $supplier->save();
        }
    }
}
