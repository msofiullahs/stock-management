<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Stock;

class DashboardController extends Controller
{

    public function index()
    {
        $thisMonth = Carbon::now()->format('m');
        // $stockIn = Stock::where('stock_type', 'in')->whereMonth('created_at', $thisMonth);
        return Inertia::render('Dashboard');
    }

}
