<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sample:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear or remove all sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('stocks')->truncate();
        DB::table('product_prices')->truncate();
        DB::table('products')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('categories')->truncate();
        return 'Data sample has cleared!';
    }
}
