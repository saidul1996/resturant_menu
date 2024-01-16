<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->get();

        return Inertia::render(
            'Category/Index',
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('childrens.childrens')->whereNull('parent_id')->get();

        return Inertia::render(
            'Category/Create',
            [
                'categories' => $categories
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|unique:categories|max:255'
        ]);

        try {
            $parent = Category::find($validated['parent_id']);

            if ($parent->level == 4) return redirect()->route('category.create')->with('message', 'Sorry, Only Possible Up To 4 Level!');

            $validated['level'] = $parent ? $parent->level + 1 : 1;
            Category::create($validated);
            return redirect()->route('category.index')->with('message', 'Category Created Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('category.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::with('childrens.childrens.childrens')->whereNull('parent_id')->get();

        return Inertia::render(
            'Category/Edit',
            [
                'category' => $category,
                'categories' => $categories
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);

        try {
            $category->update($validated);
            return redirect()->route('category.index')->with('message', 'Category Updated Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('category.index')->with('message', 'Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('message', 'Category Delete Successfully');
    }
}
