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
        $stocks = Stock::orderBy('created_at', 'DESC');

        // if ($request->has('search') && !empty($request->search)) {
        //     $word = $request->search;
        //     $stocks = $stocks->where(function($q) use($word) {
        //         $q->where('title', 'LIKE', '%'.$word.'%')
        //             ->orWhere('content', 'LIKE', '%'.$word.'%');
        //     });
        // }

        $stocks = $stocks->paginate(20);

        return Inertia::render('Backend/Article/Index', ['stocks' => $stocks]);
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
