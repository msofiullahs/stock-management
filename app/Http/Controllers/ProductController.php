<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('product_name', 'ASC');

        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $products = $products->where('product_name', 'LIKE', '%'.$word.'%')
                ->orWhere('product_code', 'LIKE', '%'.$word.'%');
        }

        $products = $products->paginate(20);

        return Inertia::render('Product/Index', ['products' => $products]);
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
        $product = new Product();
        $product->product_code = $request->product_code;
        $product->product_name = $request->product_name;

        if ($request->hasFile('product_img')) {
            $product->addMediaFromRequest('product_img')->toMediaCollection('images');
        }

        if ($product->save()) {
            $categories = $request->categories;
            $product->categories()->sync($categories);
            return back()->with('posted', $product);
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
        $product = Product::with('categories')->find($id);
        $categories = $product->categories->pluck('id');
        $product->categoryIds = $categories;

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->product_code = $request->product_code;
        $product->product_name = $request->product_name;

        if ($request->hasFile('product_img')) {
            $product->clearMediaCollection('images');
            $product->addMediaFromRequest('product_img')->toMediaCollection('images');
        }

        if ($product->save()) {
            $categories = $request->categories;
            $product->categories()->sync($categories);
            return back()->with('posted', $product);
        }
        return back()->with('posted', 'failed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();
        return back();
    }

    public function codeGenerate()
    {
        do {
            $code = random_int(00000000, 99999999);
        } while (Product::where('product_code', $code)->exists());

        return response()->json(strval($code));
    }

    public function codeCheck(Request $request)
    {
        /**
         * check numbers of digit on ProductForm.vue
         */
        $code = $request->product_code;
        $result = Product::where('product_code', $code)->exists();
        return response()->json($result);
    }
}
