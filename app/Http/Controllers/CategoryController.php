<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('category_name', 'ASC');

        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $categories = $categories->where('category_name', 'LIKE', '%'.$word.'%');
        }

        $categories = $categories->paginate(20);

        return Inertia::render('Category/Index', ['categories' => $categories]);
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
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();

        return back()->with('posted', $category);
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
        $category = Category::find($id);
        $category->category_name = $request->category_name;
        $category->save();

        return back()->with('posted', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();

        return back();
    }

    public function ajaxCategories(Request $request)
    {
        $categories = Category::orderBy('category_name');
        if ($request->has('search') && !empty($request->search)) {
            $word = $request->search;
            $categories = $categories->where('category_name', 'LIKE', '%'.$word.'%');
        }
        $categories = $categories->get();
        return response()->json($categories);
    }
}
