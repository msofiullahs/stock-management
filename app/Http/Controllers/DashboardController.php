<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Stock;
use App\Models\StockLog;

class DashboardController extends Controller
{

    public function index()
    {
        $labels = [];
        $stockIn = [];
        $stockOut = [];

        $start = Carbon::now()->subMonth();
        $end = Carbon::now();
        $days = CarbonPeriod::create($start, '1 day', $end);

        foreach ($days as $day) {
            $labels[] = $day->format('d/m/y');
            $date = $day->format('Y-m-d');
            $in = Stock::whereDate('created_at', $date)->where('stock_type', 'in')->sum('numb_of_stock');
            $stockIn[] = $in;
            $out = Stock::whereDate('created_at', $date)->where('stock_type', 'out')->sum('numb_of_stock');
            $stockOut[] = $out;
        }

        return Inertia::render('Dashboard', ['labels' => $labels, 'stockIn' => $stockIn, 'stockOut' => $stockOut]);
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

    public function logs(Request $request)
    {
        $start = Carbon::now()->subMonth()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->has('date') && !empty($request->date)) {
            $dates = $request->date;
            $start = Carbon::parse($dates[0])->format('Y-m-d');
            $end = Carbon::parse($dates[1])->format('Y-m-d');
        }

        $logs = StockLog::with(['user', 'stock'])
            ->orderBy('created_at', 'ASC')
            ->whereBetween('created_at', [$start, $end])
            ->paginate(20)->appends(['date' => [$start, $end]]);

        return Inertia::render('Log/Index', ['logs' => $logs]);
    }

}
