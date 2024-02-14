<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('supplier_name', 'ASC');

        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $suppliers = $suppliers->where('supplier_name', 'LIKE', '%'.$word.'%')
                ->orWhere('supplier_code', 'LIKE', '%'.$word.'%');
        }

        $suppliers = $suppliers->paginate(20);

        return Inertia::render('Supplier/Index', ['suppliers' => $suppliers]);
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
        $supplier = new Supplier();
        $supplier->supplier_code = $request->supplier_code;
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->pic_name = $request->pic_name;
        $supplier->pic_phone = $request->pic_phone;
        $supplier->address = $request->address;

        if ($supplier->save()) {
            return back()->with('posted', $supplier);
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
        $supplier = Supplier::find($id);
        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->supplier_code = $request->supplier_code;
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->pic_name = $request->pic_name;
        $supplier->pic_phone = $request->pic_phone;
        $supplier->address = $request->address;

        if ($supplier->save()) {
            return back()->with('posted', $supplier);
        }
        return back()->with('posted', 'failed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return back();
    }

    public function supplierGenerate()
    {
        do {
            $code = random_int(00000000, 99999999);
        } while (Supplier::where('supplier_code', $code)->exists());

        return response()->json(strval($code));
    }

    public function supplierCheck(Request $request)
    {
        /**
         * check numbers of digit on SupplierForm.vue
         */
        $code = $request->supplier_code;
        $result = Supplier::where('supplier_code', $code)->exists();
        return response()->json($result);
    }

    public function ajaxSuppliers(Request $request)
    {
        $suppliers = Supplier::orderBy('supplier_name', 'ASC');

        if ($request->has('product_id') && !empty($request->product_id)) {
            $productId = $request->product_id;
            $suppliers = $suppliers->whereHas('products', function($q) use($productId) {
                $q->where('id', $productId);
            });
        }

        $suppliers = $suppliers->select('id', 'supplier_name', 'supplier_code')->get();

        return response()->json($suppliers);
    }
}
