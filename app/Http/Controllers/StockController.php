<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Stock;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stocks = Stock::with(['product', 'price', 'supplier'])->orderBy('created_at', 'DESC');

        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $stocks = $stocks->whereHas('product', function($q) use($word) {
                $q->where('product_name', 'LIKE', '%'.$word.'%')
                    ->orWhere('product_code', 'LIKE', '%'.$word.'%');
            });
        }

        $stocks = $stocks->paginate(20);

        return Inertia::render('Stock/Index', ['stocks' => $stocks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $stock = new Stock();
        $stock->product_id = $request->product_id;
        $stock->supplier_id = $request->supplier_id;
        $stock->price_id = $request->price_id;
        $stock->numb_of_stock = $request->numb_of_stock;
        $stock->stock_type = $request->stock_type;
        $stock->stock_note = $request->stock_note;
        $stock->expired_at = $request->expired_at;

        if ($stock->save()) {
            return back()->with('posted', $stock);
        }
        return back()->with('posted', 'failed');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stock = Stock::find($id);
        return response()->json($stock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stock = Stock::find($id);
        $stock->product_id = $request->product_id;
        $stock->supplier_id = $request->supplier_id;
        $stock->price_id = $request->price_id;
        $stock->numb_of_stock = $request->numb_of_stock;
        $stock->stock_type = $request->stock_type;
        $stock->stock_note = $request->stock_note;
        $stock->expired_at = $request->expired_at;

        if ($stock->save()) {
            return back()->with('posted', $stock);
        }
        return back()->with('posted', 'failed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stock = Stock::find($id);
        $stock->delete();
        return back();
    }
}
