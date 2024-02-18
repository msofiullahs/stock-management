<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function reporting(Request $request)
    {
        $start = Carbon::now()->subMonth()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->has('date') && !empty($request->date)) {
            $dates = $request->date;
            $start = Carbon::parse($dates[0])->format('Y-m-d');
            $end = Carbon::parse($dates[1])->format('Y-m-d');
        }

        $reports = Stock::with(['product', 'price'])
            ->orderBy('created_at', 'ASC')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        if ($request->has('print')) {
            return Inertia::render('Report/Result', ['reports' => $reports]);
        }

        return Inertia::render('Report/Index', ['reports' => $reports]);
    }

}
