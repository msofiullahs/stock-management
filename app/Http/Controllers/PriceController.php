<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProductPrice;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prices = ProductPrice::with('product')->orderBy('created_at', 'DESC');
        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $prices = $prices->whereHas('product', function($q) use($word) {
                $q->where('product_name', 'LIKE', '%'.$word.'%')
                ->orWhere('product_code', 'LIKE', '%'.$word.'%');
            });
        }
        $prices = $prices->paginate(20);

        return Inertia::render('Price/Index', ['prices' => $prices]);
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
        $price = new ProductPrice();
        $price->product_id = $request->product_id;
        $price->price = $request->price;
        $price->price_type = $request->price_type;

        if ($price->save()) {
            return back()->with('posted', $price);
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
        $price = ProductPrice::find($id);
        return response()->json($price);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $price = ProductPrice::find($id);
        $price->product_id = $request->product_id;
        $price->price = $request->price;
        $price->price_type = $request->price_type;

        if ($price->save()) {
            return back()->with('posted', $price);
        }
        return back()->with('posted', 'failed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $price = ProductPrice::find($id);
        $price->delete();
        return back();
    }

    public function ajaxPrices(Request $request)
    {
        $prices = ProductPrice::select('id', 'price', 'price_type');

        if ($request->has('product_id') && !empty($request->product_id)) {
            $productId = $request->product_id;
            $prices = $prices->where('product_id', $productId);
        }

        if ($request->has('type') && !empty($request->type)) {
            $prices = $prices->where('price_type', $request->type);
        }

        $prices = $prices->get();

        return response()->json($prices);
    }
}
