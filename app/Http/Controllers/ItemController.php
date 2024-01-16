<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->get();

        return Inertia::render(
            'Item/Index',
            [
                'items' => $items
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('childrens.childrens.childrens')->whereNull('parent_id')->get();

        return Inertia::render(
            'Item/Create',
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|unique:items|max:255',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0'
        ]);

        try {
            Item::create($validated);
            return redirect()->route('item.index')->with('message', 'Item Created Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('category.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::with('childrens.childrens.childrens')->whereNull('parent_id')->get();
        return Inertia::render(
            'Item/Edit',
            [
                'item' => $item,
                'categories' => $categories
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:items,name,' . $item->id,
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0'
        ]);

        try {
            $item->update($validated);
            return redirect()->route('item.index')->with('message', 'Item Updated Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('item.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('item.index')->with('message', 'Item Delete Successfully');
    }
}
